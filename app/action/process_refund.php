<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method.";
    exit;
}

$invoice_number = trim($_POST['invoice_number'] ?? '');
$refund_quantity = (int)($_POST['refund_quantity'] ?? 0);
$refund_reason = trim($_POST['refund_reason'] ?? '');

if (!$invoice_number || $refund_quantity <= 0 || !$refund_reason) {
    echo "Please fill all required fields correctly.";
    exit;
}

try {
    // Cari transaksi berdasarkan invoice_number
   $stmt = $pdo->prepare("SELECT * FROM transaction_histories WHERE invoice_number = :invoice_number AND type = 'pembelian' LIMIT 1");
    $stmt->execute(['invoice_number' => $invoice_number]);
    $transaction = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$transaction) {
        echo "Invoice number not found.";
        exit;
    }

    // Ambil stok suppliar dan customer saat ini (contoh tabel stok harus disesuaikan)
    // Misal tabel stocks dengan kolom suppliar_id, product_id, stock_qty

    // Cek stok customer dulu (harus cukup untuk refund)
    $stmt = $pdo->prepare("SELECT stock FROM distributor_stocks WHERE suppliar_id = :customer_id AND product_id = :product_id LIMIT 1");
    $stmt->execute([
        'customer_id' => $transaction->customer_id,
        'product_id' => $transaction->product_id
    ]);
    $customerStock = $stmt->fetchColumn();

    if ($customerStock === false) {
        echo "Customer stock record not found.";
        exit;
    }

    if ($customerStock < $refund_quantity) {
        echo "Customer does not have enough stock to refund.";
        exit;
    }

    // Update stok suppliar: tambah stok
    $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = stock + :qty WHERE suppliar_id = :suppliar_id AND product_id = :product_id");
    $stmt->execute([
        'qty' => $refund_quantity,
        'suppliar_id' => $transaction->suppliar_id,
        'product_id' => $transaction->product_id
    ]);

    // Update stok customer: kurangi stok
    $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = stock - :qty WHERE suppliar_id = :customer_id AND product_id = :product_id");
    $stmt->execute([
        'qty' => $refund_quantity,
        'customer_id' => $transaction->customer_id,
        'product_id' => $transaction->product_id
    ]);

    // Insert ke transaction_histories untuk record refund (optional)
    $stmt = $pdo->prepare("INSERT INTO transaction_histories 
        (suppliar_id, type, product_id, quantity, created_at, note, customer_id, customer_name, invoice_number) 
        VALUES (:suppliar_id, 'refund', :product_id, :quantity, NOW(), :note, :customer_id, :customer_name, :invoice_number)");
    $stmt->execute([
        'suppliar_id' => $transaction->suppliar_id,
        'product_id' => $transaction->product_id,
        'quantity' => $refund_quantity,
        'note' => $refund_reason,
        'customer_id' => $transaction->customer_id,
        'customer_name' => $transaction->customer_name,
        'invoice_number' => $invoice_number
    ]);

    echo "Refund berhasil diproses.";
} catch (Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}
