<?php
require_once '../init.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'Metode request tidak valid']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$change = isset($_POST['change']) ? intval($_POST['change']) : 0;
$user_id = $_SESSION['user_id'] ?? 0;

if ($id <= 0 || $change === 0) {
    echo json_encode(['status' => false, 'message' => 'Parameter tidak valid']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Lock current stock row for update
    $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE id = ? FOR UPDATE");
    $stmt->execute([$id]);
    $stockRecord = $stmt->fetch();

    if (!$stockRecord) {
        throw new Exception("Record stok tidak ditemukan.");
    }

    $currentStock = (int)$stockRecord['stock'];
    $newStock = $currentStock + $change;

    if ($newStock < 0) {
        throw new Exception("Stock tidak boleh kurang dari 0.");
    }

    // Update stock quantity
    $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = ? WHERE id = ?");
    $stmt->execute([$newStock, $id]);

    // Prepare log data similar to your format
    $logData = array(
        'suppliar_id'  => $stockRecord['suppliar_id'],
        'product_id'   => $stockRecord['product_id'],
        'action_type'  => $change > 0 ? 'add' : 'minus',
        'old_quantity' => $currentStock,
        'new_quantity' => $newStock,
        'changed_by'   => $user_id,
        'note'         => $change > 0 ? 'Stock added' : 'Stock reduced',
    );

    // Insert log using your $obj->create method
    $obj->create('stock_logs', $logData);

    $pdo->commit();

    echo json_encode(['status' => true, 'message' => 'Stok berhasil diperbarui.', 'new_stock' => $newStock]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}
