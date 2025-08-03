<?php 
require_once '../init.php';

if (isset($_POST)) {
    $product_id     = $_POST['product_id'];
    $quantity       = intval($_POST['quantity']);
    $total_payment  = floatval($_POST['total_payment']);
    $distributor_id = $_POST['distributor_id'];
    $user_id        = $_SESSION['user_id'];

    if (!empty($product_id) && !empty($quantity) && !empty($total_payment) && !empty($distributor_id)) {
        
        try {
            // Mulai transaksi
            $pdo->beginTransaction();

            // 1. Simpan ke tabel purchase_orders
            $poData = array(
                'distributor_id'   => $distributor_id,
                'total_amount'     => $total_payment,
                'status'  => 'pending',
                'user_order_id' => $user_id
            );

            $poRes = $obj->create('purchase_orders', $poData);

            if ($poRes) {
                $purchase_order_id = $pdo->lastInsertId();

                // 2. Simpan ke tabel purchase_order_items
                $itemData = array(
                    'purchase_order_id' => $purchase_order_id,
                    'product_id'        => $product_id,
                    'quantity'          => $quantity
                );

                $itemRes = $obj->create('purchase_order_items', $itemData);

                if ($itemRes) {
                    $pdo->commit();
                    echo "yes";
                } else {
                    $pdo->rollBack();
                    echo "Gagal menyimpan item PO";
                }

            } else {
                $pdo->rollBack();
                echo "Gagal membuat purchase order";
            }

        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Error: " . $e->getMessage();
        }

    } else {
        echo "Silakan lengkapi semua field yang wajib diisi";
    }
}
?>
