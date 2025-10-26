<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_number = $_POST['invoice_number'] ?? null;

    if (!$invoice_number) {
        echo json_encode(['status' => 'error', 'message' => 'Nomor Invoice tidak ditemukan atau tidak valid.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Ambil transaksi penjualan yang BELUM di-refund (is_refund = 0)
        $sql = "
            SELECT product_id, customer_id, suppliar_id, SUM(ABS(quantity)) AS quantity 
            FROM transaction_histories 
            WHERE invoice_number = :invoice_number 
              AND is_refund = 0 
              AND type = 'penjualan'
            GROUP BY product_id, customer_id, suppliar_id
            FOR UPDATE
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':invoice_number' => $invoice_number]);
        $transactions = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (!$transactions) {
            throw new Exception("Tidak ada transaksi penjualan yang belum di-refund untuk invoice {$invoice_number}.");
        }

        // --- Prepared Statements ---
        // KOREKSI 1: Menambah stok Supplier (Gudang) karena menerima barang kembali
        $updSupStock = $pdo->prepare("
            UPDATE distributor_stocks 
            SET stock = stock + :qty 
            WHERE suppliar_id = :sup AND product_id = :pid
        ");
        
        // Logika INSERT stok baru (jika record supplier belum ada)
        $insStock = $pdo->prepare("
            INSERT INTO distributor_stocks (suppliar_id, product_id, stock) 
            VALUES (:sup, :pid, :qty)
        ");
        
        // Pilih stok customer untuk cek ketersediaan
        $selCustStock = $pdo->prepare("
            SELECT stock FROM distributor_stocks 
            WHERE suppliar_id = :cust AND product_id = :pid 
            FOR UPDATE
        ");
        
        // KOREKSI 2: Mengurangi stok Customer (karena barang dikembalikan)
        $updCustStock = $pdo->prepare("
            UPDATE distributor_stocks 
            SET stock = stock - :qty 
            WHERE suppliar_id = :cust AND product_id = :pid
        ");
        
        // KOREKSI 3: Menambah poin Customer
        $updSupplierPoint = $pdo->prepare("
            UPDATE suppliar 
            SET total_point = total_point + :qty 
            WHERE id = :cust
        ");

        $total_items_refunded = 0;
        // VARIABEL BARU: Untuk melacak total kuantitas yang direfund
        $total_quantity_refunded = 0; 

        // --- Proses Refund ---
        foreach ($transactions as $trx) {
            $product_id  = (int)$trx->product_id;
            $quantity    = (int)$trx->quantity; 
            $suppliar_id = (int)$trx->suppliar_id;
            $customer_id = (int)$trx->customer_id;
            
            // Tambahkan kuantitas ke total
            $total_quantity_refunded += $quantity;

            // ==========================================================
            // LOGIKA 1: Pembaruan Stok Customer (Hanya jika customer_id != 0)
            // ==========================================================
            if ($customer_id != 0) {
                // 1a. Cek ketersediaan stok Customer
                $selCustStock->execute([':cust' => $customer_id, ':pid' => $product_id]);
                $custRow = $selCustStock->fetch(PDO::FETCH_ASSOC);

                if (!$custRow) {
                    throw new Exception("Record stok customer (ID: {$customer_id}) untuk produk {$product_id} tidak ditemukan.");
                }

                $currentStock = (int)$custRow['stock'];
                if ($currentStock < $quantity) {
                    throw new Exception("Stok customer (ID: {$customer_id}) untuk produk {$product_id} tidak mencukupi ({$currentStock} < {$quantity}) untuk direfund.");
                }

                // 1b. Kurangi stok customer
                $updCustStock->execute([
                    ':qty' => $quantity,
                    ':cust' => $customer_id,
                    ':pid' => $product_id
                ]);

                // 1c. Tambah poin customer
                $updSupplierPoint->execute([
                    ':qty' => $quantity,
                    ':cust' => $customer_id
                ]);
            }
            
            // ==========================================================
            // LOGIKA 2: Pembaruan Stok Supplier (Selalu dilakukan)
            // ==========================================================
            
            // 2a. Tambah stok ke supplier (Gudang)
            $updSupStock->execute([
                ':qty' => $quantity,
                ':sup' => $suppliar_id,
                ':pid' => $product_id
            ]);

            if ($updSupStock->rowCount() === 0) {
                // Jika record stok supplier belum ada, buat baru.
                $insStock->execute([
                    ':sup' => $suppliar_id,
                    ':pid' => $product_id,
                    ':qty' => $quantity
                ]);
            }
            
            // Hitung jumlah item yang berhasil diproses (baris transaksi yang digrup)
            $total_items_refunded++; 
        }

        // 3. Update status transaksi menjadi direfund (is_refund = 1)
        $updateSql = "
            UPDATE transaction_histories 
            SET is_refund = 1, tanggal_refund = :now, 
                note = 'Invoice dibatalkan' 
            WHERE invoice_number = :invoice_number 
              AND is_refund = 0 
        ";
        $stmt = $pdo->prepare($updateSql);
        $stmt->execute([
            ':invoice_number' => $invoice_number,
            ':now' => date('Y-m-d H:i:s')
        ]);
        
        $pdo->commit();

        // Menggunakan total_quantity_refunded untuk pesan yang akurat
        echo json_encode([
            'status' => 'success',
            'message' => "Refund penjualan berhasil diproses untuk {$total_items_refunded} jenis item (Total Kuantitas: {$total_quantity_refunded}). Stok Supplier telah bertambah."
        ]);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Gagal memproses refund: ' . $e->getMessage()]);
    }
}