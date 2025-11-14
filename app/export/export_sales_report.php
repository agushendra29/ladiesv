<?php
require_once '../init.php';
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// ------------------------------
// AMBIL PARAMETER FILTER
// ------------------------------
$issueData   = $_GET['issuedate'] ?? '';
$suppliar_id = ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10)
    ? ($_GET['suppliar_id'] ?? 'all')
    : ($_SESSION['distributor_id'] ?? 0);
$product_id  = $_GET['product_id'] ?? 'all';
$type        = $_GET['type'] ?? 'all';

$data = explode('-', $issueData);
$issu_first_date = isset($data[0]) ? $obj->convertDateMysql(trim($data[0])) : date('Y-m-d', strtotime('-30 days'));
$issu_end_date   = isset($data[1]) ? $obj->convertDateMysql(trim($data[1]))   : date('Y-m-d');

// ------------------------------
// KONDISI FILTER
// ------------------------------
$where = [];
$params = [];

if ($suppliar_id !== 'all') {
    $where[] = "(th.suppliar_id = :suppliar_id OR th.customer_id = :suppliar_id)";
    $params[':suppliar_id'] = $suppliar_id;
}
if ($product_id !== 'all') {
    $where[] = "th.product_id = :product_id";
    $params[':product_id'] = $product_id;
}
$where[] = "th.created_at BETWEEN :start AND :end";
$params[':start'] = $issu_first_date . ' 00:00:00';
$params[':end']   = $issu_end_date   . ' 23:59:59';
$whereSQL = $where ? 'WHERE '.implode(' AND ', $where) : '';

// ------------------------------
// QUERY DATA
// ------------------------------
$sql = "SELECT th.*, 
               s1.name AS suppliar_name, s1.suppliar_code AS suppliar_code, s1.role_id AS sup_role,
               s2.name AS customer_name, s2.suppliar_code AS customer_code, s2.role_id AS cust_role,
               p.product_name
        FROM transaction_histories th
        LEFT JOIN suppliar s1 ON th.suppliar_id = s1.id
        LEFT JOIN suppliar s2 ON th.customer_id = s2.id
        LEFT JOIN products p ON th.product_id = p.id
        $whereSQL
        ORDER BY th.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ------------------------------
// BUAT FILE EXCEL
// ------------------------------
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Sales Report');

// Header Kolom
$headers = [
    'Tanggal', 'Invoice Number', 'Tipe Transaksi', 
    'Dari', 'Kepada', 'Kuantitas', 'Produk', 'Keterangan'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $sheet->getStyle($col . '1')->getFont()->setBold(true)->setSize(9);
    $sheet->getColumnDimension($col)->setAutoSize(true);
    $sheet->getStyle($col . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $col++;
}

// ------------------------------
// ISI DATA
// ------------------------------
$rowNum = 2;
foreach ($rows as $r) {
    $transType = ucfirst($r['type']);

    // Tentukan partner transaksi
    if ($r['type'] === 'penjualan' || $r['type'] === 'pembelian') {
        if (in_array((int)$r['cust_role'], [1, 10])) {
            $partnerName = 'Head Office';
            $partnerCode = '';
        } else {
            $partnerName = $r['customer_name'] ?: 'Penjualan Pribadi';
            $partnerCode = $r['customer_code'] ?: '';
        }
    } else {
        $partnerName = $r['customer_name'] ?: '-';
        $partnerCode = $r['customer_code'] ?: '';
    }

    $dari = trim($r['suppliar_name'] . ($r['suppliar_code'] ? ' - ' . $r['suppliar_code'] : ''));
    $kepada = trim($partnerName . ($partnerCode ? ' - ' . $partnerCode : ''));
    $qty = strtolower($r['type']) === 'refund' ? -$r['quantity'] : $r['quantity'];

    $sheet->setCellValue('A' . $rowNum, date('d-m-Y', strtotime($r['created_at'])));
    $sheet->setCellValue('B' . $rowNum, $r['invoice_number']);
    $sheet->setCellValue('C' . $rowNum, $transType);
    $sheet->setCellValue('D' . $rowNum, $dari);
    $sheet->setCellValue('E' . $rowNum, $kepada);
    $sheet->setCellValue('F' . $rowNum, $qty);
    $sheet->setCellValue('G' . $rowNum, $r['product_name']);
    $sheet->setCellValue('H' . $rowNum, $r['note']);

    $rowNum++;
}

// ------------------------------
// STYLE UMUM
// ------------------------------
$styleArray = [
    'font' => ['size' => 9],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'CCCCCC']
        ]
    ],
    'alignment' => [
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
    ]
];
$sheet->getStyle('A1:H' . ($rowNum - 1))->applyFromArray($styleArray);
$sheet->getDefaultRowDimension()->setRowHeight(17);

// ------------------------------
// OUTPUT FILE
// ------------------------------
$filename = "sales_report_{$issu_first_date}_to_{$issu_end_date}.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
