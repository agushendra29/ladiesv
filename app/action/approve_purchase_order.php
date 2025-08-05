<?php
require_once '../init.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'Invalid request method']);
    exit;
}

$orderId = $_POST['order_id'];
$fromDistributorId = 1; // distributor yang meng-approve

try {
    $pdo->beginTransaction();

    // Ambil data order
    $stmt = $pdo->prepare("SELECT * FROM purchase_orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();

    if (!$order) {
        throw new Exception('Order not found.');
    }

    if ($order['status'] !== 'pending') {
        throw new Exception('Order already processed.');
    }

    $toDistributorId = $order['suppliar_id'];
    $productId = $order['product_id'];
    $qty = $order['quantity'];

    // Cek stok distributor sumber
    $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id = ? AND product_id = ?");
    $stmt->execute([$fromDistributorId, $productId]);
    $fromStock = $stmt->fetch();

    if (!$fromStock) {
        throw new Exception("Stok distributor sumber tidak ditemukan.");
    }

    if ($fromStock['stock'] < $qty) {
        throw new Exception("Stok tidak cukup. Sisa stok: {$fromStock['stock']}, permintaan: {$qty}");
    }

    // Kurangi stok distributor sumber
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
        $stmt = $pdo->prepare("INSERT INTO distributor_stocks (suppliar_id, product_id, stock) VALUES (?, ?, ?)");
        $stmt->execute([$toDistributorId, $productId, $qty]);
    }

    // Catat histori pengirim
    $stmt = $pdo->prepare("INSERT INTO transaction_histories (suppliar_id, type, product_id, quantity, created_at) VALUES (?, 'out', ?, ?, NOW())");
    $stmt->execute([$fromDistributorId, $productId, $qty]);

    // Catat histori penerima
    $stmt = $pdo->prepare("INSERT INTO transaction_histories (suppliar_id, type, product_id, quantity, created_at) VALUES (?, 'in', ?, ?, NOW())");
    $stmt->execute([$toDistributorId, $productId, $qty]);

    // Update status order menjadi approved
    $stmt = $pdo->prepare("UPDATE purchase_orders SET status = 'approved', approved_at = NOW() WHERE id = ?");
    $stmt->execute([$orderId]);

    $pdo->commit();
    echo json_encode(['status' => true, 'message' => 'Order approved and stock updated.']);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}
