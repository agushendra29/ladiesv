<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id     = $_POST['product_id'];
    $quantity       = intval($_POST['quantity']);
    $total_payment  = floatval($_POST['total_payment']);
    $target_distributor_id = $_POST['distributor_id']; // distributor yang dituju
    $current_user_id = $_SESSION['distributor_id'];    // distributor yang memesan

    if (!empty($product_id) && !empty($quantity) && !empty($total_payment) && !empty($target_distributor_id)) {
        try {
            $pdo->beginTransaction();

            // Simpan purchase order
            $poData = [
                'distributor_id'   => $target_distributor_id,  // distributor yang dituju
                'user_order_id'    => $current_user_id,        // distributor yang memesan
                'product_id'       => $product_id,
                'quantity'         => $quantity,
                'total_amount'     => $total_payment,
                'status'           => 'pending',
                'created_at'       => date('Y-m-d H:i:s')
            ];
            $obj->create('purchase_orders', $poData);

            $pdo->commit();
            echo 'success';
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Gagal menyimpan data: " . $e->getMessage();
        }
    } else {
        echo "Silakan lengkapi semua field.";
    }
}
?>
