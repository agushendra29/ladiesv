<?php
require_once '../init.php';

$role_id = $_SESSION['role_id'];
$distributor_id = $_SESSION['distributor_id']; // id distributor user yg login

## Read value from POST (untuk DataTables)
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$searchArray = array();
$filterDistributorQuery = '';
$filterParams = [];

## Filter berdasarkan role (kalau bukan admin, batasi distributor)
if ($role_id > 1) {
    $filterDistributorQuery = " AND suppliar_id = :distributor_id ";
    $filterParams['distributor_id'] = $distributor_id;
}

## Search 
$searchQuery = " ";
if ($searchValue != '') {
    $searchQuery = " AND (product_id LIKE :product_id OR 
        suppliar_id LIKE :suppliar_id OR 
        stock LIKE :stock ) ";
    $searchArray = array(
        'product_id' => "%$searchValue%",
        'suppliar_id' => "%$searchValue%",
        'stock' => "%$searchValue%"
    );
}

## Total records without filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM distributor_stocks WHERE 1 " . $filterDistributorQuery);
$stmt->execute($filterParams);
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total records with filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM distributor_stocks WHERE 1 " . $filterDistributorQuery . $searchQuery);
$stmt->execute(array_merge($filterParams, $searchArray));
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records with limit, order, filtering
$stmt = $pdo->prepare("SELECT id, suppliar_id, suppliar_name, product_name, product_id, stock
    FROM distributor_stocks 
    WHERE 1 " . $filterDistributorQuery . $searchQuery . " 
    ORDER BY " . $columnName . " " . $columnSortOrder . " 
    LIMIT :limit OFFSET :offset");

// Bind filter params
foreach ($filterParams as $key => $val) {
    $stmt->bindValue(':' . $key, $val);
}

// Bind search params
foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}

// Bind pagination params
$stmt->bindValue(':limit', (int)$rowperpage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$row, PDO::PARAM_INT);

$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();
$counter = $row + 1;
foreach ($empRecords as $row) {
    $data[] = array(
        "id" => $counter++,
        "product_id" => $row['product_id'],
        "product_name" => $row['product_name'],
        "suppliar_id" => $row['suppliar_id'],
        "suppliar_name" => $row['suppliar_name'],
        "stock" => $row['stock'],
        "action" => $_SESSION['role_id'] == 10 ? '
            <div class="d-flex align-items-center" style="gap: 6px; max-width: 200px;">
                <input type="number" min="0" value="0" class="form-control form-control-sm text-center stock-input" style="width: 60px;" data-id="' . $row['id'] . '">
                <button type="button" id="stock-apply-add" class="btn btn-success btn-sm stock-apply-add" data-id="' . $row['id'] . '"><i class="fas fa-plus"></i></button>
                <button type="button" id="stock-apply-reduce" class="btn btn-danger btn-sm stock-apply-reduce" data-id="' . $row['id'] . '"><i class="fas fa-minus"></i></button>
            </div>
        ' : "",
    );
}

## Response untuk DataTables
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
