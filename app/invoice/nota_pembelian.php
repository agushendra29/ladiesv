<?php
require_once '../init.php';
require_once 'fpdf/fpdf.php';

if (!isset($_GET['id'])) die("Invoice number tidak ditemukan");
$invoice_number = $_GET['id'];

/* ==================== AMBIL DATA ==================== */
$stmt = $pdo->prepare("
    SELECT th.invoice_number, th.type, th.created_at, th.payment_type, th.bank_type,
           s1.name AS suppliar_name, s1.role_id AS role_id, s1.suppliar_code AS suppliar_code,
           s2.name AS customer_name, s2.role_id AS customer_role, s2.suppliar_code AS customer_code,
           s2.address AS customer_address,
           s2.provinsi, s2.kota, s2.kecamatan, s2.con_num,
           p.product_name, p.sell_price_hd, p.sell_price_d, p.sell_price_a, p.sell_price_r,
           th.quantity
    FROM transaction_histories th
    LEFT JOIN products p   ON th.product_id   = p.id
    LEFT JOIN suppliar s1  ON th.suppliar_id  = s1.id
    LEFT JOIN suppliar s2  ON th.customer_id  = s2.id
    WHERE th.invoice_number = :invoice
      AND th.type = 'pembelian'
    ORDER BY th.id ASC
");
$stmt->bindValue(':invoice', $invoice_number, PDO::PARAM_STR);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$rows) die("Data invoice tidak ditemukan");

$header = $rows[0];

/* ==================== Ambil nama provinsi, kota, kecamatan ==================== */
function getWilayahName($pdo,$table,$id){
    if(!$id) return '-';
    $q = $pdo->prepare("SELECT name FROM {$table} WHERE id = :id LIMIT 1");
    $q->execute([':id'=>$id]);
    $r = $q->fetch(PDO::FETCH_ASSOC);
    return $r['name'] ?? '-';
}
$provinceName = getWilayahName($pdo,'provinsi',$header['provinsi']);
$regencyName  = getWilayahName($pdo,'kota',$header['kota']);
$districtName = getWilayahName($pdo,'kecamatan',$header['kecamatan']);

/* ==================== Fungsi bantu ==================== */
function formatMember($name,$role_id,$code){
    $prefix = ($role_id >= 2 && $role_id <= 5) ? 'D' : '';
    return $name.' ('.$prefix.'-'.str_pad($code ?? '000000',6,'0',STR_PAD_LEFT).')';
}
function getPriceByRole($row) {
    switch ($row['customer_role']) {
        case 2: return $row['sell_price_hd'];
        case 3: return $row['sell_price_d'];
        case 4: return $row['sell_price_a'];
        case 5: return $row['sell_price_r'];
        default: return 0;
    }
}

/* ==================== PDF ==================== */
$pdf = new FPDF('L','mm','A5'); // Landscape
$pdf->AddFont('Montserrat','','Montserrat-Regular.php');
$pdf->AddFont('Montserrat','B','Montserrat-Bold.php');
$pdf->AddPage();

/* --- Border Luar Hitam --- */
$borderMargin = 6;
$pdf->SetLineWidth(0.1);
$pdf->SetDrawColor(0,0,0); // hitam
$pdf->Rect(
    $borderMargin,
    $borderMargin,
    210 - 2 * $borderMargin, // lebar A5
    148 - 2 * $borderMargin  // tinggi A5
);

/* --- Padding dalam border --- */
$padding = 6;
$startX = $borderMargin + $padding;
$startY = $borderMargin + $padding;
$pdf->SetXY($startX, $startY);
$contentWidth = 210 - 2*($borderMargin + $padding);

/* --- Logo + Header --- */
$pdf->Image('logo.jpeg',$startX,$startY,35);
$pdf->SetXY($startX+90,$startY);
$pdf->SetFont('Montserrat','B',12);
$pdf->Cell($contentWidth-90,6,'INVOICE',0,2,'R');

$pdf->SetFont('Montserrat','',9);
$pdf->Cell($contentWidth-90,5,'No: '.$header['invoice_number'],0,2,'R');
$pdf->Cell($contentWidth-90,5,'Tanggal: '.date('d-m-Y H:i',strtotime($header['created_at'])),0,2,'R');
$pdf->Cell($contentWidth-90,5,'Pembayaran: '.(($header['payment_type'] ?: '-') . ' ' . ($header['bank_type'] ?: '')),0,2,'R');
$pdf->Ln(8);

/* ==================== INFO ORDER ==================== */
$pengirim = ($header['role_id']==1||$header['role_id']==10)
    ? 'Head Office'
    : formatMember($header['suppliar_name'],$header['role_id'],$header['suppliar_code']);
$penerima = formatMember($header['customer_name'],$header['customer_role'],$header['customer_code']);

$labelW = 35;
$valueW = $contentWidth - $labelW;
$rowH = 5;

/* --- Pengirim --- */
$pdf->SetFont('Montserrat','B',10);
$pdf->Cell($contentWidth,$rowH,'Pengirim',0,1,'L');
$pdf->SetFont('Montserrat','',9);
$pdf->Cell($labelW,$rowH,"Nama:",0,0,'L');
$pdf->Cell($valueW,$rowH,$pengirim,0,1,'L');
$pdf->Ln(2);

/* --- Penerima --- */
$pdf->SetFont('Montserrat','B',10);
$pdf->Cell($contentWidth,$rowH,'Penerima',0,1,'L');
$pdf->SetFont('Montserrat','',9);
$pdf->Cell($labelW,$rowH,"Nama:",0,0,'L');
$pdf->Cell($valueW,$rowH,$penerima,0,1,'L');
$pdf->Cell($labelW,$rowH,"Kota:",0,0,'L');
$pdf->Cell($valueW,$rowH,ucwords(strtolower($regencyName)),0,1,'L');
$pdf->Cell($labelW,$rowH,"No.Handphone:",0,0,'L');
$pdf->Cell($valueW,$rowH,$header['con_num'],0,1,'L');
$pdf->Ln(4);

/* ==================== TABEL PRODUK ==================== */
$colNo     = 15;
$colNama   = 75;
$colQty    = 20;
$colHarga  = 40;
$colSubtot = 40;

/* Header Table */
$pdf->SetLineWidth(0.2);
$pdf->SetDrawColor(0,0,0);
$pdf->SetFont('Montserrat','B',10);
$pdf->SetFillColor(220,220,220);
$pdf->Cell($colNo,6,'No',1,0,'C',true);
$pdf->Cell($colNama,6,'Nama Produk',1,0,'C',true);
$pdf->Cell($colQty,6,'Qty',1,0,'C',true);
$pdf->Cell($colHarga,6,'Harga',1,0,'C',true);
$pdf->Cell($colSubtot,6,'Subtotal',1,1,'C',true);

/* Content Table */
$pdf->SetFont('Montserrat','',9);
$totalQty = 0;
$grandTotal = 0;
$no = 1;
foreach($rows as $item){
    $price = getPriceByRole($item);
    $subtotal = $price * $item['quantity'];
    $totalQty += $item['quantity'];
    $grandTotal += $subtotal;

    $pdf->Cell($colNo,6,$no++,1,0,'C');
    $pdf->Cell($colNama,6,$item['product_name'],1,0,'L');
    $pdf->Cell($colQty,6,$item['quantity'],1,0,'C');
    $pdf->Cell($colHarga,6,number_format($price,0,',','.'),1,0,'R');
    $pdf->Cell($colSubtot,6,number_format($subtotal,0,',','.'),1,1,'R');
}

/* Total */
$pdf->SetFont('Montserrat','B',10);
$pdf->SetFillColor(230,230,230);
$pdf->Cell($colNo+$colNama,6,'TOTAL QTY',1,0,'R',true);
$pdf->Cell($colQty,6,$totalQty,1,0,'C',true);
$pdf->Cell($colHarga,6,'TOTAL',1,0,'R',true);
$pdf->Cell($colSubtot,6,number_format($grandTotal,0,',','.'),1,1,'R',true);
$pdf->Ln(5);

/* ==================== ALAMAT PENGIRIMAN ==================== */
$pdf->SetFont('Montserrat','B',10);
$pdf->Cell($contentWidth,6,'Alamat Pengiriman',0,1);
$pdf->SetFont('Montserrat','',9);
$alamatLengkap = trim(
    (ucwords($header['customer_address']) ?: '-') . ', ' . 
    ucwords(strtolower($districtName)) . ', ' . 
    ucwords(strtolower($regencyName)) . ', ' . 
    ucwords(strtolower($provinceName))
);
$alamatWidth = $contentWidth * 0.4; // lebar MultiCell 70% dari content
$pdf->MultiCell($alamatWidth,5,$alamatLengkap);

$pdf->Output('I','Invoice_'.$header['invoice_number'].'.pdf');
?>
