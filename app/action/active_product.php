<?php
require '../init.php';

if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    // Tentukan status baru
    $new_status = ($action === 'activate') ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE products SET is_active = ? WHERE id = ?");
    $res = $stmt->execute([$new_status, $id]);

    if ($res) {
        echo json_encode(['status' => 'success', 'new_status' => $new_status]);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'invalid']);
}
