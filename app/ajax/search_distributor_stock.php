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

if (!$issuedate) {
    // fallback otomatis 30 hari
    $start_date = "";
    $end_date   = "";
} else {
    [$start_date, $end_date] = array_map('trim', explode('-', $issuedate));
    $start_date = DateTime::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
    $end_date   = DateTime::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');
}

// ambil anak suppliar
$childs = $obj->allQuery(
    "SELECT id, name, suppliar_code FROM suppliar WHERE parent_id = ?",
    [$parent_id]
);
if (!$childs) {
    echo '<tr><td colspan="4" class="no-data">Tidak ada anak distributor.</td></tr>';
    exit;
}
$child_ids = array_map(fn($c) => $c->id, $childs);
$ph = implode(',', array_fill(0, count($child_ids), '?'));

// query penjualan
$sql = "
SELECT s.id AS suppliar_id,
       s.suppliar_code,
       s.name AS distributor_name,
       p.product_name,
       COALESCE(SUM(t.quantity),0) AS total_quantity
FROM suppliar s
CROSS JOIN products p
LEFT JOIN transaction_histories t
       ON t.suppliar_id = s.id
      AND t.product_id = p.id
      AND t.type = 'penjualan'
      AND DATE(t.created_at) BETWEEN ? AND ?
WHERE s.id IN ($ph)
";
$params = [$start_date, $end_date, ...$child_ids];

if ($item_id !== '') {
    $sql .= " AND p.id = ? ";
    $params[] = $item_id;
}

$sql .= " GROUP BY s.id, s.suppliar_code, s.name, p.id, p.product_name
          ORDER BY s.name, p.product_name";

$rows = $obj->allQuery($sql, $params);

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
