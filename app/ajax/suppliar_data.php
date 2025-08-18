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
$searchQuery = " WHERE (role_id = 10 OR is_active = 1)"; // filter utama

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
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar WHERE (role_id = 10 OR is_active = 1)");
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
       "action" => $_SESSION['role_id'] == 1 ? '
    <div class="btn-group" role="group" aria-label="Actions" style="gap:4px;">
        <!-- Tombol Edit -->
        <a href="index.php?page=suppliar_edit&&edit_id=' . $row['id'] . '" 
           class="btn btn-primary btn-sm" 
           style="padding: 4px 8px; font-size: 12px; display: flex; align-items: center; justify-content: center;"
           title="Edit">
           <i class="fas fa-edit" style="font-size:14px;"></i>
        </a>
        
        ' . ($row['role_id'] == 10 ? '
        <!-- Tombol Delete hanya untuk role_id = 10 -->
        <button id="suppliarDelete_btn" data-id="' . $row['id'] . '" 
           class="btn btn-danger btn-sm" 
           style="padding: 4px 8px; font-size: 12px; display: flex; align-items: center; justify-content: center;" 
           title="Hapus">
           <i class="fas fa-trash-alt" style="font-size:14px;"></i>
        </button>' : '') . '
        
        <!-- Tombol Suspend/Aktifkan -->
        <button id="suppliarActive_btn" data-id="' . $row['id'] . '" 
           class="btn btn-warning btn-sm" 
           style="padding: 4px 8px; font-size: 12px; display: flex; align-items: center; justify-content: center;" 
           title="Suspend / Aktifkan">
           <i class="fas fa-toggle-on" style="font-size:14px;"></i>
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
