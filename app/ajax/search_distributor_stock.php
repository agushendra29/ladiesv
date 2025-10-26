<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<tr><td colspan="4" class="no-data">Invalid request.</td></tr>';
    exit;
}

$issuedate = trim($_POST['issuedate'] ?? '');
$item_id   = trim($_POST['item_id'] ?? '');
$parent_id = $_SESSION['distributor_id'] ?? 0;

if (!$parent_id) {
    echo '<tr><td colspan="4" class="no-data">User tidak valid.</td></tr>';
    exit;
}

// 1. Penanganan Tanggal
if (!$issuedate) {
    // Fallback date jika tidak ada issuedate (harus diatur ke tanggal minimal atau error handling yang sesuai)
    // Di sini kita gunakan tanggal yang sangat lama dan tanggal hari ini
    $start_date = "1970-01-01"; 
    $end_date   = date('Y-m-d');
} else {
    [$start_date_str, $end_date_str] = array_map('trim', explode('-', $issuedate));
    // Asumsi format d/m/Y
    $start_date = DateTime::createFromFormat('d/m/Y', $start_date_str)->format('Y-m-d');
    $end_date   = DateTime::createFromFormat('d/m/Y', $end_date_str)->format('Y-m-d');
}

// 2. Ambil Anak Aktif (Role 3 atau Role lainnya)
// Kita harus mengambil SEMUA anak aktif dari parent_id saat ini
$childs = $obj->allQuery(
    "SELECT id, name, suppliar_code FROM suppliar WHERE parent_id = ?",
    [$parent_id]
);
if (!$childs) {
    echo '<tr><td colspan="4" class="no-data">Tidak ada anak yang ditemukan.</td></tr>';
    exit;
}

$child_ids = array_map(fn($c) => $c->id, $childs);
$ph_child_ids = implode(',', array_fill(0, count($child_ids), '?'));

// 3. Query Penjualan dengan Cutoff Historis
$sql = "
SELECT s.id AS suppliar_id,
       s.suppliar_code,
       s.name AS distributor_name,
       p.product_name,
       COALESCE(SUM(t.quantity),0) AS total_quantity
FROM suppliar s
CROSS JOIN products p
-- JOIN untuk mendapatkan tanggal join suppliar s di bawah parent_id saat ini
LEFT JOIN (
    SELECT 
        suppliar_id, 
        MIN(created_at) AS join_date
    FROM distributor_management_history
    WHERE current_parent_id = ? 
    GROUP BY suppliar_id
) AS dmh ON dmh.suppliar_id = s.id

LEFT JOIN transaction_histories t
        ON t.suppliar_id = s.id
       AND t.product_id = p.id
       AND t.type = 'penjualan'
     AND t.is_refund = 0 
       AND DATE(t.created_at) BETWEEN ? AND ?
       -- *** LOGIC CUTOFF DI SINI ***
       -- Hanya hitung transaksi yang terjadi SETELAH tanggal join
       AND t.created_at >= COALESCE(dmh.join_date, '1900-01-01 00:00:00')

WHERE s.id IN ($ph_child_ids)
";

// Parameter untuk query
// Urutan: parent_id (untuk dmh), start_date, end_date, ...child_ids
$params = array_merge(
    [$parent_id],
    [$start_date, $end_date],
    $child_ids
);

if ($item_id !== '') {
    $sql .= " AND p.id = ? ";
    $params[] = $item_id;
}

$sql .= " GROUP BY s.id, s.suppliar_code, s.name, p.id, p.product_name
          ORDER BY s.name, p.product_name";

$rows = $obj->allQuery($sql, $params);

// 4. Output Hasil
if ($rows) {
    foreach ($rows as $r) {
        echo '<tr>
                <td>'.htmlspecialchars($r->suppliar_code).'</td>
                <td>'.htmlspecialchars($r->distributor_name).'</td>
                <td>'.htmlspecialchars($r->product_name).'</td>
                <td>'.number_format($r->total_quantity).'</td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="4" class="no-data">Tidak ada data transaksi.</td></tr>';
}