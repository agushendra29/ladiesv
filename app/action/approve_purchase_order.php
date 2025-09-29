<?php
require_once '../init.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'Invalid request method']);
    exit;
}

$po_id = $_POST['approve_po_id'] ?? null;
$fromDistributorId = 1; // distributor/ pusat yang approve
$payment_type   = $_POST['payment_type'] ?? null;
$shipping_type  = $_POST['shipping_type'] ?? null;
$now= date('Y-m-d H:i:s');

if (!$po_id || !$payment_type || !$shipping_type) {
    echo json_encode(['status' => false, 'message' => 'Missing po_id']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Ambil semua order item dengan po_id
    $stmt = $pdo->prepare("SELECT * FROM purchase_orders WHERE po_id = ?");
    $stmt->execute([$po_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$orders) {
        throw new Exception('Order tidak ditemukan.');
    }

    // Pastikan semua item masih pending
    foreach ($orders as $o) {
        if ($o['status'] !== 'pending') {
            throw new Exception("Order {$o['id']} sudah diproses.");
        }
    }

    $toDistributorId = $orders[0]['suppliar_id']; // distributor tujuan (pemesan)
    $invoice_number = 'INV-' . strtoupper(uniqid());

    function getProductName($pdo, $productId) {
    $stmt = $pdo->prepare("SELECT product_name FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['product_name'] : null; // return null kalau tidak ada
}

    // ====== 1. Cek semua stok pusat/distributor sumber dulu ======
    foreach ($orders as $o) {
        $productId = $o['product_id'];
        $qty       = $o['quantity'];

        $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id = ? AND product_id = ?");
        $stmt->execute([1, $productId]);
        $fromStock = $stmt->fetch();

         $productName = getProductName($pdo, $productId);
        if (!$fromStock) {
            throw new Exception("Stok tidak ditemukan untuk product_id {$productId}");
        }
        if ($fromStock['stock'] < $qty) {
            throw new Exception("Stok tidak cukup untuk produk <b>{$productName}</b>. <br>Sisa: {$fromStock['stock']}, <br>permintaan: {$qty}");
        }
    }

    // ====== 2. Semua stok cukup → update stok dan order ======
    foreach ($orders as $o) {
        $productId = $o['product_id'];
        $qty       = $o['quantity'];

        // Kurangi stok distributor sumber
        $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id = ? AND product_id = ?");
        $stmt->execute([$fromDistributorId, $productId]);
        $fromStock = $stmt->fetch();

        $newStockFrom = $fromStock['stock'] - $qty;
        $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = ? WHERE id = ?");
        $stmt->execute([$newStockFrom, $fromStock['id']]);

        // Tambahkan stok ke distributor tujuan
        $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id = ? AND product_id = ?");
        $stmt->execute([$toDistributorId, $productId]);
        $toStock = $stmt->fetch();

        if ($toStock) {
            $newStockTo = $toStock['stock'] + $qty;
            $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = ? WHERE id = ?");
            $stmt->execute([$newStockTo, $toStock['id']]);
        } else {
            // insert baru
            $stmt = $pdo->prepare("SELECT name FROM suppliar WHERE id = ?");
            $stmt->execute([$toDistributorId]);
            $user = $stmt->fetch();

            $stmt2 = $pdo->prepare("SELECT product_name FROM products WHERE id = ?");
            $stmt2->execute([$productId]);
            $prod = $stmt2->fetch();

            $stmt = $pdo->prepare("INSERT INTO distributor_stocks (suppliar_id, product_id, stock, suppliar_name, product_name) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$toDistributorId, $productId, $qty, $user['name'], $prod['product_name']]);
        }

        // Update status order item
        $stmt = $pdo->prepare("UPDATE purchase_orders SET invoice_number = ?, status = 'approved', approved_at = ? WHERE id = ?");
        $stmt->execute([$invoice_number,$now, $o['id']]);
    }

    // ====== 3. Catat histori transaksi ======
    $stmt = $pdo->prepare("SELECT name FROM suppliar WHERE id = ?");
    $stmt->execute([$toDistributorId]);
    $user = $stmt->fetch();

    foreach ($orders as $o) {
        $stmt = $pdo->prepare("INSERT INTO transaction_histories (suppliar_id, type, product_id, quantity, created_at, customer_id, customer_name, invoice_number,payment_type, jenis_pengiriman) 
            VALUES (?, 'pembelian', ?, ?, ?, ?, ?, ?,?,?)");
        $stmt->execute([
            $fromDistributorId,
            $o['product_id'],
            $o['quantity'],
            $toDistributorId,
            $now,
            $user['name'],
            $invoice_number,
            $payment_type,
            $shipping_type
        ]);
          $stmt = $pdo->prepare("INSERT INTO transaction_histories (suppliar_id, type, product_id, quantity, created_at, customer_id, customer_name, invoice_number,payment_type, jenis_pengiriman) 
            VALUES (?, 'penjualan', ?, ?, ?, ?, ?, ?,?,?)");
        $stmt->execute([
            $fromDistributorId,
            $o['product_id'],
            $o['quantity'],
            $toDistributorId,
            $now,
            $user['name'],
            $invoice_number,
            $payment_type,
            $shipping_type
        ]);
    }

    $pdo->commit();
    echo json_encode(['status' => true, 'message' => "Order {$po_id} berhasil diapprove. Invoice: {$invoice_number}"]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}
