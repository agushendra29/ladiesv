<?php
require_once '../init.php';

$role_id         = $_POST['role_id'] ?? '';
$current_role_id = $_SESSION['role_id'] ?? '';
$current_user_id = $_SESSION['distributor_id'] ?? 0;

/**
 * Ambil semua reseller anak (role_id = 5) dari parent tertentu
 */
function getChildResellerIds($pdo, $parentId) {
    $stmt = $pdo->prepare("SELECT id FROM suppliar WHERE parent_id = ? AND role_id = 5");
    $stmt->execute([$parentId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
}

/**
 * Hitung total poin sesuai aturan:
 * 1. Reseller (role 5): poin pembelian + penjualan sendiri.
 * 2. Parent: poin penjualan sendiri + semua transaksi reseller anak (jual & beli).
 * 3. Jika supplier lain menjual ke reseller → poin untuk parent reseller
 *    (reseller tetap dapat poin dari pembelian & penjualan sendiri).
 */
function getTotalQty($pdo, $startDate, $endDate, $distributorId, $roleId) {
    if (!$startDate || !$endDate) return 0;
    $startDateTime = $startDate.' 00:00:00';
    $endDateTime   = $endDate.' 23:59:59';

    // --- CASE 1: reseller sendiri (role 5)
    if ($roleId == 5) {
        $sql = "SELECT COALESCE(SUM(quantity),0)
                FROM transaction_histories
                WHERE suppliar_id = ?
                  AND created_at BETWEEN ? AND ?
                  AND type IN ('penjualan','pembelian')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$distributorId, $startDateTime, $endDateTime]);
        return (int)$stmt->fetchColumn();
    }

    // --- CASE 2: parent (distributor yang punya reseller anak)
    $childResellerIds = getChildResellerIds($pdo, $distributorId);
    $total = 0;

    // 2a. penjualan parent sendiri
    $sqlParent = "SELECT COALESCE(SUM(quantity),0)
                  FROM transaction_histories
                  WHERE suppliar_id = ?
                    AND created_at BETWEEN ? AND ?
                    AND type='penjualan'";
    $stmt = $pdo->prepare($sqlParent);
    $stmt->execute([$distributorId, $startDateTime, $endDateTime]);
    $total += (int)$stmt->fetchColumn();

    // 2b. transaksi anak reseller sendiri (jual & beli)
    if (!empty($childResellerIds)) {
        $inChild = str_repeat('?,', count($childResellerIds) - 1) . '?';

        $sqlChild = "SELECT COALESCE(SUM(quantity),0)
                     FROM transaction_histories
                     WHERE suppliar_id IN ($inChild)
                       AND created_at BETWEEN ? AND ?
                       AND type IN ('penjualan','pembelian')";
        $paramsChild = array_merge($childResellerIds, [$startDateTime, $endDateTime]);
        $stmt = $pdo->prepare($sqlChild);
        $stmt->execute($paramsChild);
        $total += (int)$stmt->fetchColumn();

        // 2c. penjualan dari supplier lain ke reseller anak → poin untuk parent
        $sqlToChild = "SELECT COALESCE(SUM(quantity),0)
                       FROM transaction_histories
                       WHERE customer_id IN ($inChild)
                         AND created_at BETWEEN ? AND ?
                         AND type='penjualan'";
        $paramsToChild = array_merge($childResellerIds, [$startDateTime, $endDateTime]);
        $stmt = $pdo->prepare($sqlToChild);
        $stmt->execute($paramsToChild);
        $total += (int)$stmt->fetchColumn();
    }

    // --- CASE 3: user lain (bukan reseller, bukan parent) ---
    //   Tidak boleh dapat poin dari penjualan ke reseller anak orang lain
    if ($roleId != 5 && empty($childResellerIds)) {
        $sql = "SELECT COALESCE(SUM(quantity),0)
                FROM transaction_histories t
                LEFT JOIN suppliar s ON t.customer_id = s.id
                WHERE t.suppliar_id = ?
                  AND t.created_at BETWEEN ? AND ?
                  AND t.type='penjualan'
                  AND NOT (s.role_id = 5 AND s.parent_id IS NOT NULL)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$distributorId, $startDateTime, $endDateTime]);
        return (int)$stmt->fetchColumn();
    }

    return $total;
}

function getRedeemedPoint($pdo, $userId, $eventName) {
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total_point),0)
                           FROM reward_redemptions
                           WHERE user_id = ? AND event_name = ?
                             AND status IN ('pending','approved','completed')");
    $stmt->execute([$userId, $eventName]);
    return (int)$stmt->fetchColumn();
}

// ---- Ambil Reward ----
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
.event-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 10px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}
.event-card:hover { background: #f9f9f9; }
.event-card strong {
    font-size: 16px; font-weight: 600;
    display: flex; align-items: center; color: #333;
}
.event-card .arrow {
    display: inline-block; margin-right: 10px;
    transition: transform 0.3s ease;
    font-size: 14px; color: #666;
}
.event-card span {
    font-size: 13px; color: #666;
    text-align: right; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
}
.reward-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, max-content));
    gap: 14px; max-height: 0; overflow: hidden;
    margin-top: 0;
    transition: max-height 0.4s ease, margin-top 0.4s ease;
}
.reward-item {
    margin: 10px; background: #fff;
    border-radius: 12px; padding: 18px;
    border: 1px solid #e0e0e0;
    display: flex; flex-direction: column;
    justify-content: space-between;
    min-height: 140px;
    transition: transform 0.2s ease, border-color 0.2s ease;
}
.reward-item:hover { transform: translateY(-2px); border-color: #b0b0b0; }
.reward-info strong { font-size: 16px; color: #222; }
.redeem-form {
    display: flex; gap: 6px;
    align-items: center; margin-top: 8px;
}
.redeem-form input {
    flex: 1; padding: 6px 8px; font-size: 14px;
    border-radius: 6px; border: 1px solid #ccc;
    text-align: center; max-width: 60px;
}
.redeem-form button {
    padding: 6px 12px; font-size: 14px;
    border-radius: 6px; border: none;
    background: linear-gradient(135deg, #ff6b81, #ff477e);
    color: #fff; cursor: pointer;
    font-weight: 500; transition: 0.3s ease;
}
.redeem-form button:hover {
    background: linear-gradient(135deg, #ff477e, #e60050);
    transform: translateY(-1px);
}
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

        $eventTotalPoint = $redeemedPoint = $remainingPoint = 0;
        $eventStart  = strtotime($row['periode_hadiah_dari'].' 00:00:00');
        $eventEnd    = strtotime($row['periode_hadiah_sampai'].' 23:59:59');
        $redeemStart = strtotime($row['redeem_start'].' 00:00:00');
        $redeemEnd   = strtotime($row['redeem_end'].' 23:59:59');

        if ($current_role_id != 1 && $current_role_id != 10) {
            $eventTotalPoint = getTotalQty(
                $pdo,
                date('Y-m-d', $eventStart),
                date('Y-m-d', $eventEnd),
                $current_user_id,
                $current_role_id
            );
            $redeemedPoint = getRedeemedPoint($pdo, $current_user_id, $row['event_name']);
            $remainingPoint = max($eventTotalPoint - $redeemedPoint, 0);
        }

        $eventPeriode = date('d M Y', $eventStart) . ' s/d ' . date('d M Y', $eventEnd);
        $redeemDate   = date('d M Y', $redeemStart) . ' s/d ' . date('d M Y', $eventEnd);

        echo "<div class='event-card' data-target='group{$groupId}'>
                <strong><span class='arrow'>▶</span> ".htmlspecialchars($row['event_name'])."</strong>
                <span><strong>Periode: {$eventPeriode} | Penukaran: {$redeemDate}</strong>
                Total: {$eventTotalPoint} | Redeem: {$redeemedPoint} | Sisa: {$remainingPoint}</span>";

        if ($current_role_id == 10) {
            echo "<a href='index.php?page=edit_reward"
                . "&event_name=" . urlencode($row['event_name'])
                . "&role_id="   . urlencode($row['role_id'])
                . "' class='btn btn-sm' style='margin-left:10px;background:white;color:black;font-weight:bold;border:solid 1px black;'>
                    <i class='fas fa-edit'></i> Edit Event
                  </a>";
        }

        echo "</div>";
        echo "<div class='reward-list group{$groupId}'>";
    }

    $periode = date('d-m-Y', strtotime($row['periode_hadiah_dari'])) .
               " s/d " . date('d-m-Y', strtotime($row['periode_hadiah_sampai']));
    $namaReward = htmlspecialchars($row['nama_reward']);
    if ($current_role_id == 10 && !$row['is_active']) {
        $namaReward .= " <span class='text-danger'>(Tidak Aktif)</span>";
    }

    $eventActive = ($now >= $eventStart && $now <= $eventEnd);

    echo "<div class='reward-item'>
            <div class='reward-info'>
                <strong>{$namaReward}</strong><br>
                <small>{$periode}</small><br>
                <small>Point: {$row['jumlah_point']} | Max Redeem: {$row['max_redeem']}</small>
            </div>";

    if ($current_role_id != 10 && $eventActive && $remainingPoint >= $row['jumlah_point']) {
        $maxRedeemAttr = ($row['max_redeem'] > 0) ? "max='{$row['max_redeem']}'" : "";
        echo "<form class='redeem-form'>
                <input type='hidden' name='reward_id' value='{$row['id']}'>
                <input type='hidden' name='event_name' value='".htmlspecialchars($row['event_name'])."'>
                <input type='number' name='redeem_qty' min='0' {$maxRedeemAttr} placeholder='Qty' required>
                <button type='submit'>Redeem</button>
              </form>";
    } else {
        echo "<div style='margin-top:5px;'>"
           . (!$eventActive ? "<span class='text-muted'>Event Berakhir</span>"
                            : "<span class='text-muted'>Poin Tidak Cukup</span>")
           . "</div>";
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
        const arrow = el.querySelector('.arrow');

        rewards.forEach(row => {
            if (row.style.maxHeight && row.style.maxHeight !== '0px') {
                row.style.maxHeight = '0';
                row.style.marginTop = '0';
                arrow.style.transform = 'rotate(0deg)';
            } else {
                row.style.maxHeight = row.scrollHeight + 'px';
                row.style.marginTop = '12px';
                arrow.style.transform = 'rotate(90deg)';
            }
        });
    });
});
</script>
