<?php
require_once '../init.php'; // sesuaikan path

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<tr><td colspan="7" class="no-data">Invalid request.</td></tr>';
    exit;
}

$issuedate = $_POST['issuedate'] ?? '';

if (!$issuedate) {
    echo '<tr><td colspan="7" class="no-data">Periode wajib dipilih.</td></tr>';
    exit;
}

// --- parsing periode ---
list($start_date, $end_date) = array_map('trim', explode('-', $issuedate));
$start_date = DateTime::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
$end_date   = DateTime::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');

// --- cari semua suppliar anak dari user yang login ---
$parent_id = $_SESSION['distributor_id']; 
$child_suppliers = $obj->allQuery("SELECT id, name, suppliar_code FROM suppliar WHERE parent_id = ?", [$parent_id]);

if (!$child_suppliers) {
    echo '<tr><td colspan="7" class="no-data">Tidak ada anak suppliar.</td></tr>';
    exit;
}

$child_ids = array_map(fn($c) => $c->id, $child_suppliers);
$placeholders = implode(',', array_fill(0, count($child_ids), '?'));

$params = array_merge($child_ids, [$start_date, $end_date]);

// --- ambil transaksi (LEFT JOIN biar suppliar tanpa transaksi tetap keluar) ---
$sql = "
    SELECT 
        s.id AS suppliar_id,
        s.suppliar_code,
        s.name AS distributor_name,
        p.id AS product_id,
        p.product_name,
        SUM(t.quantity) AS total_quantity
    FROM suppliar s
    LEFT JOIN transaction_histories t 
        ON t.suppliar_id = s.id
        AND t.type = 'penjualan'
        AND DATE(t.created_at) BETWEEN ? AND ?
    LEFT JOIN products p ON t.product_id = p.id
    WHERE s.id IN ($placeholders)
    GROUP BY s.id, p.id
    ORDER BY s.name, p.product_name
";

// params: tanggal dulu, baru ids
$params = array_merge([$start_date, $end_date], $child_ids);
$rows = $obj->allQuery($sql, $params);

// --- susun hasil ---
$grouped = [];
foreach ($child_suppliers as $sup) {
    $grouped[$sup->id] = [
        'suppliar_code'   => $sup->suppliar_code,
        'distributor_name'=> $sup->name,
        'products'        => [],
        'total'           => 0
    ];
}

if ($rows) {
    foreach ($rows as $r) {
        if ($r->product_id) { // kalau ada produk
            $grouped[$r->suppliar_id]['products'][] = $r->product_name . ": " . number_format($r->total_quantity);
            $grouped[$r->suppliar_id]['total'] += $r->total_quantity;
        }
    }
}

// --- tampilkan hasil ---
$periode = date("d/m/Y", strtotime($start_date)) . " - " . date("d/m/Y", strtotime($end_date));

foreach ($grouped as $sup) {
    echo '<tr>
        <td>'.htmlspecialchars($sup['suppliar_code']).'</td>
        <td>'.htmlspecialchars($sup['distributor_name']).'</td>
       <td>'.(!empty($sup['products']) ? implode('<br>', $sup['products']) : '-').'</td>
        <td>'.number_format($sup['total']).'</td>
    </tr>';
}
