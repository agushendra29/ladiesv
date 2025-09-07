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
if ($role_id > 1 && $role_id != 10) {
    $filterDistributorQuery = " AND ds.suppliar_id = :distributor_id ";
    $filterParams['distributor_id'] = $distributor_id;
}

$searchQuery = "";

// 🔹 Handle search
if ($searchValue != '') {
    $searchQuery = " AND (
        ds.product_name LIKE :search OR
        s.name LIKE :search OR
        s.suppliar_code LIKE :search OR
        CAST(ds.product_id AS CHAR) LIKE :search OR
        CAST(ds.stock AS CHAR) LIKE :search
    ) ";
    $searchArray = array('search' => "%$searchValue%");
} else {
    // Kalau superadmin (role 1 atau 10) dan search kosong, tampilkan hanya HO (suppliar_id=1)
    if ($role_id == 1 || $role_id == 10) {
        $filterDistributorQuery .= " AND ds.suppliar_id = 1 ";
    }
}

## Total records tanpa filter
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM distributor_stocks ds 
    LEFT JOIN suppliar s ON ds.suppliar_id = s.id
    WHERE 1 " . $filterDistributorQuery);
$stmt->execute($filterParams);
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total records dengan filter
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM distributor_stocks ds 
    LEFT JOIN suppliar s ON ds.suppliar_id = s.id
    WHERE 1 " . $filterDistributorQuery . $searchQuery);
$stmt->execute(array_merge($filterParams, $searchArray));
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Ambil data dengan limit, order, filter
$stmt = $pdo->prepare("
    SELECT ds.id, ds.suppliar_id, ds.product_id, ds.product_name, ds.stock,
           s.name AS suppliar_name, s.suppliar_code
    FROM distributor_stocks ds
    LEFT JOIN suppliar s ON ds.suppliar_id = s.id
    WHERE 1 " . $filterDistributorQuery . $searchQuery . "
    ORDER BY " . $columnName . " " . $columnSortOrder . "
    LIMIT :limit OFFSET :offset
");

// Bind filter params
foreach ($filterParams as $key => $val) {
    $stmt->bindValue(':' . $key, $val);
}

// Bind search params
foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}

// Bind pagination
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
        "suppliar_name" => $row['suppliar_name'] . " - " . $row['suppliar_code'],
        "stock" => $row['stock'],
        "action" => $_SESSION['role_id'] == 10 ? '
            <div class="d-flex align-items-center" style="gap: 6px; max-width: 200px;">
                <input type="number" min="0" value="0" class="form-control form-control-sm text-center stock-input-m" style="width: 60px;" data-id="' . $row['id'] . '">
                <button type="button" id="stock-apply-add" class="btn btn-success btn-sm stock-apply-add-m" data-id="' . $row['id'] . '"><i class="fas fa-plus"></i></button>
                <button type="button" id="stock-apply-reduce" class="btn btn-danger btn-sm stock-apply-reduce-m" data-id="' . $row['id'] . '"><i class="fas fa-minus"></i></button>
            </div>
        ' : "",
        "stock_id" => $row['id'],
        "role_id" => $_SESSION['role_id']
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
