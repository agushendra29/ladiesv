<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id     = $_POST['product_id'] ?? null;
    $quantity       = intval($_POST['quantity'] ?? 0);
    $total_payment  = floatval($_POST['total_payment']);
    $customer_id    = intval($_POST['buyer'] ?? 0);
    $customer_name  = trim($_POST['buyerName'] ?? '');
    $order_date     = date('Y-m-d'); // default: hari ini
    $current_user_id = $_SESSION['distributor_id']; 

    if (!empty($product_id) && $quantity > 0 && $total_payment > 0 && !empty($customer_name)) {
        try {
            $pdo->beginTransaction();

            // Ambil informasi produk
            $product = $obj->find('products', 'id', $product_id);
            if (!$product) {
                throw new Exception("Produk tidak ditemukan.");
            }
$role_id_seller = $_SESSION['role_id'] ?? 0;

switch ($role_id_seller) {
    case 1: // HO
        $price = floatval($product->sell_price_hd);
        break;
    case 2: // HD
        $price = floatval($product->sell_price_hd);
        break;
    case 3: // D
        $price = floatval($product->sell_price_d);
        break;
    case 4: // A
        $price = floatval($product->sell_price_a);
        break;
    case 5: // R
        $price = floatval($product->sell_price_r); // atau kalau ada harga khusus, ganti
        break;
    default:
        throw new Exception("Role ID penjual tidak dikenali.");
}
            $product_name = $product->product_name;
            $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id = ? AND product_id = ?");
            $stmt->execute([$current_user_id, $product_id]);
            $fromStock = $stmt->fetch();

             if (!$fromStock) {
                 throw new Exception("Stok distributor sumber tidak ditemukan. {$current_user_id} ");
             }
         
             if ($fromStock['stock'] < $quantity) {
                 throw new Exception("Stok tidak cukup. Sisa stok: {$fromStock['stock']}, permintaan: {$quantity}");
             }

            $newStockFrom = $fromStock['stock'] - $quantity;
            // Buat nomor invoice unik
            $invoice_number = 'INV-' . strtoupper(uniqid());

            // Simpan ke tabel invoice
            $invoiceData = [
                'invoice_number' => $invoice_number,
                'customer_id'    => $customer_id,
                'customer_name'  => $customer_name,
                'order_date'     => $order_date,
                'net_total'      => $total_payment, // ← langsung dari total_payment
                'return_status'  => 0,
                'last_update'    => $order_date,
                'suppliar_id' => $current_user_id
            ];
            $invoice_id = $obj->create('invoice', $invoiceData);
            // Simpan ke tabel invoice_details
            $detailData = [
                'invoice_no'    => $invoice_id,
                'pid'           => $product_id,
                'product_name'  => $product_name,
                'price'         => $price,
                'quantity'      => $quantity
            ];
            $obj->create('invoice_details', $detailData);


            $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = ? WHERE id = ?");
            $stmt->execute([$newStockFrom, $fromStock['id']]);

             $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id = ? AND product_id = ?");
             $stmt->execute([$customer_id, $product_id]);
             $toStock = $stmt->fetch();

             if ($toStock) {
                 $newStockTo = $toStock['stock'] + $quantity;
                 $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = ? WHERE id = ?");
                 $stmt->execute([$newStockTo, $toStock['id']]);
             } else {
                 $stmt = $pdo->prepare("SELECT * FROM suppliar WHERE id = ?");
                 $stmt->execute([$customer_id]);
                 $user = $stmt->fetch();
                 $stmt2 = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                 $stmt2->execute([$product_id]);
                 $prod = $stmt2->fetch();
                 if($customer_id != 0) {
                    $stmt = $pdo->prepare("INSERT INTO distributor_stocks (suppliar_id, product_id, stock, suppliar_name, product_name) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$customer_id, $product_id, $quantity, $user['name'], $prod['product_name']]);
                 }
        
             }

             $stmt = $pdo->prepare("INSERT INTO transaction_histories (suppliar_id, type, product_id, quantity, created_at, customer_id, customer_name, invoice_number) VALUES (?, 'penjualan', ?, ?, NOW(), ?, ?,?)");
             $stmt->execute([$current_user_id, $product_id, $quantity, $customer_id, $customer_name, $invoice_number]);

             $stmt = $pdo->prepare("
                UPDATE suppliar 
                SET total_point = total_point + :qty 
                WHERE id = :suppliar_id
            ");
            $stmt->execute([':qty' => $quantity,':suppliar_id' => $current_user_id]);

            $pdo->commit();
            echo "✅ Penjualan berhasil disimpan.";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "❌ Gagal menyimpan data: " . $e->getMessage();
        }
    } else {
        echo "⚠️ Silakan lengkapi semua data pembeli, produk, dan total pembayaran.";
    }
}