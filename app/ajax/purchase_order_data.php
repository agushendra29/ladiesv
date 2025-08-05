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
$isAdmin = $_SESSION['role_id'] == '1'; 
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " AND (
        u.name LIKE :suppliar_name OR 
        po.total_amount LIKE :total_amount OR 
        po.created_at LIKE :created_at OR 
        po.approved_at LIKE :approved_at
    ) ";
   $searchArray = array( 
        'suppliar_name'=>"%$searchValue%", 
        'total_amount'=>"%$searchValue%",
        'created_at'=>"%$searchValue%",
        'approved_at'=>"%$searchValue%"
   );
}
## Total number of records without filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM purchase_orders ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM purchase_orders WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $pdo->prepare("SELECT 
    po.*,
    u.name AS suppliar_name,
    GROUP_CONCAT(CONCAT(p.product_name, ' - Qty: ', quantity) SEPARATOR ', ') AS items_summary
FROM purchase_orders po
LEFT JOIN products p ON po.product_id = p.id
LEFT JOIN suppliar u ON po.suppliar_id = u.id
WHERE 1 ".$searchQuery."
GROUP BY po.id
ORDER BY ".$columnName." ".$columnSortOrder."
LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
   $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

foreach ($empRecords as $row) {
    // Skip jika tidak ada item atau total_amount = 0
    if (empty($row['items_summary']) || $row['total_amount'] == 0) {
        continue;
    }

    $rowData = array(
        "id" => $row['id'],
        "suppliar_id" => $row['suppliar_name'],
        "total_amount" => 'Rp ' . number_format($row['total_amount'], 0, ',', '.'),
        "status" => $row['status'],
        "items_summary" => $row['items_summary'],
        "created_at" => $row['created_at'],
        "approved_at" => $row['approved_at'],
        "action" => $isAdmin && $row['status'] == "pending" ? '
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-success btn-sm btn-approve" data-id="'.$row['id'].'"><i class="fas fa-check"></i>Setuju</button>
                <button type="button" class="btn btn-danger btn-sm ml-2 btn-reject" data-id="'.$row['id'].'"><i class="fas fa-times"></i> Tolak</button>
            </div>' : ''
    );

    $data[] = $rowData;
}


## Response
$response = array(
   "draw" => intval($draw),
   "iTotalRecords" => $totalRecords,
   "iTotalDisplayRecords" => $totalRecordwithFilter,
   "aaData" => $data
);



header('Content-Type: application/json');

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "error" => "JSON encode error: " . json_last_error_msg(),
        "raw_data" => $response
    ]);
    exit;
}
echo json_encode($response);