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

$isAdmin = $_SESSION['role_id'] == '1' || $_SESSION['role_id'] == '10'; 
$customerFilter = $isAdmin ? ""  : "AND po.suppliar_id = ".$_SESSION['distributor_id']."";

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
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT po.po_id) AS allcount 
    FROM purchase_orders po
    LEFT JOIN suppliar u ON po.suppliar_id = u.id
    WHERE 1 $customerFilter");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

// Total number of records with filtering
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT po.po_id) AS allcount 
    FROM purchase_orders po
    LEFT JOIN suppliar u ON po.suppliar_id = u.id
    WHERE 1 ".$searchQuery." $customerFilter");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

// Fetch records (group by po_id)
$stmt = $pdo->prepare("SELECT 
    po.po_id,
    po.invoice_number,
    po.status,
    po.created_at,
    po.approved_at,
    u.name AS suppliar_name,
    SUM(po.total_amount) AS total_amount,
    GROUP_CONCAT(CONCAT(p.product_name, ' - Qty: ', po.quantity) SEPARATOR ', ') AS items_summary
FROM purchase_orders po
LEFT JOIN suppliar u ON po.suppliar_id = u.id
LEFT JOIN products p ON po.product_id = p.id
WHERE 1 ".$searchQuery." $customerFilter
GROUP BY po.po_id
ORDER BY $orderBy
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

    $actionButtons = '';
    if ($isAdmin && strtolower($row['status']) == "pending") {
        $actionButtons = '
            <div style="display:flex; gap:8px;">
                <button 
                    type="button" 
                    class="btn-open-form" 
                    data-id="'.$row['po_id'].'" 
                    data-invoice="'.$row['invoice_number'].'"
                    data-object=\''.json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT).'\'
                    style="
                        background-color:#007bff;
                        color:#fff;
                        border:none;
                        padding:6px 14px;
                        border-radius:6px;
                        font-size:14px;
                        cursor:pointer;
                        transition:background 0.3s ease;
                    "
                    onmouseover="this.style.backgroundColor=\'#0069d9\'" 
                    onmouseout="this.style.backgroundColor=\'#007bff\'"
                >
                    Proses
                </button>

                <button 
                    type="button" 
                    class="btn-reject" 
                    data-id="'.$row['po_id'].'"
                    style="
                        background-color:#dc3545;
                        color:#fff;
                        border:none;
                        padding:6px 14px;
                        border-radius:6px;
                        font-size:14px;
                        cursor:pointer;
                        transition:background 0.3s ease;
                    "
                    onmouseover="this.style.backgroundColor=\'#c82333\'" 
                    onmouseout="this.style.backgroundColor=\'#dc3545\'"
                >
                    Tolak
                </button>
            </div>
        ';
    }

    $rowData = array(
        "id" => $row['invoice_number'] !=="-"
    ? '
        <a href="app/invoice/nota_pembelian.php?id='.$row['invoice_number'].'" 
           class="btn-invoice"
           title="Download Invoice">
           <i class="fas fa-file-pdf"></i> '.$row['invoice_number'].'
        </a>
      '
    : '<span style="color:#6c757d; font-size:13px;">-</span>',
        "suppliar_id" => $row['suppliar_name'],
        "total_amount" => 'Rp ' . number_format($row['total_amount'], 0, ',', '.'),
        "status" => strtoupper($row['status']),
        "items_summary" => $row['items_summary'],
        "created_at" => $row['created_at'],
        "approved_at" => $row['approved_at'],
        "action" => $actionButtons
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
