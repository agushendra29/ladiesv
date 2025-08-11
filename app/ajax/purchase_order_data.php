<?php
require_once '../init.php';

## Read value
$draw = $_POST['draw'];
$start = $_POST['start']; // offset
$length = $_POST['length']; // limit
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$isAdmin = $_SESSION['role_id'] == '1'; 
$customer_id = $isAdmin ? ""  : "AND suppliar_id = ".$_SESSION['distributor_id']."";
$searchArray = array();
$orderBy = "FIELD(LOWER(po.status), 'pending', 'approved', 'rejected'), po.created_at DESC";
## Search 
$searchQuery = "";
if($searchValue != ''){
   $searchQuery = " AND (
        u.name LIKE :search OR 
        po.total_amount LIKE :search OR 
        po.created_at LIKE :search OR 
        po.approved_at LIKE :search OR
        po.status LIKE :search
    ) ";
   $searchArray = array( 
        'search'=>"%$searchValue%"
   );
}

// Total number of records without filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount 
    FROM purchase_orders po
    LEFT JOIN suppliar u ON po.suppliar_id = u.id
    WHERE 1");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

// Total number of records with filtering
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT po.id) AS allcount 
    FROM purchase_orders po
    LEFT JOIN suppliar u ON po.suppliar_id = u.id
    WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

// Fetch records
$stmt = $pdo->prepare("SELECT 
    po.*,
    u.name AS suppliar_name,
    GROUP_CONCAT(CONCAT(p.product_name, ' - Qty: ', po.quantity) SEPARATOR ', ') AS items_summary
FROM purchase_orders po
LEFT JOIN suppliar u ON po.suppliar_id = u.id
LEFT JOIN products p ON po.product_id = p.id
WHERE 1 ".$searchQuery." ".$customer_id."
GROUP BY po.id
ORDER BY ".$orderBy."
LIMIT :offset, :limit");

// Bind search values
foreach($searchArray as $key => $search){
   $stmt->bindValue(':'.$key, $search, PDO::PARAM_STR);
}

// Bind limit & offset
$stmt->bindValue(':offset', (int)$start, PDO::PARAM_INT);
$stmt->bindValue(':limit', (int)$length, PDO::PARAM_INT);

$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

foreach ($empRecords as $row) {
    // Skip jika tidak ada item atau total_amount = 0
    if (empty($row['items_summary']) || $row['total_amount'] == 0) {
        continue;
    }

    $rowData = array(
        "id" => $row['invoice_number'],
        "suppliar_id" => $row['suppliar_name'],
        "total_amount" => 'Rp ' . number_format($row['total_amount'], 0, ',', '.'),
        "status" => strtoupper($row['status']),
        "items_summary" => $row['items_summary'],
        "created_at" => $row['created_at'],
        "approved_at" => $row['approved_at'],
        "action" => $isAdmin && strtolower($row['status']) == "pending"  ? '
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-success btn-sm btn-approve" data-id="'.$row['id'].'"><i class="fas fa-check"></i> Setuju</button>
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
echo json_encode($response);
