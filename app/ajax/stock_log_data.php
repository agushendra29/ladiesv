<?php
require_once '../init.php'; // koneksi

header('Content-Type: application/json');

// Ambil parameter dari DataTables
$draw   = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$start  = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

function getSuppliarName($pdo, $id) {
    if (empty($id)) return null;
    $stmt = $pdo->prepare("SELECT suppliar_code FROM user WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $stm = $pdo->prepare("SELECT name FROM suppliar WHERE suppliar_code = :suppliar_code LIMIT 1");
    $stm->execute([':suppliar_code' => $row['suppliar_code']]);
    $rowNew = $stm->fetch(PDO::FETCH_ASSOC);
    return $rowNew ? $rowNew['name'] : null;
}

// Base query   
$baseQuery = "
    FROM stock_logs sl
    LEFT JOIN suppliar s ON sl.suppliar_id = s.id
    LEFT JOIN products p ON sl.product_id = p.id
    LEFT JOIN suppliar cb ON sl.changed_by = cb.id
";

// Query untuk total semua data
$stmt = $pdo->prepare("SELECT COUNT(*) AS total " . $baseQuery);
$stmt->execute();
$totalRecords = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Query untuk pencarian
$where = " WHERE 1=1 ";  // biar gampang tambah kondisi
$params = [];

if (!empty($searchValue)) {
    $where .= " AND (
        s.name LIKE :search 
        OR p.product_name LIKE :search
        OR cb.name LIKE :search
        OR sl.action_type LIKE :search
        OR sl.note LIKE :search
    ) ";
    $params[':search'] = "%$searchValue%";
} else {
    // Kalau search kosong → filter suppliar_id = 1
    $where .= " AND sl.suppliar_id = :suppliar_id ";
    $params[':suppliar_id'] = 1;
}
// Query untuk total setelah filter
$stmt = $pdo->prepare("SELECT COUNT(*) AS total " . $baseQuery . $where);
$stmt->execute($params);
$totalFiltered = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Query untuk ambil data dengan pagination
$sql = "
    SELECT 
        changed_by,
        sl.*, 
        s.name AS suppliar_name, 
        p.product_name, 
        cb.name AS changed_by_name
    $baseQuery
    $where
    ORDER BY sl.created_at DESC
    LIMIT :start, :length
";
$stmt = $pdo->prepare($sql);

// Bind parameter search
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val, PDO::PARAM_STR);
}

// Bind parameter pagination
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':length', $length, PDO::PARAM_INT);

$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format data untuk DataTables
$data = [];
foreach ($logs as $log) {
      if ($log['note'] == "Initial stock added") {
        $actionType = "Create";
    } elseif ($log['old_quantity'] < $log['new_quantity']) {
        $actionType = "Add";
    } elseif ($log['old_quantity'] > $log['new_quantity']) {
        $actionType = "Minus";
    } else {
        $actionType = "-"; // kalau sama persis, fallback
    }
    $changedByName = getSuppliarName($pdo, $log['changed_by']);
    $data[] = [
        "suppliar_name" => $log['suppliar_name'],
        "product_name" => $log['product_name'],
        "action_type" => $actionType,
        "old_quantity" => $log['old_quantity'],
        "new_quantity" => $log['new_quantity'],
        "changed_by" => $changedByName ?? '-',
        "created_at" => $log['created_at'],
        "note" => $log['note']
    ];
}

// Output JSON DataTables
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);
