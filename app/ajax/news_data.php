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
$stmt = $pdo->prepare("SELECT id, title, content, publish_date, created_at, is_active FROM news "
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

function deleteNews($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}

function limitContent($text, $maxChars = 100) {
    $text = strip_tags($text);
    if (strlen($text) > $maxChars) {
        $text = substr($text, 0, $maxChars) . '...';
    }
    $lines = explode("\n", wordwrap($text, 50));
    $lines = array_slice($lines, 0, 2);
    return implode("<br>", $lines);
}

$data = array();
foreach ($newsRecords as $row) {
     $isActive = (int)$row['is_active'] === 1;

    $publishButton = '';
   if ($isActive) {
    $publishButton = '
        <button type="button"
            class="newsTogglePublish_btn"
            data-id="'.$row['id'].'"
            data-action="hide"
            style="
                background-color: #ef4444;
                color: #fff;
                font-size: 12px;
                font-weight: 600;
                border: none;
                border-radius: 8px;
                padding: 6px 14px;
                cursor: pointer;
                box-shadow: 0 2px 6px rgba(239,68,68,0.3);
                transition: all 0.3s ease;
            "
            onmouseover="this.style.backgroundColor=\'#dc2626\'; this.style.boxShadow=\'0 3px 8px rgba(220,38,38,0.4)\'"
            onmouseout="this.style.backgroundColor=\'#ef4444\'; this.style.boxShadow=\'0 2px 6px rgba(239,68,68,0.3)\'"
        >
            🚫 Hide
        </button>';
} else {
    $publishButton = '
        <button type="button"
            class="newsTogglePublish_btn"
            data-id="'.$row['id'].'"
            data-action="publish"
            style="
                background-color: #3b82f6;
                color: #fff;
                font-size: 12px;
                font-weight: 600;
                border: none;
                border-radius: 8px;
                padding: 6px 14px;
                cursor: pointer;
                box-shadow: 0 2px 6px rgba(59,130,246,0.3);
                transition: all 0.3s ease;
            "
            onmouseover="this.style.backgroundColor=\'#2563eb\'; this.style.boxShadow=\'0 3px 8px rgba(37,99,235,0.4)\'"
            onmouseout="this.style.backgroundColor=\'#3b82f6\'; this.style.boxShadow=\'0 2px 6px rgba(59,130,246,0.3)\'"
        >
            ✅ Publish
        </button>';
}


    $data[] = array(
        "title" => htmlspecialchars($row['title']),
        "content" => '<div class="content-cell">' . htmlspecialchars(limitContent($row['content'])) . '</div>',
        "publish_date" => date('d M Y', strtotime($row['publish_date'])),
        "created_at" => date('d M Y', strtotime($row['created_at'])),
      "action" => '
  <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
    <a href="index.php?page=edit_news&&edit_id='.$row['id'].'" 
       class="btn-action btn-blue">
       Edit
    </a>

    <button type="button" 
            id="newsDelete_btn" 
            class="btn-action btn-red" 
            data-id="'.$row['id'].'">
      Hapus
    </button>

    '.$publishButton.'
  </div>
',

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
