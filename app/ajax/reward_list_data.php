<?php
require_once '../init.php';

$role_id         = $_POST['role_id'] ?? '';
$current_role_id = $_SESSION['role_id'] ?? '';
$current_user_id = $_SESSION['distributor_id'] ?? 0;

function getChildDistributorIds($pdo, $parentId) {
    $stmt = $pdo->prepare("SELECT id FROM suppliar WHERE parent_id = ?");
    $stmt->execute([$parentId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
}
function getTotalQty($pdo, $startDate, $endDate, $distributorId, $roleId) {
    if (!$startDate || !$endDate) return 0;
    if ($roleId == 2) {
        $childIds = getChildDistributorIds($pdo, $distributorId);
        if (!$childIds) return 0;
        $in  = str_repeat('?,', count($childIds) - 1) . '?';
        $sql = "SELECT COALESCE(SUM(quantity),0)
                FROM transaction_histories
                WHERE type='penjualan'
                  AND suppliar_id IN ($in)
                  AND created_at BETWEEN ? AND ?";
        $params = array_merge($childIds, [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    } else {
        $sql = "SELECT COALESCE(SUM(quantity),0)
                FROM transaction_histories
                WHERE type='penjualan'
                  AND suppliar_id = ?
                  AND created_at BETWEEN ? AND ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$distributorId, $startDate.' 00:00:00', $endDate.' 23:59:59']);
        return (int)$stmt->fetchColumn();
    }
}
function getRedeemedPoint($pdo, $userId, $eventName) {
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(total_point),0)
        FROM reward_redemptions
        WHERE user_id = ? AND event_name = ? AND status IN ('pending','approved','completed')
    ");
    $stmt->execute([$userId, $eventName]);
    return (int)$stmt->fetchColumn();
}

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

if(!$rows){
    echo "<tr><td colspan='8'>Tidak ada data.</td></tr>";
    exit;
}

$currentRole  = null;
$currentEvent = null;
$groupId      = 0;
$now          = date('Y-m-d');

foreach($rows as $row){
    if ($current_role_id != 10 && !$row['is_active']) continue;

    if ($currentRole !== $row['role_id']) {
        $currentRole  = $row['role_id'];
        $currentEvent = null;
    }

   // ...
if ($currentEvent !== $row['event_name']) {
    $currentEvent = $row['event_name'];
    $groupId++;

    $eventStart = $row['periode_hadiah_dari'];
    $eventEnd   = $row['periode_hadiah_sampai'];
    $eventStartFmt = $eventStart ? date('d-m-Y', strtotime($eventStart)) : '-';
    $eventEndFmt   = $eventEnd   ? date('d-m-Y', strtotime($eventEnd))   : '-';

    // === Hitung hanya bila role bukan 1 & 10 ===
    $eventTotalPoint = 0;
    $redeemedPoint   = 0;
    $remainingPoint  = 0;
    if ($current_role_id != 1 && $current_role_id != 10) {
        $eventTotalPoint = getTotalQty($pdo, $eventStart, $eventEnd, $current_user_id, $current_role_id);
        $redeemedPoint   = getRedeemedPoint($pdo, $current_user_id, $row['event_name']);
        $remainingPoint  = max($eventTotalPoint - $redeemedPoint, 0);
    }

    echo "<tr class='table-secondary toggle-event' data-target='group{$groupId}' style='cursor:pointer;'>
            <td colspan='8'>
                ▶ Event: ".htmlspecialchars($row['event_name'])."
                <span style='color:#555; font-size:12px; margin-left:10px;'>
                    ({$eventStartFmt} s/d {$eventEndFmt})
                </span>
            </td>
          </tr>";

    // detail event
    echo "<tr class='event-row group{$groupId}' style='display:none; background:#f9f9f9;'>
            <td colspan='8' style='font-weight:600; color:#006eff;'>
                <div style='display:flex; justify-content:space-between; align-items:center;'>";

    // tampilkan total point hanya untuk role selain 1 & 10
    if ($current_role_id != 1 && $current_role_id != 10) {
        echo "<span>
                Total Poin Anda: ".number_format($eventTotalPoint)."
                &nbsp; | &nbsp; Sudah Redeem: ".number_format($redeemedPoint)."
                &nbsp; | &nbsp; Sisa: ".number_format($remainingPoint)."
              </span>";
    }

    if ($current_role_id == 10) {
        echo "<a href='index.php?page=edit_reward"
           . "&event_name=" . urlencode($row['event_name'])
           . "&role_id="   . urlencode($row['role_id'])
           . "' class='btn btn-sm btn-outline-primary'>
                <i class='fas fa-edit'></i> Edit Event
              </a>";
    }

    echo "      </div>
            </td>
          </tr>";
}
// ...


    $periode = date('d-m-Y', strtotime($row['periode_hadiah_dari'])) . " s/d " .
               date('d-m-Y', strtotime($row['periode_hadiah_sampai']));
    $namaReward = htmlspecialchars($row['nama_reward']);
    if ($current_role_id == 10 && !$row['is_active']) {
        $namaReward .= " <span class='text-danger fst-italic'>(Tidak Aktif)</span>";
    }

    $eventActive = ($now >= $row['periode_hadiah_dari'] && $now <= $row['periode_hadiah_sampai']);

    echo "<tr class='event-row group{$groupId}' style='display:none;'>
            <td>{$namaReward}</td>
            <td>{$periode}</td>
            <td>".htmlspecialchars($row['jumlah_point'])."</td>
            <td>".htmlspecialchars($row['max_redeem'])."</td>
            <td>";

    if ($current_role_id != 10 && $eventActive && $remainingPoint >= $row['jumlah_point']) {
        echo "
        <form class='redeem-form d-flex align-items-center'>
            <input type='hidden' name='reward_id' value='{$row['id']}'>
            <input type='hidden' name='event_name' value='".htmlspecialchars($row['event_name'])."'>
            <input type='number' name='redeem_qty' class='form-control form-control-sm'
                   min='1' max='".(int)$row['max_redeem']."'
                   placeholder='Qty'
                   style='width:70px;padding:12px;background-color:#f9fafb;border:1px solid #d1d5db;
                          border-radius:10px;font-size:10px;text-align:right;margin-right:10px;' required>
            <button type='submit' class='btn btn-sm btn-primary' style='font-size:12px;background:white;color:black;'>Redeem</button>
        </form>";
    } else {
        echo !$eventActive
            ? "<span class='text-muted mr-5'>Event Berakhir</span>"
            : "<span class='text-muted mr-5'>Poin Tidak Cukup</span>";
    }

    echo "</td></tr>";
}
