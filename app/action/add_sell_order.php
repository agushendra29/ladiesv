<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

try {
    $payload = null;
    $current_role_id = (int)$_SESSION['role_id'];
    if (isset($_POST['data'])) {
        $payload = json_decode($_POST['data'], true);
        if (!is_array($payload)) {
            throw new Exception('Payload JSON tidak valid.');
        }

        $customer_id  = isset($payload['buyerId']) && $payload['buyerId'] !== '' ? (int)$payload['buyerId'] : 0;
        $buyer_manual = isset($payload['buyerName']) ? trim($payload['buyerName']) : '';
        $products     = isset($payload['products']) && is_array($payload['products']) ? $payload['products'] : [];
    } else {
        $product_ids = $_POST['product_id'] ?? [];
        $quantities  = $_POST['quantity'] ?? [];
        $customer_id = isset($_POST['customer_name']) && $_POST['customer_name'] !== '' ? (int)$_POST['customer_name'] : 0;
        $buyer_manual = isset($_POST['buyer']) ? trim($_POST['buyer']) : '';
        $products = [];
        foreach ($product_ids as $i => $pid) {
            $qty = isset($quantities[$i]) ? (int)$quantities[$i] : 0;
            $products[] = [
                'product_id' => $pid,
                'quantity'   => $qty
            ];
        }
    }

    if (!isset($_SESSION['distributor_id'])) {
        throw new Exception('Session distributor tidak ditemukan.');
    }
    $current_user_id = (int)$_SESSION['distributor_id'];
    $order_date = date('Y-m-d');

    $customer_name = '';
    if ($customer_id > 0) {
        $cust = $obj->find('suppliar', 'id', $customer_id);
        if (!$cust) {
            throw new Exception('Data customer tidak ditemukan.');
        }
        $customer_name = $cust->name;
        $buyer_role_id = (int)$cust->role_id;
    } else {
        $customer_name = $buyer_manual ?: 'Penjualan Pribadi';
        $buyer_role_id = 0;
    }

    $items = [];
    foreach ($products as $p) {
        $pid = isset($p['product_id']) ? (int)$p['product_id'] : 0;
        $qty = isset($p['quantity'])   ? (int)$p['quantity']   : 0;
        if ($pid > 0 && $qty > 0) {
            $items[] = ['pid' => $pid, 'qty' => $qty];
        }
    }

    if (empty($items)) {
        throw new Exception('Produk dan jumlah wajib diisi.');
    }

    $pdo->beginTransaction();

    $invoice_number = 'INV-' . strtoupper(uniqid());
    $invoiceData = [
        'invoice_number' => $invoice_number,
        'customer_id'    => $customer_id,
        'customer_name'  => $customer_name,
        'order_date'     => $order_date,
        'net_total'      => 0,
        'return_status'  => 0,
        'last_update'    => $order_date,
        'suppliar_id'    => $current_user_id
    ];
    $invoice_id = $obj->create('invoice', $invoiceData);

    $grand_total  = 0;
    $total_qty_seller_points = 0;

    // ==== Tambahan untuk reseller ====
    $invoice_id_reseller = null;
    $grand_total_reseller = 0;
    // =================================

    foreach ($items as $it) {
        $pid = $it['pid'];
        $qty = $it['qty'];

        $product = $obj->find('products', 'id', $pid);
        if (!$product) {
            throw new Exception("Produk ID {$pid} tidak ditemukan.");
        }

        switch ($buyer_role_id) {
            case 0: $price = 0; break;
            case 1:
            case 2: $price = (float)$product->sell_price_hd; break;
            case 3: $price = (float)$product->sell_price_d;  break;
            case 4: $price = (float)$product->sell_price_a;  break;
            case 5: default: $price = (float)$product->sell_price_r;  break;
        }

        $stock_suppliar_id = ($current_role_id === 1 || $current_role_id === 10) ? 1 : $current_user_id;
        $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id = ? AND product_id = ? FOR UPDATE");
        $stmt->execute([$stock_suppliar_id, $pid]);
        $fromStock = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$fromStock) {
            throw new Exception("Stok produk {$product->product_name} tidak ditemukan pada suppliar pengirim.");
        }
        if ((int)$fromStock['stock'] < $qty) {
            throw new Exception("Stok {$product->product_name} kurang. Sisa {$fromStock['stock']}, diminta {$qty}.");
        }

        $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = ? WHERE id = ?");
        $stmt->execute([(int)$fromStock['stock'] - $qty, (int)$fromStock['id']]);

        if ($customer_id !== 0) {
            $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id = ? AND product_id = ? FOR UPDATE");
            $stmt->execute([$customer_id, $pid]);
            $toStock = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($toStock) {
                $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = ? WHERE id = ?");
                $stmt->execute([(int)$toStock['stock'] + $qty, (int)$toStock['id']]);
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO distributor_stocks (suppliar_id, product_id, stock, suppliar_name, product_name)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$customer_id, $pid, $qty, $customer_name, $product->product_name]);
            }
        }

        $obj->create('invoice_details', [
            'invoice_no'   => $invoice_id,
            'pid'          => $pid,
            'product_name' => $product->product_name,
            'price'        => $price,
            'quantity'     => $qty
        ]);

        $grand_total += ($price * $qty);
        $now= date('Y-m-d H:i:s');

        $stmt = $pdo->prepare("
            INSERT INTO transaction_histories
                (suppliar_id, type, product_id, quantity, created_at, customer_id, customer_name, invoice_number)
            VALUES (?, 'penjualan', ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$current_user_id, $pid, $qty, $now, $customer_id, $customer_name, $invoice_number]);

        $stmt = $pdo->prepare("
            INSERT INTO transaction_histories
                (suppliar_id, type, product_id, quantity, created_at, customer_id, customer_name, invoice_number)
            VALUES (?, 'pembelian', ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$current_user_id, $pid, $qty, $now, $customer_id, $customer_name, $invoice_number]);

        // ==== bagian Reseller ====
        if($buyer_role_id == 5) {
            if (!$invoice_id_reseller) {
                $invoice_number_reseller = 'INV-R' . strtoupper(uniqid());
                $invoiceDataReseller = [
                    'invoice_number' => $invoice_number_reseller,
                    'customer_id'    => 0,
                    'customer_name'  => "penjualan pribadi",
                    'order_date'     => $order_date,
                    'net_total'      => 0,
                    'return_status'  => 0,
                    'last_update'    => $order_date,
                    'suppliar_id'    => $customer_id
                ];
                $invoice_id_reseller = $obj->create('invoice', $invoiceDataReseller);
            }

            // total untuk reseller (pakai harga reseller)
            $grand_total_reseller += ($price * $qty);

            $stmt = $pdo->prepare("
                INSERT INTO transaction_histories
                    (suppliar_id, type, product_id, quantity, created_at, customer_id, customer_name, invoice_number)
                VALUES (?, 'penjualan', ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$customer_id, $pid, $qty, $now, 0, "penjualan pribadi", $invoice_number_reseller]);
        }
        // =========================

        $total_qty_seller_points += $qty;
    }

    // Update net_total invoice utama
    $stmt = $pdo->prepare("UPDATE invoice SET net_total = ?, last_update = ? WHERE id = ?");
    $stmt->execute([$grand_total, $order_date, $invoice_id]);

    // ==== update net_total khusus Reseller ====
    if ($invoice_id_reseller) {
        $stmt = $pdo->prepare("UPDATE invoice SET net_total = ?, last_update = ? WHERE id = ?");
        $stmt->execute([$grand_total_reseller, $order_date, $invoice_id_reseller]);
    }
    // ==========================================

    $stmt = $pdo->prepare("UPDATE suppliar SET total_point = total_point + :qty WHERE id = :sid");
    $stmt->execute([':qty' => $total_qty_seller_points, ':sid' => $current_user_id]);

    $pdo->commit();
    echo "yes";

} catch (Exception $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "❌ " . $e->getMessage();
}
