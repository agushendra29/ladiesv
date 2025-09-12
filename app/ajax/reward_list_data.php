<?php 
require_once '../init.php';

$role_id = $_POST['role_id'] ?? '';
$current_role_id = $_SESSION['role_id'] ?? '';
$current_user_id = $_SESSION['distributor_id'] ?? 0;

function getChildDistributorIds($pdo, $parentId) {
    $stmt = $pdo->prepare("SELECT id FROM suppliar WHERE parent_id = ?");
    $stmt->execute([$parentId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
}

function getTotalQty($pdo, $startDate, $endDate, $distributorId, $roleId) {
    if (!$startDate || !$endDate) return 0;

    // perbaikan: akhir hari 23:59:59
    $startDateTime = $startDate.' 00:00:00';
    $endDateTime = $endDate.' 23:59:59';
    
    if ($roleId == 2) {
        $childIds = getChildDistributorIds($pdo, $distributorId);
        $childIds[] = $distributorId;
        $in = str_repeat('?,', count($childIds) - 1) . '?';
        $sql = "SELECT COALESCE(SUM(quantity),0) FROM transaction_histories 
                WHERE type='penjualan' AND suppliar_id IN ($in) 
                AND created_at BETWEEN ? AND ?";
        $params = array_merge($childIds, [$startDateTime, $endDateTime]);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    } else {
        $sql = "SELECT COALESCE(SUM(quantity),0) FROM transaction_histories 
                WHERE type='penjualan' AND suppliar_id = ? 
                AND created_at BETWEEN ? AND ?";
        $params = [$distributorId, $startDateTime, $endDateTime];
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}

function getRedeemedPoint($pdo, $userId, $eventName) {
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total_point),0) 
                           FROM reward_redemptions 
                           WHERE user_id = ? AND event_name = ? 
                           AND status IN ('pending','approved','completed')");
    $stmt->execute([$userId, $eventName]);
    return (int)$stmt->fetchColumn();
}

// Ambil rewards
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
    echo "<p>Tidak ada data.</p>";
    exit;
}

$currentRole = null;
$currentEvent = null;
$groupId = 0;

// gunakan timestamp saat ini
$now = time();
?>

<style>
    /* -- CSS sama seperti sebelumnya -- */
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

    .event-card:hover {
        background: #f9f9f9;
    }

    .event-card strong {
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        color: #333;
    }

    .event-card .arrow {
        display: inline-block;
        margin-right: 10px;
        transition: transform 0.3s ease;
        font-size: 14px;
        color: #666;
    }

    .event-card span {
        font-size: 13px;
        color: #666;
        text-align: right;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .reward-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, max-content));
        /* ganti 1fr -> max-content */
        gap: 14px;
        max-height: 0;
        overflow: hidden;
        margin-top: 0;
        transition: max-height 0.4s ease, margin-top 0.4s ease;
    }

    .reward-item {
        margin: 10px;
        background: #fff;
        border-radius: 12px;
        padding: 18px;
        border: 1px solid #e0e0e0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 140px;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .reward-item:hover {
        transform: translateY(-2px);
        border-color: #b0b0b0;
    }

    .reward-info strong {
        font-size: 16px;
        color: #222;
    }

    .redeem-form {
        display: flex;
        gap: 6px;
        align-items: center;
        margin-top: 8px;
    }

    .redeem-form input {
        flex: 1;
        padding: 6px 8px;
        font-size: 14px;
        border-radius: 6px;
        border: 1px solid #ccc;
        text-align: center;
        max-width: 60px;
    }

    .redeem-form button {
        padding: 6px 12px;
        font-size: 14px;
        border-radius: 6px;
        border: none;
        background: linear-gradient(135deg, #ff6b81, #ff477e);
        color: #fff;
        cursor: pointer;
        font-weight: 500;
        transition: 0.3s ease;
    }

    .redeem-form button:hover {
        background: linear-gradient(135deg, #ff477e, #e60050);
        transform: translateY(-1px);
    }
</style>

<?php 
foreach($rows as $index => $row){
    if ($current_role_id != 10 && !$row['is_active']) continue;

    if ($currentRole !== $row['role_id']) {
        $currentRole = $row['role_id'];
        $currentEvent = null;
    }

    if ($currentEvent !== $row['event_name']) {
        $currentEvent = $row['event_name'];
        $groupId++;

        $eventTotalPoint = $redeemedPoint = $remainingPoint = 0;
        $eventStart = strtotime($row['periode_hadiah_dari'].' 00:00:00');
        $eventEnd = strtotime($row['periode_hadiah_sampai'].' 23:59:59'); // perbaikan akhir hari
        $redeemStart = strtotime($row['redeem_start'].' 00:00:00');
        $redeemEnd = strtotime($row['redeem_end'].' 23:59:59'); // perbaikan akhir hari

        if ($current_role_id != 1 && $current_role_id != 10) {
            $eventTotalPoint = getTotalQty($pdo, date('Y-m-d', $eventStart), date('Y-m-d', $eventEnd), $current_user_id, $current_role_id);
            $redeemedPoint = getRedeemedPoint($pdo, $current_user_id, $row['event_name']);
            $remainingPoint = max($eventTotalPoint - $redeemedPoint, 0);
        }

        $eventPeriode = date('d M Y', $eventStart) . ' s/d ' . date('d M Y', $eventEnd);
        
        $redeemDate = date('d M Y', $redeemStart) . ' s/d ' . date('d M Y', $eventEnd);
     

     echo "<div class='event-card' data-target='group{$groupId}'>
        <strong><span class='arrow'>▶</span> ".htmlspecialchars($row['event_name'])."</strong>
        <span><strong>Periode: {$eventPeriode} | Penukaran: {$redeemDate} </strong> Total: {$eventTotalPoint} | Redeem: {$redeemedPoint} | Sisa: {$remainingPoint}</span>";

if ($current_role_id == 10) {
    echo "<a href='index.php?page=edit_reward"
       . "&event_name=" . urlencode($row['event_name'])
       . "&role_id="   . urlencode($row['role_id'])
       . "' class='btn btn-sm' style='margin-left:10px;background: white;color:black; font-weight:bold;border:solid 1px black;'>
            <i class='fas fa-edit'></i> Edit Event
          </a>";
}

echo "</div>";

        echo "<div class='reward-list group{$groupId}'>";
    }

    $periode = date('d-m-Y', strtotime($row['periode_hadiah_dari'])) . " s/d " . date('d-m-Y', strtotime($row['periode_hadiah_sampai']));
    $namaReward = htmlspecialchars($row['nama_reward']);
    if ($current_role_id == 10 && !$row['is_active']) $namaReward .= " <span class='text-danger'>(Tidak Aktif)</span>";

    $eventActive = ($now >= $eventStart && $now <= $eventEnd); // perbaikan cek event aktif

    echo "<div class='reward-item'>
            <div class='reward-info'>
                <strong>{$namaReward}</strong><br>
                <small>{$periode}</small><br>
                <small>Point: {$row['jumlah_point']} | Max Redeem: {$row['max_redeem']}</small>
            </div>";

    if ($current_role_id != 10 && $eventActive && $remainingPoint >= $row['jumlah_point']) {
        $maxRedeemAttr = ($row['max_redeem'] > 0) ? "max='{$row['max_redeem']}'" : "";
        echo "<form class='redeem-form' style='display:flex; justify-content:center;'>
                <input type='hidden' name='reward_id' value='{$row['id']}'>
                <input type='hidden' name='event_name' value='".htmlspecialchars($row['event_name'])."'>
                <input type='number' name='redeem_qty' min='0' {$maxRedeemAttr} placeholder='Qty' required>
                <button type='submit'>Redeem</button>
              </form>";
    } else {
        echo "<div style='margin-top:5px;'>".(!$eventActive ? "<span class='text-muted'>Event Berakhir</span>" : "<span class='text-muted'>Poin Tidak Cukup</span>")."</div>";
    }

    echo "</div>";

    $nextRow = $rows[$index+1] ?? null;
    if (!$nextRow || $nextRow['event_name'] !== $currentEvent) echo "</div>";
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