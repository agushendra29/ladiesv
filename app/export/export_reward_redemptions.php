<?php
require_once '../init.php';
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$start_date = $_GET['start'] ?? '';
$end_date   = $_GET['end'] ?? '';

if (!$start_date || !$end_date) {
    die("Start date dan End date wajib diisi.");
}

try {
    // Query dengan JOIN ke tabel suppliar dan rewards
    $stmt = $pdo->prepare("
        SELECT 
            rr.id,
            r.nama_reward AS reward_name,
            s.suppliar_code AS user_code,
            s.name AS name,
            rr.event_name,
            rr.qty_redeem,
            rr.point_per_unit,
            rr.total_point,
            rr.status,
            rr.note,
            rr.created_at
        FROM reward_redemptions rr
        LEFT JOIN rewards r ON rr.reward_id = r.id
        LEFT JOIN suppliar s ON rr.user_id = s.id
        WHERE DATE(rr.created_at) BETWEEN :start_date AND :end_date
        ORDER BY rr.created_at DESC
    ");
    $stmt->execute([
        ':start_date' => $start_date,
        ':end_date'   => $end_date
    ]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Judul kolom
    $headers = [
        'ID', 'Reward Name', 'Supplier', 'Event Name',
        'Qty Redeem', 'Point / Unit', 'Total Point',
        'Status', 'Note', 'Created At'
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $sheet->getStyle($col . '1')->getFont()->setBold(true);
        $sheet->getColumnDimension($col)->setAutoSize(true);
        $col++;
    }

    // Isi data
    $row = 2;
    foreach ($data as $item) {
        $sheet->setCellValue('A' . $row, $item['id']);
        $sheet->setCellValue('B' . $row, $item['reward_name']);
        $sheet->setCellValue('C' . $row, $item['user_code'] . ' - ' . $item['name']);
        $sheet->setCellValue('D' . $row, $item['event_name']);
        $sheet->setCellValue('E' . $row, $item['qty_redeem']);
        $sheet->setCellValue('F' . $row, $item['point_per_unit']);
        $sheet->setCellValue('G' . $row, $item['total_point']);
        $sheet->setCellValue('H' . $row, ucfirst($item['status']));
        $sheet->setCellValue('I' . $row, $item['note']);
        $sheet->setCellValue('J' . $row, $item['created_at']);
        $row++;
    }

    $filename = "reward_redemptions_{$start_date}_to_{$end_date}.xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}
