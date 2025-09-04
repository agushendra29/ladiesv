<?php
require_once '../init.php';

## Read value
$draw            = $_POST['draw'];
$row             = $_POST['start'];
$rowperpage      = $_POST['length']; // Rows display per page
$columnIndex     = $_POST['order'][0]['column']; // Column index
$columnName      = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue     = $_POST['search']['value']; // Search value
$user_id         = $_SESSION['distributor_id'];
$roleFilter      = isset($_POST['roleFilter']) ? $_POST['roleFilter'] : '';

$searchArray = [];

## Role-based access
if ($_SESSION['role_id'] == 10) {
    // Super Admin -> lihat semua
    $searchQuery = " WHERE 1=1 ";
} elseif ($_SESSION['role_id'] == 1) {
    // HO -> lihat semua kecuali HO & Super Admin
    $searchQuery = " WHERE role_id NOT IN (1,10)";
} elseif ($_SESSION['role_id'] == 2) {
    // HD -> lihat supplier child-nya (anak dari distributor_id ini) yang aktif
    $searchQuery = " WHERE parent_id = :parent_id AND is_active = 1";
    $searchArray['parent_id'] = (int)$_SESSION['distributor_id'];
} elseif ($_SESSION['role_id'] == 3) {
    // Distributor -> hanya lihat sesama distributor
    $searchQuery = " WHERE role_id = 3 AND is_active = 1";
} elseif ($_SESSION['role_id'] == 4) {
    // Agen -> hanya lihat distributor
    $searchQuery = " WHERE role_id = 3 AND is_active = 1";
} else {
    // Default fallback -> hanya lihat yang aktif
    $searchQuery = " WHERE is_active = 1";
}

## Tambahan: filter berdasarkan role dari dropdown
if (!empty($roleFilter)) {
    $searchQuery .= " AND role_id = :roleFilter";
    $searchArray['roleFilter'] = $roleFilter;
}

## Search (include "suspend" special case)
$searchValueLower = strtolower($searchValue);
$suspendKeywords  = ['sus', 'susp', 'suspe', 'suspen', 'suspend'];

if ($searchValue != '') {
    if ($_SESSION['role_id'] == 10 && in_array($searchValueLower, $suspendKeywords)) {
        // Khusus Super Admin -> cari user suspend
        $searchQuery .= " AND is_active = 0 ";
    } else {
        // Normal search
        $searchQuery .= " AND (suppliar_code LIKE :suppliar_code 
            OR name LIKE :name 
            OR con_num LIKE :con_num)";
        $searchArray['suppliar_code'] = "%$searchValue%";
        $searchArray['name']          = "%$searchValue%";
        $searchArray['con_num']       = "%$searchValue%";
    }
}

## Total number of records tanpa filter
if ($_SESSION['role_id'] == 10) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar");
} elseif ($_SESSION['role_id'] == 1) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar WHERE role_id NOT IN (1,10)");
} elseif ($_SESSION['role_id'] == 2) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar WHERE parent_id = :parent_id AND is_active = 1");
    $stmt->bindValue(':parent_id', (int)$_SESSION['distributor_id'], PDO::PARAM_INT);
} elseif ($_SESSION['role_id'] == 3) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar WHERE role_id = 3 AND is_active = 1");
} elseif ($_SESSION['role_id'] == 4) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar WHERE role_id = 3 AND is_active = 1");
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar WHERE is_active = 1");
}
$stmt->execute();
$totalRecords = $stmt->fetchColumn();

## Total number of records dengan filter
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar " . $searchQuery);
foreach ($searchArray as $key => $val) {
    $stmt->bindValue(':' . $key, $val);
}
$stmt->execute();
$totalRecordwithFilter = $stmt->fetchColumn();

## Fetch records data
$stmt = $pdo->prepare("SELECT * FROM suppliar " . $searchQuery . 
    " ORDER BY " . $columnName . " " . $columnSortOrder . 
    " LIMIT :limit OFFSET :offset");

foreach ($searchArray as $key => $val) {
    $stmt->bindValue(':' . $key, $val);
}
$stmt->bindValue(':limit', (int)$rowperpage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$row, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

## Helper functions
function getRoleName($role_id) {
    switch ($role_id) {
        case 1: return 'HO';
        case 2: return 'HD';
        case 3: return 'D';
        case 4: return 'A';
        case 5: return 'R';
        case 10: return 'Super Admin';
        default: return '-';
    }
}
function setActive($isActive) {
    return $isActive == 0 ? " (Suspend)" : "";
}

## Format response data
$data = [];
foreach ($empRecords as $row) {
    $data[] = [
        "id"         => getRoleName($row['role_id']) . '-' . $row['suppliar_code'],
        "name"       => $row['name'] . setActive($row['is_active']),
        "address"    => $row['address'],
        "con_num"    => $row['con_num'],
        "role_id"    => getRoleName($row['role_id']),
        "created_at" => $row['create_at'],
    "action" => ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10) ? '
<div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center; justify-content:center;">
    <!-- Edit -->
    <a href="index.php?page=suppliar_edit&&edit_id=' . $row['id'] . '" 
       class="btn btn-primary btn-sm"
       style="padding:3px 8px; font-size:11px; border-radius:5px; display:flex; align-items:center; justify-content:center;">
       Edit
    </a>
    <!-- Suspend / Aktifkan -->
    <button id="suppliarActive_btn" 
            data-id="' . $row['id'] . '" 
            data-status="' . $row['is_active'] . '"
            class="btn btn-warning btn-sm"
            style="padding:3px 8px; font-size:11px; border-radius:5px; display:flex; align-items:center; justify-content:center;"
            title="' . ($row['is_active'] == 1 ? 'Suspend' : 'Aktifkan') . '">
       ' . ($row['is_active'] == 1 ? 'Suspend' : 'Aktifkan') . '
    </button>
    <!-- Reset Password -->
    <form action="app/action/reset_password.php" method="post" style="display:inline;">
        <input type="hidden" name="username" value="' . $row['suppliar_code'] . '">
        <input type="hidden" name="nik_last6" value="' . substr($row['nik'], -6) . '">
        <button type="submit" class="btn btn-danger btn-sm"
            style="background:red; padding:3px 8px; color:white; font-size:11px !important; border-radius:5px; display:flex; align-items:center; justify-content:center;"
            onclick="return confirm(\'Yakin reset password ' . $row['name'] . '?\')">
            Reset
        </button>
    </form>
</div>' : ''



    ];
}

## Response JSON
$response = [
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
];
echo json_encode($response);
