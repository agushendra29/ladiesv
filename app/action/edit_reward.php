<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $original_event_name = $_POST['original_event_name'] ?? '';
    $original_role_id    = $_POST['original_role_id'] ?? '';
    $event_name   = trim($_POST['event_name'] ?? '');
    $reward_start = trim($_POST['reward_start'] ?? '');
    $reward_end   = trim($_POST['reward_end'] ?? '');
    $redeem_start = trim($_POST['redeem_start'] ?? '');
    $redeem_end   = trim($_POST['redeem_end'] ?? '');
    $role_id      = trim($_POST['role_id'] ?? '');
    $rewards      = $_POST['rewards'] ?? [];

    if (!$event_name || !$reward_start || !$reward_end || !$redeem_start || !$redeem_end || !$role_id) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Lengkapi semua field.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // --- 1) Update info event global
        $pdo->prepare("
            UPDATE rewards
            SET event_name = :event_name,
                periode_hadiah_dari = :phd,
                periode_hadiah_sampai = :phs,
                redeem_start = :rs,
                redeem_end   = :re,
                role_id      = :role_id
            WHERE event_name = :orig_name AND role_id = :orig_role
        ")->execute([
            ':event_name' => $event_name,
            ':phd'        => date('Y-m-d', strtotime(str_replace('-', '/', $reward_start))),
            ':phs'        => date('Y-m-d', strtotime(str_replace('-', '/', $reward_end))),
            ':rs'         => date('Y-m-d', strtotime(str_replace('-', '/', $redeem_start))),
            ':re'         => date('Y-m-d', strtotime(str_replace('-', '/', $redeem_end))),
            ':role_id'    => $role_id,
            ':orig_name'  => $original_event_name,
            ':orig_role'  => $original_role_id,
        ]);

        // --- 2) Kumpulkan ID lama & ID kiriman
        $oldIds = $pdo->prepare("SELECT id FROM rewards WHERE event_name = ? AND role_id = ?");
        $oldIds->execute([$event_name, $role_id]);
        $oldIds = $oldIds->fetchAll(PDO::FETCH_COLUMN);

        $postedIds = [];
        foreach ($rewards as $r) {
            if (!empty($r['id'])) $postedIds[] = (int)$r['id'];
        }

        // --- 3) Hapus reward yang dihapus user (tidak ada di POST)
        if (!empty($oldIds)) {
            $deleteIds = array_diff($oldIds, $postedIds);
            if (!empty($deleteIds)) {
                $in = implode(',', array_fill(0, count($deleteIds), '?'));
                $pdo->prepare("DELETE FROM rewards WHERE id IN ($in)")->execute(array_values($deleteIds));
            }
        }

        // --- 4) Update / Insert reward item
        $updateItem = $pdo->prepare("
            UPDATE rewards
            SET nama_reward = :nama, jumlah_point = :point, max_redeem = :max
            WHERE id = :id
        ");
        $insertItem = $pdo->prepare("
            INSERT INTO rewards
            (event_name, nama_reward, periode_hadiah_dari, periode_hadiah_sampai,
             redeem_start, redeem_end, role_id, jumlah_point, max_redeem, created_at, is_active)
            VALUES
            (:event_name, :nama, :phd, :phs, :rs, :re, :role_id, :point, :max, NOW(), 1)
        ");

        foreach ($rewards as $r) {
            $nama  = trim($r['nama_reward'] ?? '');
            if (!$nama) continue;
            $point = (int)($r['jumlah_point'] ?? 0);
            $max   = (int)($r['max_redeem'] ?? 0);

            if (!empty($r['id'])) {
                $updateItem->execute([
                    ':nama'  => $nama,
                    ':point' => $point,
                    ':max'   => $max,
                    ':id'    => $r['id'],
                ]);
            } else {
                $insertItem->execute([
                    ':event_name' => $event_name,
                    ':nama'       => $nama,
                    ':phd'        => date('Y-m-d', strtotime(str_replace('-', '/', $reward_start))),
                    ':phs'        => date('Y-m-d', strtotime(str_replace('-', '/', $reward_end))),
                    ':rs'         => date('Y-m-d', strtotime(str_replace('-', '/', $redeem_start))),
                    ':re'         => date('Y-m-d', strtotime(str_replace('-', '/', $redeem_end))),
                    ':role_id'    => $role_id,
                    ':point'      => $point,
                    ':max'        => $max,
                ]);
            }
        }

        $pdo->commit();
        header('Content-Type: application/json');
        echo json_encode(['status'=>'success','message'=>'Reward berhasil diperbarui']);
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status'=>'error','message'=>'Method not allowed']);
}
