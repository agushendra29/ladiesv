<?php
require_once '../init.php';   // Pastikan path sesuai
header('Content-Type: application/json');

// ---- ambil kode referal dari GET ----
$kode = trim($_GET['kode'] ?? '');
if ($kode === '') {
    echo json_encode(['ok' => false, 'message' => 'Kode referal kosong']);
    exit;
}

// ---- query cek kode ----
$sql = "SELECT id,name 
        FROM suppliar
        WHERE suppliar_code = :kode AND role_id != 10 AND role_id != 1 AND role_id != 5
        LIMIT 1";

$stmt = $pdo->prepare($sql);      // ← gunakan $pdo, sama seperti contoh action Anda
$stmt->execute([':kode' => $kode]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// ---- respon JSON ----
if ($data) {
    echo json_encode([
        'ok'      => true,
        'message' => 'Kode referal valid',
        'data'    => $data
    ]);
} else {
    echo json_encode([
        'ok'      => false,
        'message' => 'Kode referal tidak ditemukan'
    ]);
}
