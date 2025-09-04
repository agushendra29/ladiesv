<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_number = $_POST['invoice_number'] ?? '';

    if (empty($invoice_number)) {
        echo json_encode(['status' => 'error', 'message' => 'Invoice tidak ditemukan']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Ambil semua transaksi di invoice ini
        $sql = "SELECT * FROM transaction_histories WHERE invoice_number = :invoice AND ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':invoice' => $invoice_number]);
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (!$rows) {
            echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
            exit;
        }

        foreach ($rows as $row) {
            $suppliar_id = $row->suppliar_id;
            $customer_id = $row->customer_id;
            $product_id  = $row->product_id;
            $qty         = $row->quantity;

            // ✅ Cek stok customer sebelum dikurangi
            $checkStock = $pdo->prepare("SELECT stock FROM distributor_stocks WHERE suppliar_id = :id AND product_id = :pid FOR UPDATE");
            $checkStock->execute([':id' => $customer_id, ':pid' => $product_id]);
            $customerStock = $checkStock->fetchColumn();

            if ($customerStock === false) {
                throw new Exception("Stok customer tidak ditemukan (Customer ID: $customer_id, Product ID: $product_id)");
            }

            if ($customerStock < $qty) {
                throw new Exception("Stok customer tidak mencukupi untuk refund (Stok: $customerStock, Butuh: $qty)");
            }

            // ✅ Stok suppliar bertambah
            $pdo->prepare("UPDATE distributor_stocks SET stock = stock + :qty WHERE suppliar_id = :id AND product_id = :pid")
                ->execute([':qty' => $qty, ':id' => $suppliar_id, ':pid' => $product_id]);

            // ✅ Stok customer berkurang
            $pdo->prepare("UPDATE distributor_stocks SET stock = stock - :qty WHERE suppliar_id = :id AND product_id = :pid")
                ->execute([':qty' => $qty, ':id' => $customer_id, ':pid' => $product_id]);

            // ✅ Point customer dipotong (gunakan suppliar_id sesuai transaksi)
            $pdo->prepare("UPDATE suppliar SET total_point = total_point - :qty WHERE id = :id")
                ->execute([':qty' => $qty, ':id' => $suppliar_id]);

            // ✅ Update transaction_histories jadi refund
            $pdo->prepare("UPDATE transaction_histories SET is_refund = 1, note = 'Refund berhasil diproses', tanggal_refund = NOW() WHERE id = :id")
                ->execute([':id' => $row->id]);
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Refund berhasil diproses']);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
