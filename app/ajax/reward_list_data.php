<?php
require_once '../init.php';

// Parameter DataTables
$draw = $_POST['draw'] ?? 1;
$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$searchValue = $_POST['search']['value'] ?? '';
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';

// Mapping kolom sesuai tabel
$columns = ['id', 'nama_reward', 'periode_hadiah_dari', 'role_id', 'jumlah_point'];

// Sorting kolom validasi
$orderColumn = $columns[$orderColumnIndex] ?? 'id';

// Search query
$searchQuery = "";
$params = [];
if ($searchValue != '') {
    $searchQuery = " WHERE nama_reward LIKE :search OR periode_hadiah_dari LIKE :search ";
    $params[':search'] = "%$searchValue%";
}

// Total records tanpa filter
$totalRecordsStmt = $pdo->prepare("SELECT COUNT(*) FROM rewards");
$totalRecordsStmt->execute();
$totalRecords = $totalRecordsStmt->fetchColumn();

// Total records dengan filter
$totalFilteredStmt = $pdo->prepare("SELECT COUNT(*) FROM rewards $searchQuery");
$totalFilteredStmt->execute($params);
$totalFiltered = $totalFilteredStmt->fetchColumn();

// Ambil data dengan limit, offset, order
$dataStmt = $pdo->prepare("SELECT id, nama_reward, periode_hadiah_dari, periode_hadiah_sampai, role_id, jumlah_point FROM rewards $searchQuery ORDER BY $orderColumn $orderDir LIMIT :limit OFFSET :offset");

// Bind parameters
foreach ($params as $key => $val) {
    $dataStmt->bindValue($key, $val, PDO::PARAM_STR);
}

$dataStmt->bindValue(':limit', (int)$length, PDO::PARAM_INT);
$dataStmt->bindValue(':offset', (int)$start, PDO::PARAM_INT);

$dataStmt->execute();

$data = [];
$no = $start + 1;
while ($row = $dataStmt->fetch(PDO::FETCH_ASSOC)) {
     $periode = htmlspecialchars($row['periode_hadiah_dari']) . " s/d " . htmlspecialchars($row['periode_hadiah_sampai']);
    $data[] = [
        'no' => $no++,
        'nama_reward' => htmlspecialchars($row['nama_reward']),
        'jumlah_point' => (int)$row['jumlah_point'],
        'periode_hadiah' => $periode ,
        'role_id' => (int)$row['role_id'],
        'aksi' => ''
    ];
}

$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
exit;
