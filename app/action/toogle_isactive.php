<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $action = $_POST['action']; // publish / hide

    if ($action === 'publish') {
        $stmt = $pdo->prepare("UPDATE news SET is_active = 1 WHERE id = :id");
    } elseif ($action === 'hide') {
        $stmt = $pdo->prepare("UPDATE news SET is_active = 0 WHERE id = :id");
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        exit;
    }

    $stmt->execute(['id' => $id]);
    echo json_encode(['status' => 'success']);
}
