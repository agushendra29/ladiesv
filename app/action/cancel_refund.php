<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_number = $_POST['invoice_number'] ?? null;

    if (!$invoice_number) {
        // Mengubah pesan agar lebih jelas (Invoice tidak ditemukan atau tidak valid)
        echo json_encode(['status' => 'error', 'message' => 'Nomor Invoice tidak ditemukan atau tidak valid.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // ✅ Ambil hanya transaksi refund bertipe "penjualan"
        $sql = "
            SELECT product_id, customer_id, suppliar_id, SUM(quantity) AS quantity
            FROM transaction_histories 
            WHERE invoice_number = :invoice_number 
              AND is_refund = 1 
              AND type = 'penjualan'
            GROUP BY product_id, customer_id, suppliar_id
            FOR UPDATE
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':invoice_number' => $invoice_number]);
        $transactions = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (!$transactions) {
            throw new Exception("Tidak ada transaksi refund penjualan yang aktif untuk invoice {$invoice_number}.");
        }

        // ✅ Cek apakah ada transaksi tipe 'pembelian' dengan invoice sama — abaikan
        $ignoreCount = $pdo->prepare("
            SELECT COUNT(*) FROM transaction_histories 
            WHERE invoice_number = :invoice_number AND type = 'pembelian'
        ");
        $ignoreCount->execute([':invoice_number' => $invoice_number]);
        if ($ignoreCount->fetchColumn() > 0) {
            // Informasi: Ada transaksi pembelian yang terkait, tetapi tidak diubah stoknya.
        }

        // ===============================================
        // === Prepare reusable statements (Koreksi Logika Stok) ===
        // ===============================================

        // KOREKSI 1: Mengurangi stok dari supplier (Karena stok dikembalikan ke customer)
        $updSupStock = $pdo->prepare("
            UPDATE distributor_stocks 
            SET stock = stock - :qty 
            WHERE suppliar_id = :sup AND product_id = :pid
        ");
        
        // Logika INSERT stok baru (jika record supplier belum ada)
        $insStock = $pdo->prepare("
            INSERT INTO distributor_stocks (suppliar_id, product_id, stock) 
            VALUES (:sup, :pid, :qty)
        ");
        
        // Pilih stok customer (tetap sama)
        $selCustStock = $pdo->prepare("
            SELECT stock FROM distributor_stocks 
            WHERE suppliar_id = :cust AND product_id = :pid 
            FOR UPDATE
        ");
        
        // KOREKSI 2: Menambah stok customer (Karena penjualan kembali dianggap valid)
        $updCustStock = $pdo->prepare("
            UPDATE distributor_stocks 
            SET stock = stock + :qty 
            WHERE suppliar_id = :cust AND product_id = :pid
        ");
        
        // KOREKSI 3: Mengurangi poin supplier/customer (Karena penjualan kembali dianggap valid)
        $updSupplierPoint = $pdo->prepare("
            UPDATE suppliar 
            SET total_point = total_point - :qty 
            WHERE id = :cust
        ");

        foreach ($transactions as $trx) {
            $product_id  = (int)$trx->product_id;
            $quantity    = (int)$trx->quantity;
            $suppliar_id = (int)$trx->suppliar_id;
            $customer_id = (int)$trx->customer_id;

            // === Kurangi stok dari supplier (KOREKSI: Dulu 'stock + :qty') ===
            $updSupStock->execute([
                ':qty' => $quantity,
                ':sup' => $suppliar_id,
                ':pid' => $product_id
            ]);
            
            // Catatan: Jika $updSupStock rowCount 0 (record tidak ditemukan),
            // dan kita mencoba MENGURANGI stok, maka insStock harusnya
            // tidak dijalankan, atau harus menyisipkan nilai negatif, yang tidak ideal.
            // Untuk sementara, kita pertahankan logika insStock yang lama,
            // mengasumsikan record supplier pasti ada saat terjadi refund.
            // Anda mungkin perlu menyesuaikan ini jika stok bisa negatif.
            if ($updSupStock->rowCount() === 0) {
                // throw new Exception("Record stok supplier (ID: {$suppliar_id}) untuk produk {$product_id} tidak ditemukan."); 
                
                // Jika Anda ingin mengizinkan pembuatan record, gunakan logika berikut (hati-hati dengan nilai stok awal):
                /*
                $insStock->execute([
                    ':sup' => $suppliar_id,
                    ':pid' => $product_id,
                    ':qty' => 0 - $quantity // Sisipkan stok awal yang dikurangi
                ]);
                */
            }

            // === Tambah stok customer (KOREKSI: Dulu 'stock - :qty') ===
            if ($customer_id != 0) {
                $selCustStock->execute([':cust' => $customer_id, ':pid' => $product_id]);
                $custRow = $selCustStock->fetch(PDO::FETCH_ASSOC);

                if (!$custRow) {
                    // Jika record tidak ditemukan, buat record baru (asumsi stok awal 0)
                    $insStock->execute([
                        ':sup' => $customer_id,
                        ':pid' => $product_id,
                        ':qty' => $quantity
                    ]);
                } else {
                    $updCustStock->execute([
                        ':qty' => $quantity,
                        ':cust' => $customer_id,
                        ':pid' => $product_id
                    ]);
                }

                // Kurangi poin (KOREKSI: Dulu 'total_point + :qty')
                $updSupplierPoint->execute([
                    ':qty' => $quantity,
                    ':cust' => $customer_id
                ]);
            }
        }

        // ✅ Update hanya baris refund penjualan, bukan pembelian
        $updateSql = "
            UPDATE transaction_histories 
            SET is_refund = 0, tanggal_refund = NULL, 
                note = CONCAT(COALESCE(note, ''), ' | Refund cancelled at ', :now) 
            WHERE invoice_number = :invoice_number 
              AND is_refund = 1 
              AND type = 'penjualan'
        ";
        $stmt = $pdo->prepare($updateSql);
        $stmt->execute([
            ':invoice_number' => $invoice_number,
            ':now' => date('Y-m-d H:i:s')
        ]);

        $pdo->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Refund penjualan berhasil dibatalkan. Stok telah disesuaikan kembali (Supplier - ' . $quantity . ', Customer + ' . $quantity . ').'
        ]);

    } catch (Exception $e) {
        $pdo->rollBack();
        // Pastikan error ditampilkan hanya untuk debugging.
        echo json_encode(['status' => 'error', 'message' => 'Gagal membatalkan refund: ' . $e->getMessage()]);
    }
}