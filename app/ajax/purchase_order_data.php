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
   $searchQuery = " AND (distributor_id LIKE :distributor_id or 
        total_amount LIKE :total_amount OR 
        created_at LIKE :created_at OR 
        approved_at LIKE :approved_at ) ";
   $searchArray = array( 
        'distributor_id'=>"%$searchValue%", 
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
$stmt = $pdo->prepare(" SELECT 
        po.*,
        GROUP_CONCAT(CONCAT(p.product_name, ' - Qty: ',poi.quantity, ')') SEPARATOR ', ') AS items_summary
    FROM purchase_orders po
    LEFT JOIN purchase_order_items poi ON po.id = poi.purchase_order_id
    LEFT JOIN products p ON poi.product_id = p.id WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $rowData = array(
        "id" => $row['id'],
        "distributor_id" => $row['distributor_id'],
        "total_amount" => $row['total_amount'],
        "status" => $row['status'],
        "items_summary" => $row['items_summary'],
        "created_at" => $row['created_at'],
        "approved_at" => $row['approved_at'], // ✅ use actual data
    );

    if ($isAdmin) {
        $rowData["action"] = '
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-success btn-sm btn-approve" data-id="'.$row['id'].'"><i class="fas fa-check"></i> Approve</button>
                <button type="button" class="btn btn-danger btn-sm ml-2 btn-reject" data-id="'.$row['id'].'"><i class="fas fa-times"></i> Reject</button>
            </div>
        ';
    }

    $data[] = $rowData; // ✅ Only once
}

## Response
$response = array(
   "draw" => intval($draw),
   "iTotalRecords" => $totalRecords,
   "iTotalDisplayRecords" => $totalRecordwithFilter,
   "aaData" => $data
);

echo json_encode($response);