<?php
require_once '../init.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'Invalid request method']);
    exit;
}

$orderId = $_POST['order_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM purchase_orders WHERE po_id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();

    if (!$order) {
        throw new Exception('Order not found.');
    }

    if ($order['status'] !== 'pending') {
        throw new Exception('Order already processed.');
    }

    // Ubah status jadi rejected
    $stmt = $pdo->prepare("UPDATE purchase_orders SET status = 'rejected', approved_at = '' WHERE po_id = ?");
    $stmt->execute([$orderId]);

    echo json_encode(['status' => true, 'message' => 'Order rejected successfully.']);

} catch (Exception $e) {
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}
