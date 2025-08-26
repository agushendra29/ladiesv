<?php
require_once '../init.php';

## Read value from DataTables
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$columnName = $_POST['columns'][$columnIndex]['data'];
$columnSortOrder = $_POST['order'][0]['dir'];
$searchValue = $_POST['search']['value'];
$searchArray = [];

## Filter suppliar jika bukan role 1 (HO)
$whereExtra = '';
if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10) {
    $whereExtra = " AND i.suppliar_id = :suppliar_id ";
}

## Search query
$searchQuery = " ";
if ($searchValue != '') {
    $searchQuery = " AND (
        i.invoice_number LIKE :invoice_number OR 
        i.net_total LIKE :net_total OR 
        u2.name LIKE :distributor_name
    ) ";
    $searchArray['invoice_number']   = "%$searchValue%";
    $searchArray['net_total']        = "%$searchValue%";
    $searchArray['distributor_name'] = "%$searchValue%";
}

## Total records without filtering
$sqlTotal = "SELECT COUNT(*) AS allcount FROM invoice i WHERE 1 {$whereExtra}";
$stmt = $pdo->prepare($sqlTotal);
if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10) {
    $stmt->bindValue(':suppliar_id', $_SESSION['distributor_id'], PDO::PARAM_INT);
}
$stmt->execute();
$totalRecords = $stmt->fetch()['allcount'];

## Total records with filtering
$sqlTotalFilter = "
    SELECT COUNT(DISTINCT i.id) AS allcount 
    FROM invoice i
    LEFT JOIN suppliar u2 ON i.suppliar_id = u2.id
    WHERE 1 {$whereExtra} {$searchQuery}
";
$stmt = $pdo->prepare($sqlTotalFilter);
if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10) {
    $stmt->bindValue(':suppliar_id', $_SESSION['distributor_id'], PDO::PARAM_INT);
}
foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':'.$key, $search, PDO::PARAM_STR);
}
$stmt->execute();
$totalRecordwithFilter = $stmt->fetch()['allcount'];

## Fetch records with join
$sqlFetch = "
    SELECT suppliar_id, customer_id, i.*,
        u2.name AS distributor_name,
        GROUP_CONCAT(CONCAT(p.product_name, ' - ', d.quantity) SEPARATOR ', ') AS items_summary
    FROM invoice i
    LEFT JOIN suppliar u2 ON i.suppliar_id = u2.id
    LEFT JOIN invoice_details d ON i.id = d.invoice_no
    LEFT JOIN products p ON d.pid = p.id
    WHERE 1 {$whereExtra} {$searchQuery}
    GROUP BY i.id
    ORDER BY {$columnName} {$columnSortOrder}
    LIMIT :limit OFFSET :offset
";
$stmt = $pdo->prepare($sqlFetch);
if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10) {
    $stmt->bindValue(':suppliar_id', $_SESSION['distributor_id'], PDO::PARAM_INT);
}
foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':'.$key, $search, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', (int)$rowperpage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$row, PDO::PARAM_INT);
$stmt->execute();
$records = $stmt->fetchAll();

## Format data for DataTables
$data = [];

function getSuppliarCode($name) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT suppliar_code FROM suppliar WHERE id = :id LIMIT 1");
    $stmt->bindValue(':id', $name, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['suppliar_code'] : null;
}

foreach ($records as $row) {
    if (empty($row['items_summary']) || $row['net_total'] == 0) {
        continue;
    }
    $data[] = [
        "invoice_number"   => $row['invoice_number'],
        "customer_name"    => $row['customer_name'] . ' - ' . getSuppliarCode($row['customer_id']),
        "distributor_name" => $row['distributor_name'] . ' - ' . getSuppliarCode($row['suppliar_id']),
        "net_total"        => $row['customer_name'] == "Penjualan Pribadi" ? "-" : 'Rp ' . number_format($row['net_total'], 0, ',', '.'),
        "order_date"       => $row['order_date'],
        "items_summary"    => $row['items_summary'],
    ];
}

## JSON Response
$response = [
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
