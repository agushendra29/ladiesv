<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name     = trim($_POST['event_name'] ?? '');
    $reward_start   = trim($_POST['reward_start'] ?? '');
    $reward_end     = trim($_POST['reward_end'] ?? '');
    $redeem_start   = trim($_POST['redeem_start'] ?? '');
    $redeem_end     = trim($_POST['redeem_end'] ?? '');
    $role_id        = trim($_POST['role_id'] ?? '');
    $rewards        = $_POST['rewards'] ?? [];

    if ($event_name && $reward_start && $reward_end && $redeem_start && $redeem_end && $role_id && !empty($rewards)) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("
                INSERT INTO rewards 
                (event_name, nama_reward, periode_hadiah_dari, periode_hadiah_sampai, redeem_start, redeem_end, role_id, jumlah_point, max_redeem, created_at, is_active)
                VALUES 
                (:event_name, :nama_reward, :periode_hadiah_dari, :periode_hadiah_sampai, :redeem_start, :redeem_end, :role_id, :jumlah_point, :max_redeem, NOW(), 1)
            ");

            foreach ($rewards as $reward) {
                if (!empty($reward['nama_reward']) && isset($reward['jumlah_point'])) {
                    $stmt->execute([
                        ':event_name'          => $event_name,
                        ':nama_reward'         => trim($reward['nama_reward']),
                        ':periode_hadiah_dari' => date('Y-m-d', strtotime(str_replace('-', '/', $reward_start))),
                        ':periode_hadiah_sampai'=> date('Y-m-d', strtotime(str_replace('-', '/', $reward_end))),
                        ':redeem_start'        => date('Y-m-d', strtotime(str_replace('-', '/', $redeem_start))),
                        ':redeem_end'          => date('Y-m-d', strtotime(str_replace('-', '/', $redeem_end))),
                        ':role_id'             => $role_id,
                        ':jumlah_point'        => (int)$reward['jumlah_point'],
                        ':max_redeem'          => (int)($reward['max_redeem'] ?? 0),
                    ]);
                }
            }

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Reward berhasil disimpan']);
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Silakan lengkapi semua field.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
