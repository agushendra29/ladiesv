<?php
require_once '../init.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$role_id = $_SESSION['role_id'];

$searchArray = array();

## Filter aktif hanya jika role_id != 10
$roleCondition = "";
if ($role_id != 10 && $role_id != 1) {
    $roleCondition = " AND is_active = 1 ";
}

## Search 
$searchQuery = "";
if ($searchValue != '') {
    $searchQuery = " AND (product_id LIKE :product_id OR 
                          product_name LIKE :product_name) ";
    $searchArray = array( 
        'product_id'=>"%$searchValue%", 
        'product_name'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM products WHERE 1 $roleCondition");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM products WHERE 1 $roleCondition $searchQuery");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $pdo->prepare("SELECT * FROM products WHERE 1 $roleCondition $searchQuery 
                       ORDER BY ".$columnName." ".$columnSortOrder." 
                       LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
   $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}
$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();
$counter = $row + 1;

foreach($empRecords as $row){
    // Format harga
    if ($role_id == 1 || $role_id ==10) {
        $price_display = 
          "HD: Rp " . number_format($row['sell_price_hd'], 0, ',', '.') . "<br>" .
          "D: Rp " . number_format($row['sell_price_d'], 0, ',', '.') . "<br>" .
          "A: Rp " . number_format($row['sell_price_a'], 0, ',', '.') . "<br>" .
          "R: Rp " . number_format($row['sell_price_r'], 0, ',', '.');
    } elseif ($role_id == 2) {
        $price_display = 'Rp ' . number_format($row['sell_price_hd'], 0, ',', '.');
    } elseif ($role_id == 3) {
        $price_display = 'Rp ' . number_format($row['sell_price_d'], 0, ',', '.');
    } elseif ($role_id == 4) {
        $price_display = 'Rp ' . number_format($row['sell_price_a'], 0, ',', '.');
    } elseif ($role_id == 5) {
        $price_display = 'Rp ' . number_format($row['sell_price_r'], 0, ',', '.');
    } else {
        $price_display = 'N/A';
    }

    // Tombol aksi
    if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10) {
        $activeClass = $row['is_active'] ? 'background-color: #28a745; color: #fff;' : 'background-color: #6c757d; color: #fff;';
        $activeText = $row['is_active'] ? 'Active' : 'Inactive';

      $action = '
    <div style="display: flex; gap: 4px; font-size: 12px; text-align:center; justify-content:center !important;">
        <a href="index.php?page=product_edit&edit_id=' . $row['id'] . '" 
           style="padding: 3px 6px; background-color: #007bff; color: #fff; border: none; 
                  border-radius: 3px; cursor: pointer; text-decoration:none; display:inline-block;" 
           class="edit-product">Edit</a>
        <button style="padding: 3px 6px; border-radius: 3px; border: none; cursor: pointer; '.$activeClass.'" 
                class="toggle-active" data-id="'.$row['id'].'">'.$activeText.'</button>
    </div>
';
    } else {
        $action = '';
    }

    $data[] = array(
        "product_id" => $counter++,
        "product_name" => $row['product_name'],
        "sell_price" =>  $price_display,
        "action" => $action
    );
}

## Response
$response = array(
   "draw" => intval($draw),
   "iTotalRecords" => $totalRecords,
   "iTotalDisplayRecords" => $totalRecordwithFilter,
   "aaData" => $data
);

echo json_encode($response);
