<?php
require_once '../init.php';
header('Content-Type: application/json');

try {
    // === DataTables Request ===
    $draw            = intval($_POST['draw'] ?? 1);
    $row             = intval($_POST['start'] ?? 0);
    $rowperpage      = intval($_POST['length'] ?? 10);
    $columnIndex     = intval($_POST['order'][0]['column'] ?? 0);
    $columnNamePost  = $_POST['columns'][$columnIndex]['data'] ?? 'id';
    $columnSortOrder = strtoupper($_POST['order'][0]['dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
    $searchValue     = trim($_POST['search']['value'] ?? '');

    // === Filter tambahan dari user ===
    $startDate  = trim($_POST['start_date'] ?? '');
    $endDate    = trim($_POST['end_date']   ?? '');
    $roleFilter = trim($_POST['role']       ?? '');

    // --- Build where parts & params ---
    $whereBaseParts = [];
    $whereSearchParts = [];
    $params = [];

    // Batasan role (bukan HO & bukan SuperAdmin)
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

    // Compose WHERE clauses
    $whereBase = 'WHERE 1';
    if (count($whereBaseParts) > 0) $whereBase .= ' AND ' . implode(' AND ', $whereBaseParts);

    $whereFilter = $whereBase;
    if (count($whereSearchParts) > 0) $whereFilter .= ' AND ' . implode(' AND ', $whereSearchParts);

    // === Total Records tanpa filter ===
    $sqlTotal = "SELECT COUNT(*) FROM invoice i LEFT JOIN suppliar u2 ON i.suppliar_id = u2.id $whereBase";
    $stmt = $pdo->prepare($sqlTotal);
    // bind params only present in $whereBase
    foreach ($params as $k => $v) {
        // skip search-only params for total (we included search only in $whereFilter)
        if (strpos($k, ':invoice_number') === 0 || strpos($k, ':net_total') === 0 || strpos($k, ':distributor_name') === 0 || strpos($k, ':customer_name') === 0) {
            continue;
        }
        $type = is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($k, $v, $type);
    }
    $stmt->execute();
    $totalRecords = (int)$stmt->fetchColumn();

    // === Total Records dengan filter & search ===
    $sqlTotalFilter = "SELECT COUNT(*) FROM invoice i LEFT JOIN suppliar u2 ON i.suppliar_id = u2.id $whereFilter";
    $stmt = $pdo->prepare($sqlTotalFilter);
    foreach ($params as $k => $v) {
        $type = is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($k, $v, $type);
    }
    $stmt->execute();
    $totalRecordwithFilter = (int)$stmt->fetchColumn();

    // === Items subquery to avoid GROUP BY issues ===
    $itemsSubquery = "
        SELECT d.invoice_no,
               GROUP_CONCAT(CONCAT(p.product_name, ' - ', d.quantity) SEPARATOR '||') AS items_summary
        FROM invoice_details d
        JOIN products p ON d.pid = p.id
        GROUP BY d.invoice_no
    ";

    // === Whitelist untuk ORDER BY (prevent SQL injection) ===
    $allowedColumns = [
        'invoice_number'   => 'i.invoice_number',
        'customer_name'    => 'i.customer_name',
        'distributor_name' => 'u2.name',
        'net_total'        => 'i.net_total',
        'order_date'       => 'i.order_date',
        'id'               => 'i.id'
    ];
    // If DataTables sends nested props like "0" or others, map to default
    $columnName = $allowedColumns[$columnNamePost] ?? 'i.id';
    $orderBy = $columnName . ' ' . $columnSortOrder;

    // === Fetch Data ===
    $sqlFetch = "
        SELECT i.*,
               u2.name AS distributor_name,
               u2.role_id AS distributor_role,
               items.items_summary
        FROM invoice i
        LEFT JOIN suppliar u2 ON i.suppliar_id = u2.id
        LEFT JOIN ({$itemsSubquery}) items ON i.id = items.invoice_no
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

    // === Helper untuk kode suppliar ===
    function getSuppliarCode($id) {
        if (!$id) return '';
        global $pdo;
        $stmt = $pdo->prepare("SELECT suppliar_code FROM suppliar WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() ?: '';
    }

    // === Format Data untuk DataTables ===
    $data = [];
    foreach ($records as $r) {
        $itemsSummary = $r['items_summary'] ? str_replace('||', '<br>', $r['items_summary']) : '';
        $distName = ($r['distributor_role'] == 1 || $r['distributor_role'] == 10)
            ? 'Head Office'
            : ($r['distributor_name'] . ' - ' . getSuppliarCode($r['suppliar_id']));

        $customerCode = ($r['customer_id'] > 0) ? getSuppliarCode($r['customer_id']) : '';
        $netTotalLabel = ($r['customer_name'] === "Penjualan Pribadi") ? '-' : 'Rp '. number_format($r['net_total'],0,',','.');

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
    // Untuk debugging bisa log error di server; untuk client kirim pesan sederhana
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $ex->getMessage()]);
    exit;
}
