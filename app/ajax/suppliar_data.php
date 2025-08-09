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
$searchQuery = "";
if ($searchValue != '') {
    $searchQuery = " WHERE (suppliar_code LIKE :suppliar_code 
        OR name LIKE :name 
        OR con_num LIKE :con_num)";
    $searchArray = array(
        'suppliar_code' => "%$searchValue%",
        'name' => "%$searchValue%",
        'con_num' => "%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM suppliar");
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
        default: return '-';
    }
}

$data = array();
foreach ($empRecords as $row) {
    $data[] = array(
        "id" => $row['suppliar_code'],
        "name" => $row['name'],
        "address" => $row['address'],
        "con_num" => $row['con_num'],
        "role_id" => getRoleName($row['role_id']),
        "action" => $_SESSION['role_id'] == 1 ? '
            <div class="btn-group">
                <a href="index.php?page=suppliar_edit&&edit_id=' . $row['id'] . '" class="btn btn-secondary btn-sm rounded-0" type="button"><i class="fas fa-edit"></i></a>
                <p id="suppliarDelete_btn" class="btn btn-danger btn-sm rounded-0" data-id="' . $row['id'] . '"><i class="fas fa-trash-alt"></i></p>
            </div> :
        ': '',
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
