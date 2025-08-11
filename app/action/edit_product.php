<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = trim($_POST['id'] ?? '');
    $product_name  = trim($_POST['product_name'] ?? '');
    $p_catagory    = trim($_POST['p_catagory'] ?? '');
    $price_hd      = trim($_POST['sell_price_hd'] ?? '');
    $price_d       = trim($_POST['sell_price_d'] ?? '');
    $price_a       = trim($_POST['sell_price_a'] ?? '');
    $price_r       = trim($_POST['sell_price_r'] ?? '');
    $date          = date('Y-m-d H:i:s');
    $user_id       = $_SESSION['distributor_id'] ?? null;

    if ($id && $product_name && $p_catagory  && $price_hd !== '' && $price_d !== '' && $price_a !== '' && $price_r !== '') {
        // Ambil nama kategori berdasarkan ID kategori
        $category = $obj->find('catagory', 'id', $p_catagory);
        if (!$category) {
            echo "Kategori produk tidak valid.";
            exit;
        }
        $p_catagory_name = $category->name;

        $updateData = [
            'product_name'   => $product_name,
            'catagory_id'    => $p_catagory,
            'sell_price_hd'  => $price_hd,
            'sell_price_d'   => $price_d,
            'sell_price_a'   => $price_a,
            'sell_price_r'   => $price_r,
            'last_update_at' => $date,
            'updated_by'     => $user_id
        ];

        $res = $obj->update('products', 'id', $id, $updateData);
        if ($res) {
            echo "Produk berhasil diperbarui.";
        } else {
            echo "Gagal memperbarui produk.";
        }
    } else {
        echo "Semua field wajib diisi dengan benar.";
    }
} else {
    echo "Metode request tidak valid.";
}
