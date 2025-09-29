<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_suppliar = $_POST['p_suppliar'];
    $p_product = $_POST['p_product'];
    $stock_quantity = intval($_POST['stock_quantity']);

    // Validate input
    if (!empty($p_suppliar) && !empty($p_product) && !empty($stock_quantity)) {
           $now= date('Y-m-d H:i:s');
        
        // Get suppliar data
        $suppliar = $obj->find('suppliar', 'id', $p_suppliar);
        if (!$suppliar) {
            echo "Suppliar not found";
            exit;
        }

        // Get product data
        $product = $obj->find('products', 'id', $p_product);
        if (!$product) {
            echo "Product not found";
            exit;
        }

        // Check existing stock
        $stmt = $pdo->prepare("SELECT id, stock FROM distributor_stocks WHERE suppliar_id= ? AND product_id = ?");
        $stmt->execute([$suppliar->id, $product->id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update existing stock
            $new_stock = $existing['stock'] + $stock_quantity;
            $stmtUpdate = $pdo->prepare("UPDATE distributor_stocks SET stock = ? WHERE id = ?");
            $stmtUpdate->execute([$new_stock, $existing['id']]);

            // Insert log ke stock_logs
            $logData = array(
                'suppliar_id'  => $suppliar->id,
                'product_id'   => $product->id,
                'action_type'  => 'add',
                'old_quantity' => $existing['stock'],
                'new_quantity' => $new_stock,
                'changed_by'   => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0,
                'note'         => 'Stock updated'
            );
            $obj->create('stock_logs', $logData);

            // Insert transaction history
            $stmt = $pdo->prepare("INSERT INTO transaction_histories (suppliar_id, type, product_id, quantity, created_at, customer_id, customer_name, invoice_number) VALUES (?, 'pembelian', ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$suppliar->id, $product->id, $stock_quantity, $suppliar->id, $now , $suppliar->name, "-"]);

            echo "Stock updated successfully";
        } else {
            // Insert new stock
            $query = array(				
                'suppliar_id'   => $suppliar->id,						
                'product_id'    => $product->id,
                'suppliar_name' => $suppliar->name,
                'product_name'  => $product->product_name,						
                'stock'         => $stock_quantity,
                'role_id'       => $suppliar->role_id		
            );
            $res = $obj->create('distributor_stocks', $query);

            if ($res) {
                // Insert log ke stock_logs
                $logData = array(
                    'suppliar_id'  => $suppliar->id,
                    'product_id'   => $product->id,
                    'action_type'  => 'create',
                    'old_quantity' => NULL,
                    'new_quantity' => $stock_quantity,
                    'changed_by'   => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0,
                    'note'         => 'Initial stock added'
                );
                $obj->create('stock_logs', $logData);

                // Insert transaction history
                $stmt = $pdo->prepare("INSERT INTO transaction_histories (suppliar_id, type, product_id, quantity, created_at, customer_id, customer_name, invoice_number) VALUES (?, 'pembelian', ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$suppliar->id, $product->id, $stock_quantity, $suppliar->id, $now, $suppliar->name, "-"]);

                echo "Product added successfully";
            } else {
                echo "Failed to add product";
            }
        }

    } else {
        echo "Please fill out all required fields";
    }
}
?>
