<?php
require_once '../init.php';

$role_id         = $_POST['role_id'] ?? '';
$current_role_id = $_SESSION['role_id'] ?? '';
$current_user_id = $_SESSION['distributor_id'] ?? 0;

/**
 * Ambil semua child id dari tabel suppliar (versi lama)
 */
function getAllChildIds($pdo, $parentId) {
    $stmt = $pdo->prepare("SELECT id FROM suppliar WHERE parent_id = ?");
    $stmt->execute([$parentId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
}

/**
 * =========================
 * PERHITUNGAN BARU
 * (pakai kolom di transaction_histories: suppliar_parent_id, customer_role_id)
 * =========================
 */
function getTotalQty(PDO $pdo, string $startDate, string $endDate, int $userId): int
{
    if (!$startDate || !$endDate) return 0;

    $start = $startDate . ' 00:00:00';
    $end   = $endDate   . ' 23:59:59';

    $sql = "
        SELECT COALESCE(SUM(th.quantity), 0) AS total_point
        FROM transaction_histories th
        WHERE th.type = 'penjualan'
          AND th.is_refund = 0
          AND th.created_at BETWEEN :start AND :end
          AND (
              (th.suppliar_id = :uid AND th.customer_role_id != 5)
              OR (th.suppliar_parent_id = :uid)
          )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':uid'   => $userId,
        ':start' => $start,
        ':end'   => $end,
    ]);

    return (int) $stmt->fetchColumn();
}

/**
 * =========================
 * PERHITUNGAN LAMA (getPreviousCalculation)
 * (menggunakan suppliar.parent_id, levelup_history & distributor_management_history cutoff)
 * =========================
 */
function getPreviousCalculation(PDO $pdo, string $startDate, string $endDate, int $distributorId): int
{
    if (!$startDate || !$endDate) return 0;

    $start = $startDate . ' 00:00:00';
    $end   = $endDate   . ' 23:59:59';

    // 1. Ambil ID Distributor dan anak-anaknya yang aktif (dari tabel suppliar)
    $childIds = getAllChildIds($pdo, $distributorId);
    $allActiveIds = array_merge([$distributorId], $childIds);

    if (empty($allActiveIds)) {
        $allActiveIds = [$distributorId];
    }

    $phActive = implode(',', array_fill(0, count($allActiveIds), '?'));

    // 2. Ambil Mantan Anak (levelup_history)
    $formerChildIdsQuery = "
        SELECT suppliar_id, created_at AS upgrade_date
        FROM levelup_history 
        WHERE role_from = 5 AND role_to <> 5 AND parent_id_before = ?
    ";
    $stmt = $pdo->prepare($formerChildIdsQuery);
    $stmt->execute([$distributorId]);
    $formerChildren = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $formerChildIds = array_column($formerChildren, 'suppliar_id');
    $formerChildIds = array_diff($formerChildIds, $allActiveIds);

    $formerPh = '';
    $formerParams = [];
    if (!empty($formerChildIds)) {
        $formerPh = implode(',', array_fill(0, count($formerChildIds), '?'));
        $formerParams = array_values($formerChildIds);
    }

    // 3. Query utama (sama logika dengan yang kamu berikan)
    $sql = "
    SELECT COALESCE(SUM(th.quantity),0) AS total_point
    FROM transaction_histories th
    LEFT JOIN suppliar c ON c.id = th.customer_id

    -- Riwayat Perubahan Role Customer (untuk exclusion logic)
    LEFT JOIN levelup_history lhc ON lhc.suppliar_id = c.id AND lhc.role_from = 5 AND lhc.role_to <> 5

    -- Riwayat Perubahan Role Seller (untuk cutoff penjualan Mantan Anak)
    LEFT JOIN levelup_history lhs ON lhs.suppliar_id = th.suppliar_id AND lhs.role_from = 5 AND lhs.role_to <> 5

    -- Riwayat Manajemen Distribusi (untuk cutoff JOIN anak aktif)
    LEFT JOIN (
        SELECT 
            suppliar_id, 
            MIN(created_at) AS join_date
        FROM distributor_management_history
        WHERE current_parent_id = ? 
        GROUP BY suppliar_id
    ) AS dmh ON dmh.suppliar_id = th.suppliar_id

    WHERE th.type = 'penjualan'
      AND th.created_at BETWEEN ? AND ?
      AND th.is_refund = 0

      AND (
          (
            th.suppliar_id IN ($phActive) 
            AND (
                th.suppliar_id = ? 
                OR th.created_at >= COALESCE(dmh.join_date, '1900-01-01 00:00:00')
            )
          )
          " . (!empty($formerPh) ? "
          OR (
            th.suppliar_id IN ($formerPh)
            AND th.created_at < lhs.created_at
          )
          " : "") . "
      )

      -- Eksklusi transaksi ke customer yang dulunya Reseller (R5)
      AND NOT (lhc.id IS NOT NULL AND th.created_at < lhc.created_at)

      -- Eksklusi customer yang saat ini reseller (role_id = 5)
      AND (c.role_id IS NULL OR c.role_id <> 5)
    ";

    // Susun parameter sesuai urutan placeholder:
    // 1) DMH current_parent_id = ?                => $distributorId
    // 2) BETWEEN ? AND ?                          => $start, $end
    // 3) IN ($phActive) placeholders             => ...$allActiveIds
    // 4) th.suppliar_id = ?                       => $distributorId
    // 5) formerPh placeholders (jika ada)         => ...$formerParams
    $params = [];
    $params[] = $distributorId;       // for dmh subquery
    $params[] = $start;               // between start
    $params[] = $end;                 // between end
    // append allActiveIds for IN ($phActive)
    foreach ($allActiveIds as $id) $params[] = $id;
    $params[] = $distributorId;       // th.suppliar_id = ?
    // append former params if any
    foreach ($formerParams as $f) $params[] = $f;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}

/**
 * Total poin yang sudah diredeem user di event tertentu
 */
function getRedeemedPoint($pdo, $userId, $eventName) {
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(total_point), 0)
        FROM reward_redemptions
        WHERE user_id = ? AND event_name = ?
          AND status IN ('pending','approved','completed')
    ");
    $stmt->execute([$userId, $eventName]);
    return (int)$stmt->fetchColumn();
}

/* ---------------------------
   Ambil daftar reward & tampilkan
   --------------------------- */
$sql = "SELECT * FROM rewards WHERE 1=1";
$params = [];
if ($role_id !== '' && $role_id !== 'all') {
    $sql .= " AND role_id = ?";
    $params[] = $role_id;
}
$sql .= " ORDER BY role_id, event_name, id";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    echo "<p>Tidak ada data.</p>";
    exit;
}

$currentRole  = null;
$currentEvent = null;
$groupId      = 0;
$now          = time();
?>

<style>
/* styling sama seperti sebelumnya, ringkas supaya fokus pada logic */
.event-card { background:#fff;border:1px solid #e0e0e0;border-radius:12px;padding:16px 20px;margin-bottom:10px;cursor:pointer;display:flex;justify-content:space-between;align-items:center; }
.event-card strong{font-size:16px;}
.event-card .meta{font-size:13px;color:#666;text-align:right}
.reward-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,max-content));gap:14px;max-height:0;overflow:hidden;margin-top:0;transition:max-height .4s,margin-top .4s}
.reward-item{margin:10px;background:#fff;border-radius:12px;padding:18px;border:1px solid #e0e0e0;min-height:120px}
.redeem-form input{padding:6px;border-radius:6px;border:1px solid #ccc}
.redeem-form button{padding:6px 12px;border-radius:6px;border:none;background:linear-gradient(135deg,#ff6b81,#ff477e);color:#fff}
</style>

<?php
foreach ($rows as $index => $row) {
    if ($current_role_id != 10 && !$row['is_active']) continue;

    if ($currentRole !== $row['role_id']) {
        $currentRole  = $row['role_id'];
        $currentEvent = null;
    }

    if ($currentEvent !== $row['event_name']) {
        $currentEvent = $row['event_name'];
        $groupId++;

        $eventStart  = strtotime($row['periode_hadiah_dari'].' 00:00:00');
        $eventEnd    = strtotime($row['periode_hadiah_sampai'].' 23:59:59');

        $totalBaru = $totalLama = $redeemedPoint = $remainingPoint = 0;

        if ($current_role_id != 1 && $current_role_id != 10) {
            // perhitungan baru
            $totalBaru = getTotalQty(
                $pdo,
                date('Y-m-d', $eventStart),
                date('Y-m-d', $eventEnd),
                $current_user_id
            );

            // perhitungan lama (menggunakan parent_id & history cutoff)
            $totalLama = getPreviousCalculation(
                $pdo,
                date('Y-m-d', $eventStart),
                date('Y-m-d', $eventEnd),
                $current_user_id
            );

            $redeemedPoint = getRedeemedPoint($pdo, $current_user_id, $row['event_name']);
            $remainingPoint = max($totalBaru - $redeemedPoint, 0);
        }

        $eventPeriode = date('d M Y', $eventStart) . ' s/d ' . date('d M Y', $eventEnd);

        echo "<div class='event-card' data-target='group{$groupId}'>
                <strong>".htmlspecialchars($row['event_name'])."</strong>
                <div class='meta'>
                    <div><small>Periode: {$eventPeriode}</small></div>
                    <div>Baru: <strong>{$totalBaru}</strong> | Lama: <strong>{$totalLama}</strong></div>
                    <div>Redeem: <strong>{$redeemedPoint}</strong> | Sisa (Baru): <strong>{$remainingPoint}</strong></div>
                </div>
              </div>";
        echo "<div class='reward-list group{$groupId}'>";
    }

    $periode = date('d-m-Y', strtotime($row['periode_hadiah_dari'])) . " s/d " . date('d-m-Y', strtotime($row['periode_hadiah_sampai']));
    $namaReward = htmlspecialchars($row['nama_reward']);
    if ($current_role_id == 10 && !$row['is_active']) $namaReward .= " <span style='color:#c00'>(Tidak Aktif)</span>";

    $eventActive = ($now >= strtotime($row['periode_hadiah_dari']) && $now <= strtotime($row['periode_hadiah_sampai'].' 23:59:59'));

    echo "<div class='reward-item'>
            <div style='margin-bottom:10px;'>
                <strong>{$namaReward}</strong><br>
                <small>{$periode}</small><br>
                <small>Point: {$row['jumlah_point']} | Max Redeem: {$row['max_redeem']}</small>
            </div>";

    if ($current_role_id != 10 && $eventActive && $remainingPoint >= $row['jumlah_point']) {
        $maxRedeemAttr = ($row['max_redeem'] > 0) ? "max='{$row['max_redeem']}'" : "";
        echo "<form class='redeem-form'>
                <input type='hidden' name='remaining_point' value='{$remainingPoint}'>
                <input type='hidden' name='reward_id' value='{$row['id']}'>
                <input type='hidden' name='event_name' value='".htmlspecialchars($row['event_name'])."'>
                <input type='number' name='redeem_qty' min='0' {$maxRedeemAttr} placeholder='Qty' required>
                <button type='submit'>Redeem</button>
              </form>";
    } else {
        echo "<div style='margin-top:10px;color:#888;'>" . 
             (!$eventActive ? "Event Berakhir" : "Poin Tidak Cukup") .
             "</div>";
    }

    echo "</div>";

    $nextRow = $rows[$index + 1] ?? null;
    if (!$nextRow || $nextRow['event_name'] !== $currentEvent) {
        echo "</div>";
    }
}
?>

<script>
document.querySelectorAll('.event-card').forEach(el => {
    el.addEventListener('click', () => {
        const targetClass = el.dataset.target;
        const rewards = document.querySelectorAll(`.${targetClass}`);
        rewards.forEach(row => {
            if (row.style.maxHeight && row.style.maxHeight !== '0px') {
                row.style.maxHeight = '0';
                row.style.marginTop = '0';
            } else {
                row.style.maxHeight = row.scrollHeight + 'px';
                row.style.marginTop = '12px';
            }
        });
    });
});
</script>
