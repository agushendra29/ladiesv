<?php
require_once '../init.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'Invalid request method']);
    exit;
}

$orderId = $_POST['order_id'];
$fromDistributorId = $_POST['suppliar_id']; // distributor yang meng-approve

try {
    $pdo->beginTransaction();

    // Ambil data order langsung dari tabel purchase_orders
    $stmt = $pdo->prepare("SELECT * FROM purchase_orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();

    if (!$order) {
        throw new Exception('Order not found.');
    }

    if ($order['status'] !== 'pending') {
        throw new Exception('Order already processed.');
    }

    $toDistributorId = $order['user_order_id'];  // distributor yang memesan
    $productId = $order['product_id'];
    $qty = $order['quantity'];

    // 1. Kurangi stok distributor sumber
    $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id = ? AND product_id = ?");
    $stmt->execute([$fromDistributorId, $productId]);
    $fromStock = $stmt->fetch();

    if (!$fromStock) {
        throw new Exception("Stock distributor sumber tidak ditemukan.");
    }

    $newStockFrom = $fromStock['stock'] - $qty;
    if ($newStockFrom < 0) $newStockFrom = 0;

    $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = ? WHERE id = ?");
    $stmt->execute([$newStockFrom, $fromStock['id']]);

    // 2. Tambah stok ke distributor tujuan
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

    $type = "out";
    // 3. Catat histori dari sisi pengirim
    $stmt = $pdo->prepare("INSERT INTO transaction_histories (suppliar_id, type, product_id, quantity, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$fromDistributorId,"out", $productId, $qty]);

    // 4. Catat histori dari sisi penerima
    $stmt = $pdo->prepare("INSERT INTO transaction_histories (suppliar_id, type, product_id, quantity, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$toDistributorId,"in",  $productId, $qty]);

    // 5. Update status order jadi approved
    $stmt = $pdo->prepare("UPDATE purchase_orders SET status = 'approved', approved_at = NOW() WHERE id = ?");
    $stmt->execute([$orderId]);

    $pdo->commit();
    echo json_encode(['status' => true, 'message' => 'Order approved and stock updated.']);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}
