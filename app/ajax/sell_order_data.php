<?php
require_once '../init.php';

/* === DataTables Request === */
$draw            = $_POST['draw'] ?? 1;
$row             = $_POST['start'] ?? 0;
$rowperpage      = $_POST['length'] ?? 10;
$columnIndex     = $_POST['order'][0]['column'] ?? 0;
$columnName      = $_POST['columns'][$columnIndex]['data'] ?? 'id';
$columnSortOrder = $_POST['order'][0]['dir'] ?? 'desc';
$searchValue     = $_POST['search']['value'] ?? '';

/* === Filter tambahan dari user === */
$startDate = $_POST['start_date'] ?? '';
$endDate   = $_POST['end_date']   ?? '';

$searchArray = [];
$whereExtra  = '';
$whereDate   = '';   // filter tanggal

/* --- Batasan role (bukan HO dan bukan SuperAdmin) --- */
if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10) {
    $whereExtra = " AND i.suppliar_id = :suppliar_id ";
}

/* --- Filter Tanggal --- */
if ($startDate !== '' && $endDate !== '') {
    $whereDate = " AND DATE(i.order_date) BETWEEN :start_date AND :end_date ";
} elseif ($startDate !== '') {
    $whereDate = " AND DATE(i.order_date) >= :start_date ";
} elseif ($endDate !== '') {
    $whereDate = " AND DATE(i.order_date) <= :end_date ";
}

/* --- Search --- */
$searchQuery = '';
if ($searchValue !== '') {
    $lowerSearch = strtolower($searchValue);
    if (str_contains($lowerSearch, 'head')) {
        $searchQuery = " AND (i.customer_id = 1 OR i.suppliar_id = 1) ";
    } else {
        $searchQuery = " AND (
            i.invoice_number LIKE :invoice_number OR 
            i.net_total LIKE :net_total OR 
            u2.name LIKE :distributor_name
        ) ";
        $searchArray['invoice_number']   = "%$searchValue%";
        $searchArray['net_total']        = "%$searchValue%";
        $searchArray['distributor_name'] = "%$searchValue%";
    }
}

/* === Total Records tanpa filter === */
$sqlTotal = "SELECT COUNT(*) AS allcount FROM invoice i WHERE 1 {$whereExtra} {$whereDate}";
$stmt = $pdo->prepare($sqlTotal);
if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10) {
    $stmt->bindValue(':suppliar_id', $_SESSION['distributor_id'], PDO::PARAM_INT);
}
if ($startDate !== '') $stmt->bindValue(':start_date', $startDate);
if ($endDate   !== '') $stmt->bindValue(':end_date',   $endDate);
$stmt->execute();
$totalRecords = $stmt->fetchColumn();

/* === Total Records dengan filter & search === */
$sqlTotalFilter = "
    SELECT COUNT(DISTINCT i.id) AS allcount
    FROM invoice i
    LEFT JOIN suppliar u2 ON i.suppliar_id = u2.id
    WHERE 1 {$whereExtra} {$whereDate} {$searchQuery}";
$stmt = $pdo->prepare($sqlTotalFilter);
if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10) {
    $stmt->bindValue(':suppliar_id', $_SESSION['distributor_id'], PDO::PARAM_INT);
}
if ($startDate !== '') $stmt->bindValue(':start_date', $startDate);
if ($endDate   !== '') $stmt->bindValue(':end_date',   $endDate);
foreach ($searchArray as $k => $v) $stmt->bindValue(":$k", $v);
$stmt->execute();
$totalRecordwithFilter = $stmt->fetchColumn();

/* === Fetch Data === */
$sqlFetch = "
    SELECT i.*, i.suppliar_id, i.customer_id,
           u2.name AS distributor_name,
           u2.role_id AS distributor_role,
           GROUP_CONCAT(CONCAT(p.product_name, ' - ', d.quantity) SEPARATOR '||') AS items_summary
    FROM invoice i
    LEFT JOIN suppliar u2 ON i.suppliar_id = u2.id
    LEFT JOIN invoice_details d ON i.id = d.invoice_no
    LEFT JOIN products p ON d.pid = p.id
    WHERE 1 {$whereExtra} {$whereDate} {$searchQuery}
    GROUP BY i.id
    ORDER BY {$columnName} {$columnSortOrder}
    LIMIT :limit OFFSET :offset 
    ORDER BY DESC
    ";
$stmt = $pdo->prepare($sqlFetch);
if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10) {
    $stmt->bindValue(':suppliar_id', $_SESSION['distributor_id'], PDO::PARAM_INT);
}
if ($startDate !== '') $stmt->bindValue(':start_date', $startDate);
if ($endDate   !== '') $stmt->bindValue(':end_date',   $endDate);
foreach ($searchArray as $k => $v) $stmt->bindValue(":$k", $v);
$stmt->bindValue(':limit',  (int)$rowperpage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$row,        PDO::PARAM_INT);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* === Helper untuk kode suppliar === */
function getSuppliarCode($id) {
    if ($id <= 0) return ''; // aman bila id 0 atau negatif
    global $pdo;
    $stmt = $pdo->prepare("SELECT suppliar_code FROM suppliar WHERE id = :id LIMIT 1");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn() ?: '';
}

/* === Format Data untuk DataTables === */
$data = [];
foreach ($records as $r) {
    // hanya lewati bila tidak ada detail item
    if (empty($r['items_summary'])) continue;

    $itemsSummary = str_replace('||', '<br>', $r['items_summary']);
    $distName = ($r['distributor_role']==1 || $r['distributor_role']==10)
        ? 'Head Office'
        : $r['distributor_name'].' - '.getSuppliarCode($r['suppliar_id']);

    $customerCode = $r['customer_id'] > 0 ? getSuppliarCode($r['customer_id']) : '';

    $data[] = [
        'invoice_number' => '<a href="app/invoice/po_pdf.php?id='.$r['id'].'" class="btn-invoice" download>
                               <i class="fas fa-file-pdf"></i> '.$r['invoice_number'].'</a>',
        'customer_name'  => $r['customer_name'] . ($customerCode ? ' - '.$customerCode : ''),
        'distributor_name'=> $distName,
        'net_total'      => $r['customer_name']=="Penjualan Pribadi" ? '-' : 'Rp '.number_format($r['net_total'],0,',','.'),
        'order_date'     => $r['order_date'],
        'items_summary'  => $itemsSummary
    ];
}

/* === JSON Response === */
echo json_encode([
    'draw' => intval($draw),
    'iTotalRecords' => $totalRecords,
    'iTotalDisplayRecords' => $totalRecordwithFilter,
    'aaData' => $data
]);
