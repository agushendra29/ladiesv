<?php
require_once '../init.php'; // sesuaikan path

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<tr><td colspan="8" class="no-data">Invalid request.</td></tr>';
    exit;
}

function parse_date_to_mysql($d) {
    $d = trim($d);
    if (!$d) return date('Y-m-d');

    // jika ada slash
    if (strpos($d, '/') !== false) {
        $parts = explode('/', $d);
        if (count($parts) === 3) {
            $a = (int)$parts[0];
            $b = (int)$parts[1];
            $y = $parts[2];

            // jika hari > 12 maka formatnya DD/MM/YYYY
            if ($a > 12) {
                $day = $a; $month = $b;
            } else {
                // jika b > 12 kebalik
                if ($b > 12) {
                    $day = $b; $month = $a;
                } else {
                    // tidak pasti — anggap format DD/MM/YYYY (umumnya di ID)
                    $day = $parts[0]; $month = $parts[1];
                }
            }
            $ts = strtotime(sprintf('%04d-%02d-%02d', $y, $month, $day));
            return $ts ? date('Y-m-d', $ts) : date('Y-m-d');
        }
    }

    // fallback ke strtotime
    $ts = strtotime($d);
    return $ts ? date('Y-m-d', $ts) : date('Y-m-d');
}

// Fungsi parse tanggal tetap sama (tidak diubah)

$issueData = $_POST['issuedate'] ?? '';
if (trim($issueData) === '') {
    $issu_first_date = date('Y-m-d', strtotime('-29 days'));
    $issu_end_date = date('Y-m-d');
} else {
    $parts = explode('-', $issueData);
    $left = isset($parts[0]) ? trim($parts[0]) : '';
    $right = isset($parts[1]) ? trim($parts[1]) : $left;
    $issu_first_date = parse_date_to_mysql($left);
    $issu_end_date   = parse_date_to_mysql($right);
}

if ($_SESSION['role_id'] == 1) {
    $suppliar_id = $_POST['suppliar_id'] ?? 'all';
} else {
    $suppliar_id = $_SESSION['distributor_id'];
}

// Query tanpa GROUP BY, mengambil tiap row transaksi satu-satu
$sql = "
SELECT 
  DATE(th.created_at) AS transaksi_date,
  th.type,
  th.product_id,
  p.product_name,
  s.name AS supplier_name,
  th.quantity,
  th.created_at
FROM transaction_histories th
LEFT JOIN products p ON th.product_id = p.id
LEFT JOIN suppliar s ON th.suppliar_id = s.id
WHERE DATE(th.created_at) BETWEEN :start AND :end
AND (
    :suppliar_id = 'all' OR
    (
      (th.type = 'pembelian' AND th.customer_id = :suppliar_id) OR
      (th.type = 'penjualan' AND th.suppliar_id = :suppliar_id)
    )
)
ORDER BY th.created_at ASC, p.product_name ASC, th.type ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':start' => $issu_first_date,
    ':end' => $issu_end_date,
    ':suppliar_id' => $suppliar_id
]);

$rows = $stmt->fetchAll(PDO::FETCH_OBJ);

if (!$rows) {
    echo '<tr><td colspan="8" class="no-data">No stock data found</td></tr>';
    exit;
}

$totalPembelian = 0;
$totalPenjualan = 0;
$totalRefund = 0;

foreach ($rows as $r) {
    $transaksi_date = $r->transaksi_date ?? '-';
    $last_update = $r->created_at ? date('d-m-Y H:i', strtotime($r->created_at)) : '-';

    // Set jumlah per tipe transaksi
    $pembelian = $r->type === 'pembelian' ? intval($r->quantity) : 0;
    $penjualan = $r->type === 'penjualan' ? intval($r->quantity) : 0;
    $refund = $r->type === 'refund' ? intval($r->quantity) : 0;

    echo "<tr>
            <td>" . htmlspecialchars($r->product_name) . "</td>
            <td>" . $pembelian . "</td>
            <td>" . $penjualan . "</td>
            <td>" . $refund . "</td>
            <td>" . $last_update . "</td>
          </tr>";

    $totalPembelian += $pembelian;
    $totalPenjualan += $penjualan;
    $totalRefund += $refund;
}

echo "<tr style='font-weight:bold; background-color:#f0f0f0;'>
        <td>Total</td>
        <td>{$totalPembelian}</td>
        <td>{$totalPenjualan}</td>
        <td>{$totalRefund}</td>
        <td>-</td>
      </tr>";
?>
