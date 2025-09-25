<?php
require_once '../init.php';

/* ===== Helper ambil nama wilayah dari API EMSIFA ===== */
function getWilayahNames($prov_id, $kab_id, $kec_id) {
    $base = "https://www.emsifa.com/api-wilayah-indonesia/api";
    $provName = $kabName = $kecName = '';
    try {
        if ($prov_id) {
            $prov = @json_decode(file_get_contents("$base/province/$prov_id.json"), true);
            $provName = $prov['name'] ?? '';
        }
        if ($kab_id) {
            $kab  = @json_decode(file_get_contents("$base/regency/$kab_id.json"), true);
            $kabName = $kab['name'] ?? '';
        }
        if ($kec_id) {
            $kec  = @json_decode(file_get_contents("$base/district/$kec_id.json"), true);
            $kecName = $kec['name'] ?? '';
        }
    } catch (\Exception $e) {
        // biarkan kosong bila gagal
    }
    return trim("$provName, $kabName, $kecName", ', ');
}

/* ===== DataTables Request ===== */
$draw            = $_POST['draw'];
$row             = $_POST['start'];
$rowperpage      = $_POST['length'];
$columnIndex     = $_POST['order'][0]['column'];
$columnName      = $_POST['columns'][$columnIndex]['data'];
$columnSortOrder = $_POST['order'][0]['dir'];
$searchValue     = $_POST['search']['value'];
$roleFilter      = $_POST['roleFilter'] ?? '';

$searchArray = [];

/* ===== Role-based filter ===== */
if ($_SESSION['role_id'] == 10) {
    $searchQuery = " WHERE 1=1 ";
} elseif ($_SESSION['role_id'] == 1) {
    $searchQuery = " WHERE role_id NOT IN (1,10)";
} elseif ($_SESSION['role_id'] == 2) {
    $searchQuery = " WHERE parent_id = :parent_id AND is_active = 1";
    $searchArray['parent_id'] = (int)$_SESSION['distributor_id'];
} elseif ($_SESSION['role_id'] == 3) {
    $searchQuery = " WHERE role_id = 3 AND is_active = 1";
} elseif ($_SESSION['role_id'] == 4) {
    $searchQuery = " WHERE role_id = 3 AND is_active = 1";
} elseif ($_SESSION['role_id'] == 5) {
    $searchQuery = " WHERE role_id IN (2,3,4) AND is_active = 1";
} else {
    $searchQuery = " WHERE is_active = 1";
}

/* ===== Filter role dropdown ===== */
if (!empty($roleFilter)) {
    $searchQuery .= " AND role_id = :roleFilter";
    $searchArray['roleFilter'] = $roleFilter;
}

/* ===== Search & Suspend keyword ===== */
$searchValueLower = strtolower($searchValue);
$suspendKeywords  = ['sus','susp','suspe','suspen','suspend'];
if ($searchValue != '') {
    if ($_SESSION['role_id'] == 10 && in_array($searchValueLower, $suspendKeywords)) {
        $searchQuery .= " AND is_active = 0 ";
    } else {
        $searchQuery .= " AND (suppliar_code LIKE :suppliar_code 
            OR name LIKE :name 
            OR con_num LIKE :con_num)";
        $searchArray['suppliar_code'] = "%$searchValue%";
        $searchArray['name']          = "%$searchValue%";
        $searchArray['con_num']       = "%$searchValue%";
    }
}

/* ===== Total records ===== */
if ($_SESSION['role_id'] == 10) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM suppliar");
} elseif ($_SESSION['role_id'] == 1) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM suppliar WHERE role_id NOT IN (1,10)");
} elseif ($_SESSION['role_id'] == 2) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM suppliar WHERE parent_id = :parent_id AND is_active = 1");
    $stmt->bindValue(':parent_id', (int)$_SESSION['distributor_id'], PDO::PARAM_INT);
} elseif ($_SESSION['role_id'] == 3) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM suppliar WHERE role_id = 3 AND is_active = 1");
} elseif ($_SESSION['role_id'] == 4) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM suppliar WHERE role_id = 3 AND is_active = 1");
} elseif ($_SESSION['role_id'] == 5) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM suppliar WHERE role_id IN (2,3,4) AND is_active = 1");
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM suppliar WHERE is_active = 1");
}
$stmt->execute();
$totalRecords = $stmt->fetchColumn();

/* ===== Total filtered ===== */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM suppliar $searchQuery");
foreach ($searchArray as $k => $v) $stmt->bindValue(':'.$k, $v);
$stmt->execute();
$totalRecordwithFilter = $stmt->fetchColumn();

/* ===== Fetch data ===== */
$stmt = $pdo->prepare("SELECT * FROM suppliar $searchQuery 
    ORDER BY $columnName $columnSortOrder
    LIMIT :limit OFFSET :offset");
foreach ($searchArray as $k => $v) $stmt->bindValue(':'.$k, $v);
$stmt->bindValue(':limit', (int)$rowperpage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$row, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

/* ===== Helper role & active ===== */
function getRoleName($role_id) {
    switch ($role_id) {
        case 1:  return 'HO';
        case 2:  return ($_SESSION['role_id']==1||$_SESSION['role_id']==10)?'HD':'D';
        case 3:  return 'D';
        case 4:  return 'A';
        case 5:  return 'R';
        case 10: return 'SA';
        default: return '-';
    }
}
function setActive($isActive) { return $isActive == 0 ? " (Suspend)" : ""; }

/* ===== Format response ===== */
$data = [];
foreach ($empRecords as $row) {
    // Alamat: provinsi/kab/kec untuk role 4 & 5
    
    $alamat = $row['address'];
    if (in_array($_SESSION['role_id'], [4,5])) {
        $alamat = getWilayahNames(
            $row['provinsi'] ?? null,
            $row['kota']  ?? null,
            $row['kecamatan'] ?? null
        );
    }

    $data[] = [
        "id"         => $_SESSION['role_id']==4 || $_SESSION['role_id']==5 ? '-' : getRoleName($row['role_id']).'-'.$row['suppliar_code'],
        "name"       => $row['name'].setActive($row['is_active']),
        "address"    => $alamat,
        "con_num"    => $row['con_num'],
        "role_id"    => getRoleName($row['role_id']),
        "created_at" => ($_SESSION['role_id']==1 || $_SESSION['role_id']==10) ? $row['create_at'] : '-',
        // ===== tombol PERSIS seperti semula =====
        "action" => (($_SESSION['role_id']==1 && $row['role_id']>1 && $row['role_id']<10) || $_SESSION['role_id']==10) ? '
<div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center; justify-content:center;">
    <a href="index.php?page=suppliar_edit&&edit_id=' . $row['id'] . '" 
       class="btn btn-primary btn-sm"
       style="padding:3px 8px; font-size:11px; border-radius:5px; display:flex; align-items:center; justify-content:center;">
       Edit
    </a>
    <button id="suppliarActive_btn" 
            data-id="' . $row['id'] . '" 
            data-status="' . $row['is_active'] . '"
            class="btn btn-warning btn-sm"
            style="padding:3px 8px; font-size:11px; border-radius:5px; display:flex; align-items:center; justify-content:center;"
            title="' . ($row['is_active'] == 1 ? 'Suspend' : 'Aktifkan') . '">
       ' . ($row['is_active'] == 1 ? 'Suspend' : 'Aktifkan') . '
    </button>
    <form action="app/action/reset_password.php" method="post" style="display:inline;">
        <input type="hidden" name="username" value="' . $row['suppliar_code'] . '">
        <input type="hidden" name="nik_last6" value="' . substr($row['nik'], -6) . '">
        <button type="submit" class="btn btn-danger btn-sm"
            style="background:red; padding:3px 8px; color:white; font-size:11px !important; border-radius:5px; display:flex; align-items:center; justify-content:center;"
            onclick="return confirm(\'Yakin reset password ' . $row['name'] . '?\')">
            Reset
        </button>
    </form>'
    . ($row['role_id']==5 ? '
    <button class="btn btn-success btn-sm upgrade-btn"
            data-id="' . $row['id'] . '" 
            data-name="' . htmlspecialchars($row['name']) . '"
            style="padding:3px 8px; font-size:11px; border-radius:5px;">
        Upgrade
    </button>' : '') .
'</div>' : ''
    ];
}

/* ===== Output JSON ===== */
echo json_encode([
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
]);
