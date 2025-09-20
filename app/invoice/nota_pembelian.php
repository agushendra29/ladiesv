<?php
require_once '../init.php';
require_once 'fpdf/fpdf.php';

if (!isset($_GET['id'])) die("Invoice number tidak ditemukan");
$invoice_number = $_GET['id'];

// === Query data TIDAK diubah ===
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

$header   = $rows[0];
$totalQty = array_sum(array_column($rows,'quantity'));

function formatMember($name,$role_id,$code){
    $prefix = ($role_id >= 2 && $role_id <= 5) ? 'D' : '';
    return $name.' ('.$prefix.'-'.str_pad($code ?? '000000',6,'0',STR_PAD_LEFT).')';
}

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->AddFont('Montserrat','','Montserrat-Regular.php');
$pdf->AddFont('Montserrat','B','Montserrat-Bold.php');

// ==================== HEADER ====================
$pdf->Image('logo.jpeg',10,10,40); // logo kiri

$pdf->SetXY(130,12);
$pdf->SetFont('Montserrat','B',14);
$pdf->Cell(70,8,'INVOICE',0,2,'R');

$pdf->SetFont('Montserrat','',10);
$pdf->Cell(70,6,'No: '.$header['invoice_number'],0,2,'R');
$pdf->Cell(70,6,'Tanggal: '.date('d-m-Y H:i',strtotime($header['created_at'])),0,2,'R');
$pdf->Cell(70,6,'Pembayaran: '.(($header['payment_type'] ?: '-') . ' ' . ($header['bank_type'] ?: '-')),0,2,'R');
$pdf->Ln(15);

// ==================== INFORMASI PEMBELI / PENJUAL ====================
$pengirim = ($header['role_id']==1||$header['role_id']==10)
              ? 'Head Office'
              : formatMember($header['suppliar_name'],$header['role_id'],$header['suppliar_code']);
$penerima = formatMember($header['customer_name'],$header['customer_role'],$header['customer_code']);

$pdf->SetFont('Montserrat','B',11);
$pdf->Cell(95,7,'Order Dari',0,0);
$pdf->Cell(95,7,'Dikirim Ke',0,1);

$pdf->SetFont('Montserrat','',10);
$pdf->Cell(95,6,$pengirim,0,0);
$pdf->MultiCell(95,6,$penerima,0,1);
$pdf->Ln(4);

// ==================== TABEL PRODUK ====================
$pdf->SetFont('Montserrat','B',10);
$pdf->Cell(10,8,'No',1,0,'C');
$pdf->Cell(130,8,'Nama Produk',1,0,'C');
$pdf->Cell(30,8,'Qty',1,1,'C');

$pdf->SetFont('Montserrat','',9);
$no=1;
foreach($rows as $item){
    $pdf->Cell(10,8,$no++,1,0,'C');
    $pdf->Cell(130,8,$item['product_name'],1,0);
    $pdf->Cell(30,8,$item['quantity'],1,1,'C');
}

// ==================== TOTAL ====================
$pdf->SetFont('Montserrat','B',10);
$pdf->Cell(140,8,'TOTAL',1,0,'R');
$pdf->Cell(30,8,$totalQty,1,1,'C');
$pdf->Ln(6);

// ==================== ALAMAT PENGIRIMAN ====================
$pdf->SetFont('Montserrat','B',11);
$pdf->Cell(0,7,'Alamat Pengiriman',0,1);
$pdf->SetFont('Montserrat','',10);
$pdf->MultiCell(0,6,$header['customer_address'] ?? '-');

$pdf->Output('I','Invoice_'.$header['invoice_number'].'.pdf');
