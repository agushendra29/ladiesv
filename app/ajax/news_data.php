<?php
require_once '../init.php'; // Make sure $pdo is defined here

// Read DataTables parameters
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$searchArray = array();

// Build search query if searchValue is not empty
$searchQuery = "";
if ($searchValue != '') {
    $searchQuery = " WHERE (title LIKE :title OR content LIKE :content)";
    $searchArray = array(
        'title' => "%$searchValue%",
        'content' => "%$searchValue%"
    );
}

// Total records without filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM news");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

// Total records with filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM news " . $searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

// Fetch records with limit, offset, order, and search
$stmt = $pdo->prepare("SELECT id, title, content, publish_date, created_at FROM news "
    . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder
    . " LIMIT :limit OFFSET :offset");

// Bind search parameters
foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}

// Bind pagination params
$stmt->bindValue(':limit', (int)$rowperpage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$row, PDO::PARAM_INT);

$stmt->execute();
$newsRecords = $stmt->fetchAll();

$data = array();
foreach ($newsRecords as $row) {
    $data[] = array(
        "id" => $row['id'],
        "title" => $row['title'],
        "content"=> $row['content'],
        "publish_date" => date('d M Y', strtotime($row['publish_date'])),
        "created_at" => date('d M Y H:i', strtotime($row['created_at'])),
    );
}

// Prepare response for DataTables
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
