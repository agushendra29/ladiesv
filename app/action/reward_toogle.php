<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}

$id = $_POST['id'] ?? null;
$newStatus = $_POST['status'] ?? null; // 0 = hide, 1 = unhide

if (!$id || !isset($newStatus)) {
    http_response_code(400);
    echo "Data tidak lengkap";
    exit;
}

// Update is_active
try {
    $stmt = $pdo->prepare("UPDATE rewards SET is_active = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);

    echo $newStatus == 1 ? "Reward berhasil ditampilkan." : "Reward berhasil disembunyikan.";
} catch (Exception $e) {
    http_response_code(500);
    echo "Gagal mengubah status: " . $e->getMessage();
}
