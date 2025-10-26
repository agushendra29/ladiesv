<?php
require_once '../init.php';
header('Content-Type: application/json');

try {
    // === DataTables Request & Filter Logic ===
    $draw              = intval($_POST['draw'] ?? 1);
    $row               = intval($_POST['start'] ?? 0);
    $rowperpage        = intval($_POST['length'] ?? 10);
    $columnIndex       = intval($_POST['order'][0]['column'] ?? 0);
    $columnNamePost    = $_POST['columns'][$columnIndex]['data'] ?? 'id';
    $columnSortOrder   = strtoupper($_POST['order'][0]['dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
    $searchValue       = trim($_POST['search']['value'] ?? '');

    // === Filter tambahan ===
    $startDate  = trim($_POST['start_date'] ?? '');
    $endDate    = trim($_POST['end_date']   ?? '');
    $roleFilter = trim($_POST['role']       ?? '');

    // --- Build where parts ---
    $whereBaseParts = [];
    $whereSearchParts = [];
    $params = [];

    // Batasan role
    if (!in_array($_SESSION['role_id'] ?? null, [1,10], true)) {
        $whereBaseParts[] = "i.suppliar_id = :suppliar_id";
        $params[':suppliar_id'] = intval($_SESSION['distributor_id'] ?? 0);
    }

    // Filter tanggal
    if ($startDate !== '' && $endDate !== '') {
        $whereBaseParts[] = "DATE(i.order_date) BETWEEN :start_date AND :end_date";
        $params[':start_date'] = $startDate;
        $params[':end_date']   = $endDate;
    } elseif ($startDate !== '') {
        $whereBaseParts[] = "DATE(i.order_date) >= :start_date";
        $params[':start_date'] = $startDate;
    } elseif ($endDate !== '') {
        $whereBaseParts[] = "DATE(i.order_date) <= :end_date";
        $params[':end_date'] = $endDate;
    }

    // Filter role
    if ($roleFilter !== '') {
        if ((int)$roleFilter === 1) {
            $whereBaseParts[] = "(u2.role_id = 1 OR u2.role_id = 10)";
        } else {
            $whereBaseParts[] = "u2.role_id = :roleFilter";
            $params[':roleFilter'] = (int)$roleFilter;
        }
    }

    // Search
    if ($searchValue !== '') {
        $lowerSearch = strtolower($searchValue);
        if (str_contains($lowerSearch, 'head')) {
            $whereSearchParts[] = "(i.customer_id = 1 OR i.suppliar_id = 1)";
        } else {
            $whereSearchParts[] = "(i.invoice_number LIKE :invoice_number OR i.net_total LIKE :net_total OR u2.name LIKE :distributor_name OR i.customer_name LIKE :customer_name)";
            $params[':invoice_number'] = "%$searchValue%";
            $params[':net_total'] = "%$searchValue%";
            $params[':distributor_name'] = "%$searchValue%";
            $params[':customer_name'] = "%$searchValue%";
        }
    }

    // === Base WHERE ===
    $whereBase = 'WHERE 1';
    if (count($whereBaseParts) > 0) $whereBase .= ' AND ' . implode(' AND ', $whereBaseParts);
    $whereFilter = $whereBase;
    if (count($whereSearchParts) > 0) $whereFilter .= ' AND (' . implode(' OR ', $whereSearchParts) . ')';

    // === Hanya tampilkan invoice dengan transaksi non-refund ===
    $existsCondition = "
        EXISTS (
            SELECT 1 FROM transaction_histories th
            WHERE th.invoice_number = i.invoice_number
            AND th.is_refund = 0
            AND th.type = 'penjualan'
        )
    ";

    $whereBase   .= " AND $existsCondition";
    $whereFilter .= " AND $existsCondition";

    // === Total Records tanpa filter ===
    $sqlTotal = "SELECT COUNT(*) FROM invoice i 
                 LEFT JOIN suppliar u2 ON i.suppliar_id = u2.id 
                 $whereBase";
    $stmt = $pdo->prepare($sqlTotal);
    foreach ($params as $k => $v) {
        if (str_contains($k, ':invoice_number') || str_contains($k, ':net_total') || str_contains($k, ':distributor_name') || str_contains($k, ':customer_name')) continue;
        $type = is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($k, $v, $type);
    }
    $stmt->execute();
    $totalRecords = (int)$stmt->fetchColumn();

    // === Total Records dengan filter & search ===
    $sqlTotalFilter = "SELECT COUNT(*) FROM invoice i 
                       LEFT JOIN suppliar u2 ON i.suppliar_id = u2.id 
                       $whereFilter";
    $stmt = $pdo->prepare($sqlTotalFilter);
    foreach ($params as $k => $v) {
        $type = is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($k, $v, $type);
    }
    $stmt->execute();
    $totalRecordwithFilter = (int)$stmt->fetchColumn();

    // === Subquery items (non-refund only) ===
    $itemsSubquery = "
        SELECT th.invoice_number AS invoice_num,
               CAST(
                   GROUP_CONCAT(
                       CONCAT(p.product_name, ' - ', th.quantity_total) 
                       SEPARATOR '||'
                   ) AS CHAR(10000)
               ) AS items_summary
        FROM (
            SELECT invoice_number, product_id, SUM(ABS(quantity)) AS quantity_total
            FROM transaction_histories 
            WHERE type = 'penjualan' AND is_refund = 0
            GROUP BY invoice_number, product_id
        ) AS th
        JOIN products p ON th.product_id = p.id
        GROUP BY th.invoice_number
    ";

    // === ORDER BY whitelist ===
    $allowedColumns = [
        'invoice_number'   => 'i.invoice_number',
        'customer_name'    => 'i.customer_name',
        'distributor_name' => 'u2.name',
        'net_total'        => 'i.net_total',
        'order_date'       => 'i.order_date',
        'id'               => 'i.id'
    ];
    $columnName = $allowedColumns[$columnNamePost] ?? 'i.id';
    $orderBy = $columnName . ' ' . $columnSortOrder;

    // === Fetch data ===
    $sqlFetch = "
        SELECT i.*,
               u2.name AS distributor_name,
               u2.role_id AS distributor_role,
               items.items_summary
        FROM invoice i
        LEFT JOIN suppliar u2 ON i.suppliar_id = u2.id
        LEFT JOIN ({$itemsSubquery}) items ON i.invoice_number = items.invoice_num
        $whereFilter
        ORDER BY {$orderBy}
        LIMIT :limit OFFSET :offset
    ";
    $stmt = $pdo->prepare($sqlFetch);
    foreach ($params as $k => $v) {
        $type = is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($k, $v, $type);
    }
    $stmt->bindValue(':limit',  $rowperpage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $row,        PDO::PARAM_INT);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // === Helper kode suppliar ===
    function getSuppliarCode($id) {
        if (!$id) return '';
        global $pdo;
        static $stmt = null;
        if ($stmt === null) {
            $stmt = $pdo->prepare("SELECT suppliar_code FROM suppliar WHERE id = :id LIMIT 1");
        }
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() ?: '';
    }

    // === Format Data ===
    $data = [];
    foreach ($records as $r) {
        $itemsSummary = $r['items_summary'] ? str_replace('||', '<br>', $r['items_summary']) : '';
        $distName = ($r['distributor_role'] == 1 || $r['distributor_role'] == 10)
            ? 'Head Office'
            : ($r['distributor_name'] . ' - ' . getSuppliarCode($r['suppliar_id']));
        $customerCode = ($r['customer_id'] > 0) ? getSuppliarCode($r['customer_id']) : '';
        $netTotalLabel = ($r['customer_id'] == 0) ? '-' : 'Rp ' . number_format($r['net_total'], 0, ',', '.');

        $data[] = [
            'invoice_number'   => '<a href="app/invoice/po_pdf.php?id=' . htmlspecialchars($r['invoice_number']) . '" class="btn-invoice" download>
                                       <i class="fas fa-file-pdf"></i> ' . htmlspecialchars($r['invoice_number']) . '</a>',
            'customer_name'    => htmlspecialchars($r['customer_name']) . ($customerCode ? ' - ' . htmlspecialchars($customerCode) : ''),
            'distributor_name' => htmlspecialchars($distName),
            'net_total'        => $netTotalLabel,
            'order_date'       => $r['order_date'],
            'items_summary'    => $itemsSummary
        ];
    }

    // === JSON Response ===
    echo json_encode([
        'draw' => $draw,
        'iTotalRecords' => $totalRecords,
        'iTotalDisplayRecords' => $totalRecordwithFilter,
        'aaData' => $data
    ]);
    exit;

} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => 'Query error: ' . $ex->getMessage()]);
    exit;
}
