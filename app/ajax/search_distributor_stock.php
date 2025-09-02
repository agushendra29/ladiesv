<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<tr><td colspan="7" class="no-data">Invalid request.</td></tr>';
    exit;
}

$issuedate = $_POST['issuedate'] ?? '';
$item_id   = $_POST['item_id'] ?? '';

if (!$issuedate) {
    echo '<tr><td colspan="7" class="no-data">Periode wajib dipilih.</td></tr>';
    exit;
}

// --- parsing periode ---
list($start_date, $end_date) = array_map('trim', explode('-', $issuedate));
$start_date = DateTime::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
$end_date   = DateTime::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');

// --- ambil anak suppliar ---
$parent_id = $_SESSION['distributor_id']; 
$child_suppliers = $obj->allQuery("SELECT id, name, suppliar_code FROM suppliar WHERE parent_id = ?", [$parent_id]);

if (!$child_suppliers) {
    echo '<tr><td colspan="7" class="no-data">Tidak ada anak suppliar.</td></tr>';
    exit;
}

$child_ids = array_map(fn($c) => $c->id, $child_suppliers);
$placeholders = implode(',', array_fill(0, count($child_ids), '?'));

// --- query ---
$sql = "
SELECT 
    s.id AS suppliar_id,
    s.suppliar_code,
    s.name AS distributor_name,
    p.id AS product_id,
    p.product_name,
    COALESCE(SUM(t.quantity), 0) AS total_quantity
FROM suppliar s
CROSS JOIN products p
LEFT JOIN transaction_histories t 
    ON t.suppliar_id = s.id
    AND t.product_id = p.id
    AND t.type = 'penjualan'
    AND DATE(t.created_at) BETWEEN ? AND ?
WHERE s.id IN ($placeholders)
";

// kalau ada filter produk, batasi di join
if (!empty($item_id)) {
    $sql .= " AND p.id = ? ";
}

$sql .= "
GROUP BY s.id, s.suppliar_code, s.name, p.id, p.product_name
ORDER BY s.name, p.product_name
";
// --- parameter ---
$params = [$start_date, $end_date];
$params = array_merge($params, $child_ids);

if (!empty($item_id)) {
    $params[] = $item_id;
}
$rows = $obj->allQuery($sql, $params);

// --- tampilkan ---
if ($rows) {
    foreach ($rows as $r) {
        echo '<tr>
            <td>'.htmlspecialchars($r->suppliar_code).'</td>
            <td>'.htmlspecialchars($r->distributor_name).'</td>
            <td>'.($r->product_name ?: '-').'</td>
            <td>'.number_format($r->total_quantity).'</td>
        </tr>';
    }
} else {
    echo '<tr><td colspan="7" class="no-data">Tidak ada data transaksi.</td></tr>';
}
