<?php
require_once '../init.php';
require_once 'fpdf/fpdf.php';

if (!isset($_GET['id'])) die("Invoice number tidak ditemukan");
$invoice_number = $_GET['id'];

// Ambil data
$stmt = $pdo->prepare("
    SELECT th.invoice_number, th.type, th.created_at, th.payment_type, th.bank_type,
           s1.name AS suppliar_name, s1.role_id AS role_id, s1.suppliar_code AS suppliar_code,
           s2.name AS customer_name, s2.role_id AS customer_role, s2.suppliar_code AS customer_code,
           s2.address AS customer_address,
           p.product_name, th.quantity
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
if (!$rows) die("Data invoice tidak ditemukan");

$header = $rows[0];
$totalQty = array_sum(array_column($rows, 'quantity'));

function formatMember($name, $role_id, $code) {
    $prefix = ($role_id >= 2 && $role_id <= 5) ? 'D' : '';
    return $name . ' (' . $prefix . '-' . str_pad($code ?? '000000', 6, '0', STR_PAD_LEFT) . ')';
}

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

// Judul
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'INVOICE TRANSAKSI',0,1,'C');
$pdf->Ln(5);

// Kiri & Kanan Inline
$pdf->SetFont('Arial','',12);
$pengirim = ($header['role_id']==1 || $header['role_id']==10)?'Head Office':formatMember($header['suppliar_name'],$header['role_id'],$header['suppliar_code']);
$customer = formatMember($header['customer_name'],$header['customer_role'],$header['customer_code']);
$orderDate = date('d-m-Y H:i', strtotime($header['created_at']));
$jenisTransaksi = ucfirst($header['type']);
$paymentInfo = ($header['payment_type'] ?: '-') . ' ' . ($header['bank_type'] ?: '-');

// Lebar kolom kiri dan kanan
$widthLeft = 95;
$widthRight = 95;

// Baris 1: Pengirim & Tanggal
$pdf->Cell($widthLeft,6,"Pengirim: $pengirim",0,0);
$pdf->Cell($widthRight,6,"Tanggal: $orderDate",0,1);

// Baris 2: Pemesan & Jenis Transaksi
$pdf->Cell($widthLeft,6,"Pemesan: $customer",0,0);
$pdf->Cell($widthRight,6,"Jenis Transaksi: $jenisTransaksi",0,1);

// Baris 3: Kosong kiri & Pembayaran kanan
$pdf->Cell($widthLeft,6,"",0,0);
$pdf->Cell($widthRight,6,"Pembayaran: $paymentInfo",0,1);

$pdf->Ln(5);

// Table Items
$pdf->SetFont('Arial','B',12);
$pdf->Cell(10,8,'No',1,0,'C');
$pdf->Cell(140,8,'Nama Produk',1,0,'C');
$pdf->Cell(30,8,'Qty',1,1,'C');

$pdf->SetFont('Arial','',12);
$no = 1;
foreach ($rows as $item) {
    $pdf->Cell(10,8,$no++,1,0,'C');
    $pdf->Cell(140,8,$item['product_name'],1,0);
    $pdf->Cell(30,8,$item['quantity'],1,1,'C');
}

// Total
$pdf->SetFont('Arial','B',12);
$pdf->Cell(150,8,'TOTAL',1,0,'R');
$pdf->Cell(30,8,$totalQty,1,1,'C');

// Alamat pengiriman di bawah total
$pdf->Ln(5);
$alamat = $header['customer_address'] ?? '-';
$pdf->MultiCell(0,6,"Alamat Pengiriman: $alamat");

// Output PDF
$pdf->Output('D','Invoice_'.$header['invoice_number'].'.pdf');
