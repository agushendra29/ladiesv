<?php
require_once '../init.php'; // sesuaikan path

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
    // Fallback diset ke string kosong (asumsi query akan gagal atau default)
    $start_date = "1970-01-01"; // Default date minimal untuk menghindari error query
    $end_date   = date('Y-m-d');
} else {
    [$start_date_str, $end_date_str] = array_map('trim', explode('-', $issuedate));
    // Asumsi format d/m/Y
    $start_date = DateTime::createFromFormat('d/m/Y', $start_date_str)->format('Y-m-d');
    $end_date   = DateTime::createFromFormat('d/m/Y', $end_date_str)->format('Y-m-d');
}

// 2. Mengambil ID Reseller (Anak Aktif dan Mantan Anak)

// A. Reseller/Anak Aktif (Role 5 dan Parent saat ini = $parent_id)
$childs = $obj->allQuery(
    "SELECT id, name, suppliar_code FROM suppliar WHERE parent_id = ? AND role_id = 5",
    [$parent_id]
);
$active_ids = array_map(fn($c) => $c->id, $childs);

// B. Mantan Reseller (Role 5 -> Role Lain, dan Parent sebelum upgrade = $parent_id)
$former_query = "
    SELECT lh.suppliar_id AS id, s.name, s.suppliar_code, lh.created_at AS upgrade_date
    FROM levelup_history lh
    JOIN suppliar s ON s.id = lh.suppliar_id
    WHERE lh.parent_id_before = ? 
    AND lh.role_from = 5 
    AND lh.role_to <> 5
";
// Gabungkan data mantan reseller untuk mendapatkan tanggal upgrade mereka
$former_children_data = $obj->allQuery($former_query, [$parent_id]);

$combined_suppliars = [];
$upgrade_cutoffs = [];

// Tambahkan Anak Aktif
foreach ($childs as $c) {
    if (!isset($combined_suppliars[$c->id])) {
        $combined_suppliars[$c->id] = $c;
    }
}

// Tambahkan Mantan Anak dan simpan tanggal cutoff (hanya yang tidak aktif lagi)
foreach ($former_children_data as $fc) {
    // Hanya tambahkan jika ID tidak ada di list aktif (untuk menghindari duplikasi)
    if (!in_array($fc->id, $active_ids) && !isset($combined_suppliars[$fc->id])) {
        $combined_suppliars[$fc->id] = $fc;
        $upgrade_cutoffs[$fc->id] = $fc->upgrade_date;
    }
}

if (!$combined_suppliars) {
    echo '<tr><td colspan="4" class="no-data">Tidak ada anak Reseller yang relevan pada periode ini.</td></tr>';
    exit;
}

$suppliar_ids = array_keys($combined_suppliars);
$ph = implode(',', array_fill(0, count($suppliar_ids), '?'));

// 3. Query Penjualan (Memperkenalkan Cutoff Logik)

// Kita menggunakan subquery untuk membawa tanggal cutoff ke dalam join
$sql = "
SELECT s.id AS suppliar_id,
       s.suppliar_code,
       s.name AS distributor_name,
       p.product_name,
       COALESCE(SUM(t.quantity),0) AS total_quantity
FROM (
    SELECT id, name, suppliar_code FROM suppliar WHERE id IN ($ph)
) s
CROSS JOIN products p
LEFT JOIN transaction_histories t
        ON t.suppliar_id = s.id
       AND t.product_id = p.id
       AND t.type = 'penjualan'
       AND t.is_refund = 0
       AND DATE(t.created_at) BETWEEN ? AND ?
LEFT JOIN (
    -- Subquery untuk mendapatkan tanggal upgrade Mantan Reseller
    SELECT suppliar_id, created_at AS upgrade_date
    FROM levelup_history 
    WHERE role_from = 5 AND role_to <> 5
) lh ON lh.suppliar_id = s.id

WHERE s.id IN ($ph)
-- Terapkan Cutoff Historis: Hanya hitung penjualan sebelum tanggal upgrade
AND (
    lh.upgrade_date IS NULL  -- Selalu hitung untuk Reseller Aktif (tidak ada tanggal upgrade)
    OR t.created_at < lh.upgrade_date -- Hentikan hitungan setelah tanggal upgrade
)
";

$params = array_merge(
    $suppliar_ids, // untuk subquery 's'
    [$start_date, $end_date],
    $suppliar_ids // untuk WHERE clause luar
);

if ($item_id !== '') {
    $sql .= " AND p.id = ? ";
    $params[] = $item_id;
}

$sql .= " GROUP BY s.id, s.suppliar_code, s.name, p.id, p.product_name
          ORDER BY s.name, p.product_name";

$rows = $obj->allQuery($sql, $params);

// 4. Output Hasil (sama seperti sebelumnya)
if ($rows) {
    // ... (logic output HTML Anda tetap di sini)
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