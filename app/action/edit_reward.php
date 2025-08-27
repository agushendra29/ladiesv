<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id                   = $_POST['id'] ?? null;
    $nama_reward           = trim($_POST['nama_reward'] ?? '');
    $periode_hadiah_dari   = trim($_POST['periode_hadiah_dari'] ?? '');
    $periode_hadiah_sampai = trim($_POST['periode_hadiah_sampai'] ?? '');
    $role_id               = trim($_POST['role_id'] ?? '');
    $jumlah_point          = trim($_POST['jumlah_point'] ?? '');
    $max_redeem            = trim($_POST['max_redeem'] ?? '');

    if ($id && $nama_reward !== '' && $periode_hadiah_dari !== '' && $periode_hadiah_sampai !== '' && $role_id !== '' && $jumlah_point !== '') {
        try {
            $pdo->beginTransaction();

            $rewardData = [
                'nama_reward'           => $nama_reward,
                'periode_hadiah_dari'   => $periode_hadiah_dari,
                'periode_hadiah_sampai' => $periode_hadiah_sampai,
                'role_id'               => $role_id,
                'jumlah_point'          => $jumlah_point,
                'max_redeem'            => $max_redeem,
                'updated_at'            => date('Y-m-d H:i:s')
            ];

            $obj->update('rewards', $rewardData, ['id' => $id]);

            $pdo->commit();
            echo 'Reward berhasil diupdate.';
        } catch (Exception $e) {
            $pdo->rollBack();
            echo 'Gagal update reward: ' . $e->getMessage();
        }
    } else {
        echo 'Silakan lengkapi semua field.';
    }
} else {
    http_response_code(405);
    echo 'Method not allowed';
}
