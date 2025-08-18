<?php
require_once '../init.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$user_id = $_SESSION['distributor_id'];

$searchArray = array();

## Search 
if ($_SESSION['role_id'] == 10 || $_SESSION['role_id'] == 1) {
    // Super Admin -> bisa lihat semua
    $searchQuery = " WHERE 1=1 ";
} else {
    // User biasa -> hanya lihat yang aktif
    $searchQuery = " WHERE is_active = 1 ";
}

if ($searchValue != '') {
    $searchQuery .= " AND (suppliar_code LIKE :suppliar_code 
        OR name LIKE :name 
        OR con_num LIKE :con_num)";
    $searchArray = array(
        'suppliar_code' => "%$searchValue%",
        'name' => "%$searchValue%",
        'con_num' => "%$searchValue%"
    );
}

## Total number of records without filtering
if ($_SESSION['role_id'] == 10 || $_SESSION['role_id'] == 1) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar");
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar WHERE is_active = 1");
}
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar " . $searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $pdo->prepare("SELECT * FROM suppliar " . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit OFFSET :offset");

foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', (int)$rowperpage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$row, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

## Function untuk mapping role_id ke nama role
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

$data = array();
foreach ($empRecords as $row) {
    $data[] = array(
        "id" => getRoleName($row['role_id']) . '-' . $row['suppliar_code'],
        "name" => $row['name'] . setActive($row['is_active']),
        "address" => $row['address'],
        "con_num" => $row['con_num'],
        "role_id" => getRoleName($row['role_id']),
       "action" => $_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10 ? '
    <div class="btn-group" role="group" aria-label="Actions" style="gap:4px;">
        <!-- Tombol Edit -->
        <a href="index.php?page=suppliar_edit&&edit_id=' . $row['id'] . '" 
           class="btn btn-primary btn-sm" 
           style="padding: 4px 8px; font-size: 12px; display: flex; align-items: center; justify-content: center;"
           title="Edit">
           <i class="fas fa-edit" style="font-size:14px;"></i>
        </a>
        
        
        <!-- Tombol Suspend/Aktifkan -->
      <button id="suppliarActive_btn" 
        data-id="' . $row['id'] . '" 
        data-status="' . $row['is_active'] . '"
        class="btn btn-warning btn-sm" 
        style="padding: 4px 8px; font-size: 12px; display: flex; align-items: center; justify-content: center;" 
        title="' . ($row['is_active'] == 1 ? 'Suspend' : 'Aktifkan') . '">
   <i class="fas ' . ($row['is_active'] == 1 ? 'fa-toggle-off' : 'fa-toggle-on') . '" style="font-size:14px;"></i> 
   ' . ($row['is_active'] == 1 ? 'Suspend' : 'Aktifkan') . '
</button>

    </div>
' : '',

    );
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
