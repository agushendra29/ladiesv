<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_number = $_POST['invoice_number'] ?? null;

    if (!$invoice_number) {
        echo json_encode(['status' => 'error', 'message' => 'Invoice tidak ditemukan']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Ambil semua transaksi yang termasuk invoice ini
        $sql = "SELECT * FROM transaction_histories WHERE invoice_number = :invoice_number";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':invoice_number' => $invoice_number]);
        $transactions = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (!$transactions) {
            throw new Exception("Data transaksi tidak ditemukan");
        }

        foreach ($transactions as $trx) {
            $product_id   = $trx->product_id;
            $quantity     = $trx->quantity;
            $suppliar_id  = $trx->suppliar_id;
            $customer_id  = $trx->customer_id;

            // === Balik stok ===
            // kurangi stok supplier
            $pdo->prepare("
                UPDATE distributor_stocks 
                SET stock = stock - :qty 
                WHERE suppliar_id = :sup AND product_id = :pid
            ")->execute([
                ':qty' => $quantity,
                ':sup' => $suppliar_id,
                ':pid' => $product_id
            ]);

            // tambah stok kembali ke customer
            $pdo->prepare("
                UPDATE distributor_stocks 
                SET stock = stock + :qty 
                WHERE suppliar_id = :cust AND product_id = :pid
            ")->execute([
                ':qty' => $quantity,
                ':cust' => $customer_id,
                ':pid' => $product_id
            ]);

            // === Balik point customer ===
            $pdo->prepare("
                UPDATE suppliar 
                SET total_point = total_point + :qty 
                WHERE id = :cust
            ")->execute([
                ':qty' => $quantity,
                ':cust' => $customer_id
            ]);
        }

        // update status refund jadi 0
        $updateSql = "UPDATE transaction_histories 
                      SET is_refund = 0, tanggal_refund = NULL, note= 'Refund berhasil dicancel' 
                      WHERE invoice_number = :invoice_number";
        $stmt = $pdo->prepare($updateSql);
        $stmt->execute([':invoice_number' => $invoice_number]);

        $pdo->commit();

        echo json_encode(['status' => 'success', 'message' => 'Refund berhasil dibatalkan & point dikembalikan']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
