<?php
require_once '../init.php';
require_once 'fpdf/fpdf.php';

if (!isset($_GET['id'])) {
    die("Invoice number tidak ditemukan");
}

$invoice_number = $_GET['id'];

// Ambil data invoice (header + items)
$stmt = $pdo->prepare("
    SELECT 
        th.invoice_number,
        th.type,
        th.created_at,
        th.payment_type,
        th.bank_type,
        th.jenis_pengiriman,
        th.is_refund,
        s1.name AS suppliar_name,
        s1.role_id AS role_id,
        s2.name AS customer_name,
        p.product_name,
        th.quantity
    FROM transaction_histories th
    LEFT JOIN products p ON th.product_id = p.id
    LEFT JOIN suppliar s1 ON th.suppliar_id = s1.id
    LEFT JOIN suppliar s2 ON th.customer_id = s2.id
    WHERE th.invoice_number = :invoice
    ORDER BY th.id ASC
");
$stmt->bindValue(':invoice', $invoice_number, PDO::PARAM_STR);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    die("Data invoice tidak ditemukan");
}

// Ambil info header dari baris pertama
$header = $rows[0];

// Hitung total (kalau butuh total kuantitas, bisa juga total harga kalau ada kolom harga)
$totalQty = array_sum(array_column($rows, 'quantity'));

// Buat PDF
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

// Header Judul
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,'INVOICE TRANSAKSI',0,1,'C');
$pdf->Ln(5);

// Info Invoice
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,8,'Invoice No:',0,0);
$pdf->Cell(100,8,$header['invoice_number'],0,1);

$pdf->Cell(50,8,'Tanggal:',0,0);
$pdf->Cell(100,8,date('d-m-Y H:i', strtotime($header['created_at'])),0,1);

$pdf->Cell(50,8,'Distributor:',0,0);
$pdf->Cell(100,8,$header['role_id'] == 10 || $header['role_id'] == 1 ? 'Head Office' : $header['suppliar_name'],0,1);

$pdf->Cell(50,8,'Customer:',0,0);
$pdf->Cell(100,8,$header['customer_name'],0,1);

$pdf->Cell(50,8,'Jenis Transaksi:',0,0);
$pdf->Cell(100,8,ucfirst($header['type']),0,1);

$pdf->Cell(50,8,'Jenis Pembayaran:',0,0);
$pdf->Cell(100,8,($header['payment_type'] ?: '-').' '.$header['bank_type'],0,1);

$pdf->Cell(50,8,'Pengiriman:',0,0);
$pdf->Cell(100,8,$header['jenis_pengiriman'] ?: '-',0,1);

$pdf->Cell(50,8,'Status Refund:',0,0);
$pdf->Cell(100,8,$header['is_refund'] ? 'REFUND' : '-',0,1);

$pdf->Ln(10);

// Items Table Header
$pdf->SetFont('Arial','B',12);
$pdf->Cell(10,8,'No',1,0,'C');
$pdf->Cell(100,8,'Nama Produk',1,0,'C');
$pdf->Cell(30,8,'Qty',1,1,'C');

// Items Data
$pdf->SetFont('Arial','',12);
$no = 1;
foreach ($rows as $item) {
    $pdf->Cell(10,8,$no++,1,0,'C');
    $pdf->Cell(100,8,$item['product_name'],1,0);
    $pdf->Cell(30,8,$item['quantity'],1,1,'C');
}

// Total
$pdf->SetFont('Arial','B',12);
$pdf->Cell(110,8,'TOTAL',1,0,'R');
$pdf->Cell(30,8,$totalQty,1,1,'C');

$pdf->Ln(15);

// Footer
$pdf->SetFont('Arial','I',10);
$pdf->Cell(190,8,'Dokumen ini dibuat otomatis oleh sistem',0,1,'C');

// Output sebagai download
$pdf->Output('D', 'Invoice_'.$header['invoice_number'].'.pdf');
