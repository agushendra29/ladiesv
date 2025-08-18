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

## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " AND (product_id LIKE :product_id or 
        product_name LIKE :product_name OR 
        brand_name LIKE :brand_name OR 
        catagory_name LIKE :catagory_name OR 
        product_source LIKE :product_source ) ";
   $searchArray = array( 
        'product_id'=>"%$searchValue%", 
        'product_name'=>"%$searchValue%",
        'brand_name'=>"%$searchValue%",
        'catagory_name'=>"%$searchValue%",
        'product_source'=>"%$searchValue%"
   );
}

## Total number of records without filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM products ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM products WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $pdo->prepare("SELECT * FROM products WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
   $data[] = array(
      "product_id"=>$counter++,
      "product_name"=>$row['product_name'],
      "sell_price" =>  $price_display,
      "action"=> $_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10 ?'
          <div class="btn-group" role="group" aria-label="Basic example">
            <a href="index.php?page=product_edit&&edit_id='.$row['id'].'" class="btn btn-secondary btn-sm rounded-0" id="memberEdit_btn"><i class="fas fa-edit"></i></a>
            <button type="button" id="productDelete_btn" class="btn btn-danger btn-sm rounded-0 ml-2" data-id="'.$row['id'].'"><i class="fas fa-trash-alt"></i></button>
          </div>
      ': '',
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