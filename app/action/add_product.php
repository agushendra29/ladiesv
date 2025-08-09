<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name  = trim($_POST['product_name'] ?? '');
    $p_catagory    = trim($_POST['p_catagory'] ?? '');
    $price_hd      = trim($_POST['sell_price_hd'] ?? '');
    $price_d       = trim($_POST['sell_price_d'] ?? '');
    $price_a       = trim($_POST['sell_price_a'] ?? '');
    $price_r       = trim($_POST['sell_price_r'] ?? '');
    $user_id       = $_SESSION['user_id'] ?? null;

    if ($product_name && $p_catagory && $price_hd && $price_d && $price_a && $price_r) {
        try {
            $data = [
                'product_name'   => $product_name,
                'catagory_id'    => $p_catagory,
                'sell_price_hd'  => $price_hd,
                'sell_price_d'   => $price_d,
                'sell_price_a'   => $price_a,
                'sell_price_r'   => $price_r,
                'added_by'       => $user_id
            ];

            $result = $obj->create('products', $data);

            if ($result) {
                echo "yes"; // ✅ sukses
            } else {
                echo "Gagal menyimpan produk.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Semua field wajib diisi.";
    }
} else {
    echo "Metode request tidak valid.";
}
