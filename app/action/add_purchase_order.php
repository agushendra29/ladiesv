<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_ids     = isset($_POST['product_id']) ? $_POST['product_id'] : [];
    $quantities      = isset($_POST['quantity']) ? $_POST['quantity'] : [];
    $total_payments  = isset($_POST['total_payment']) ? $_POST['total_payment'] : [];
    $current_user_id = $_SESSION['distributor_id']; // distributor yg pesan

    if (!empty($product_ids) && is_array($product_ids)) {
        try {
            $pdo->beginTransaction();

            $po_id = uniqid("PO_");
            $seen_products = []; // simpan product_id yang sudah masuk

            foreach ($product_ids as $index => $product_id) {
                $product_id    = intval($product_id);
                $quantity      = isset($quantities[$index]) ? intval($quantities[$index]) : 0;
                $total_payment = isset($total_payments[$index]) ? floatval($total_payments[$index]) : 0;

                // Cek duplicate product_id
                if (in_array($product_id, $seen_products)) {
                    throw new Exception("Produk dengan ID {$product_id} duplikat dalam pesanan.");
                }

                if ($product_id > 0 && $quantity > 0) {
                    $poData = [
                        'po_id'          => $po_id,
                        'suppliar_id'    => $current_user_id,
                        'product_id'     => $product_id,
                        'quantity'       => $quantity,
                        'total_amount'   => $total_payment,
                        'status'         => 'pending',
                        'created_at'     => date('Y-m-d H:i:s'),
                        'invoice_number' => "-"
                    ];

                    $obj->create('purchase_orders', $poData);

                    // Tambahkan ke daftar produk yang sudah diproses
                    $seen_products[] = $product_id;
                }
            }

            $pdo->commit();
            echo json_encode([
                "success" => true,
                "message" => "✅ Pemesanan berhasil! Semua produk masuk ke order <b>{$po_id}</b>",
                "po_id"   => $po_id
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode([
                "success" => false,
                "message" => "❌ Gagal menyimpan data: " . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Silakan pilih minimal 1 produk."
        ]);
    }
}
?>
