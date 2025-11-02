<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<tr><td colspan="4" class="no-data">Invalid request.</td></tr>';
    exit;
}

$issuedate = trim($_POST['issuedate'] ?? '');
$item_id   = trim($_POST['item_id'] ?? '');
$current_user_id = $_SESSION['distributor_id'] ?? 0;

if (!$current_user_id) {
    echo '<tr><td colspan="4" class="no-data">User tidak valid.</td></tr>';
    exit;
}

// 1️⃣ Format tanggal
if (!$issuedate) {
    $start_date = "1970-01-01";
    $end_date   = date('Y-m-d');
} else {
    [$start_date_str, $end_date_str] = array_map('trim', explode('-', $issuedate));
    $start_date = DateTime::createFromFormat('d/m/Y', $start_date_str)->format('Y-m-d');
    $end_date   = DateTime::createFromFormat('d/m/Y', $end_date_str)->format('Y-m-d');
}

// 2️⃣ Query transaksi reseller (role 5) yang parent-nya = current user
$sql = "
SELECT 
    s.suppliar_code,
    s.name AS reseller_name,
    p.product_name,
    COALESCE(SUM(t.quantity), 0) AS total_quantity
FROM transaction_histories t
JOIN suppliar s ON s.id = t.suppliar_id
JOIN products p ON p.id = t.product_id
WHERE 
    t.type = 'penjualan'
    AND t.is_refund = 0
    AND t.suppliar_role_id = 5
    AND t.suppliar_parent_id = ?
    AND DATE(t.created_at) BETWEEN ? AND ?
";

$params = [$current_user_id, $start_date, $end_date];

if ($item_id !== '') {
    $sql .= " AND t.product_id = ? ";
    $params[] = $item_id;
}

$sql .= "
GROUP BY s.suppliar_code, s.name, p.product_name
ORDER BY s.name, p.product_name
";

$rows = $obj->allQuery($sql, $params);

// 3️⃣ Output hasil
if ($rows) {
    foreach ($rows as $r) {
        echo '<tr>
                <td>' . htmlspecialchars($r->suppliar_code) . '</td>
                <td>' . htmlspecialchars($r->reseller_name) . '</td>
                <td>' . htmlspecialchars($r->product_name) . '</td>
                <td>' . number_format($r->total_quantity) . '</td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="4" class="no-data">Tidak ada data transaksi.</td></tr>';
}
?>
