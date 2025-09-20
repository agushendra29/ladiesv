<?php
require_once '../init.php';
header('Content-Type: application/json');

$current_user_id = $_SESSION['distributor_id'] ?? 0;
if (!$current_user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User tidak ditemukan.']);
    exit;
}

// ---- Ambil input ----
$reward_id  = isset($_POST['reward_id']) ? (int)$_POST['reward_id'] : 0;
$event_name = trim($_POST['event_name'] ?? '');
$qty        = isset($_POST['redeem_qty']) ? (int)$_POST['redeem_qty'] : 0;
$remainingPoint = isset($_POST['remaining_point']) ? (int)$_POST['remaining_point'] : 0;

if ($reward_id <= 0 || $qty <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak valid.']);
    exit;
}

try {
    // --- Data reward
    $stmt = $pdo->prepare("SELECT * FROM rewards WHERE id = ?");
    $stmt->execute([$reward_id]);
    $reward = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reward) {
        echo json_encode(['status' => 'error', 'message' => 'Reward tidak ditemukan.']);
        exit;
    }

    // --- Periode redeem
    $now = date('Y-m-d H:i:s');
    if (!empty($reward['redeem_start']) && !empty($reward['redeem_end'])) {
        $start = $reward['redeem_start'].' 00:00:00';
        $end   = $reward['redeem_end'].' 23:59:59';
        if ($now < $start || $now > $end) {
            echo json_encode(['status' => 'error', 'message' => 'Periode redeem sudah berakhir / belum dimulai.']);
            exit;
        }
    }

    $point_per_unit = (int)$reward['jumlah_point'];
    $total_point    = $point_per_unit * $qty;

    // --- Hitung total poin yang dimiliki user pada periode event
    $periodeStart = $reward['periode_hadiah_dari'];
    $periodeEnd   = $reward['periode_hadiah_sampai'];

    function getTotalQty($pdo, $start, $end, $userId) {
        $sql = "SELECT COALESCE(SUM(quantity),0) 
                FROM transaction_histories
                WHERE type='penjualan' AND suppliar_id = ?
                  AND created_at BETWEEN ? AND ?";
        $st = $pdo->prepare($sql);
        $st->execute([$userId, $start.' 00:00:00', $end.' 23:59:59']);
        return (int)$st->fetchColumn();
    }

    $user_point = $remainingPoint;

    // --- Total poin sudah diredeem user pada event ini
    $sql = "SELECT COALESCE(SUM(total_point),0)
            FROM reward_redemptions
            WHERE user_id = ? AND event_name = ?
              AND status IN ('pending','approved','completed')";
    $st = $pdo->prepare($sql);
    $st->execute([$current_user_id, $event_name]);
    $used_point = (int)$st->fetchColumn();

    $available_point = $user_point;

    if ($total_point > $available_point) {
        echo json_encode(['status' => 'error', 'message' => 'Point tidak mencukupi.']);
        exit;
    }

    // --- Validasi maksimal redeem (jumlah total unit yg boleh diredeem)
    $max_redeem = (int)$reward['max_redeem'];
    if ($max_redeem > 0) {
        // hitung total qty_redeem yang sudah dilakukan user untuk reward ini
        $stmtQty = $pdo->prepare("
            SELECT COALESCE(SUM(qty_redeem),0)
            FROM reward_redemptions
            WHERE user_id = ? AND reward_id = ?
              AND status IN ('pending','approved','completed')
        ");
        $stmtQty->execute([$current_user_id, $reward_id]);
        $qty_used = (int)$stmtQty->fetchColumn();

        if (($qty_used + $qty) > $max_redeem) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Maksimal redeem hanya '.$max_redeem.
                             ' unit. Anda sudah menukar '.$qty_used.' unit.'
            ]);
            exit;
        }
    }
    // jika max_redeem == 0, tidak ada batasan unit

    // --- Simpan redeem
    $stmt = $pdo->prepare("
        INSERT INTO reward_redemptions
            (reward_id, user_id, event_name, qty_redeem, point_per_unit, total_point, status)
        VALUES
            (:reward_id, :user_id, :event_name, :qty_redeem, :point_per_unit, :total_point, 'pending')
    ");
    $ok = $stmt->execute([
        ':reward_id'      => $reward_id,
        ':user_id'        => $current_user_id,
        ':event_name'     => $event_name,
        ':qty_redeem'     => $qty,
        ':point_per_unit' => $point_per_unit,
        ':total_point'    => $total_point
    ]);

    if ($ok) {
        echo json_encode(['status' => 'success', 'message' => 'Redeem berhasil diajukan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data redeem.']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
