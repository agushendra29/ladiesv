<?php
require_once '../init.php';
require_once 'fpdf/fpdf.php';

if (!isset($_GET['id'])) {
    die("Invoice ID tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data invoice + detail
$sql = "
    SELECT i.*, 
           u1.name AS suppliar_name, u1.suppliar_code AS suppliar_code,
           u2.name AS customer_name, u2.suppliar_code AS customer_code,
		   u1.role_id AS role_id,
		   u2.address AS address
    FROM invoice i
    LEFT JOIN suppliar u1 ON i.suppliar_id = u1.id
    LEFT JOIN suppliar u2 ON i.customer_id = u2.id
    WHERE i.id = :id
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) {
    die("Invoice tidak ditemukan.");
}

// Ambil detail item
$sqlDetail = "
    SELECT d.*, p.product_name
    FROM invoice_details d
    LEFT JOIN products p ON d.pid = p.id
    WHERE d.invoice_no = :id
";
$stmt = $pdo->prepare($sqlDetail);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$details = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buat PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

// Judul
$pdf->Cell(0,10,'INVOICE TRANSAKSI',0,1,'C');
$pdf->Ln(5);

// Info Invoice
$pdf->SetFont('Arial','',12);
// Invoice #
$pdf->Cell(0,8,"Invoice #: " . $invoice['invoice_number'],0,1);

// Tanggal + Alamat jadi 1 kolom
$pdf->Cell(0,8,"Tanggal: " . date('d/m/Y', strtotime($invoice['order_date'])),0,1);
$pdf->MultiCell(0,8,"Alamat: " . $invoice['address'],0,1); 

// Distributor + Customer jadi 1 kolom
$invoiceBasedRole = ($invoice['role_id'] == 1 || $invoice['role_id'] == 10) 
    ? 'Distributor: Head Office' 
    : "Distributor: " . $invoice['suppliar_name'] . " (" . $invoice['suppliar_code'] . ")";
$pdf->Cell(0,8,$invoiceBasedRole,0,1);
$pdf->Cell(0,8,"Customer: " . $invoice['customer_name'] . " (" . $invoice['customer_code'] . ")",0,1);


$pdf->Ln(5);

// Header tabel
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,10,'Produk',1);
$pdf->Cell(30,10,'Qty',1);
$pdf->Cell(40,10,'Harga',1);
$pdf->Cell(40,10,'Subtotal',1);
$pdf->Ln();

// Data produk
$pdf->SetFont('Arial','',12);
$grandTotal = 0;
foreach ($details as $d) {
    $subtotal = $d['quantity'] * $d['price'];
    $grandTotal += $subtotal;

    $pdf->Cell(80,10,$d['product_name'],1);
    $pdf->Cell(30,10,$d['quantity'],1,0,'C');
    $pdf->Cell(40,10,number_format($d['price'],0,',','.'),1,0,'R');
    $pdf->Cell(40,10,number_format($subtotal,0,',','.'),1,0,'R');
    $pdf->Ln();
}

// Grand total
$pdf->SetFont('Arial','B',12);
$pdf->Cell(150,10,'Total',1);
$pdf->Cell(40,10,"Rp " . number_format($grandTotal,0,',','.'),1,0,'R');

$pdf->Output("I", "Invoice_" . $invoice['invoice_number'] . ".pdf");
