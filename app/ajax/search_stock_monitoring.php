<?php
require_once '../init.php'; // sesuaikan path

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<tr><td colspan="8" class="no-data">Invalid request.</td></tr>';
    exit;
}

// helper parse tanggal (mendukung DD/MM/YYYY atau MM/DD/YYYY)
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

$issueData = $_POST['issuedate'] ?? '';
// fallback: jika kosong gunakan last 30 hari
if (trim($issueData) === '') {
    $issu_first_date = date('Y-m-d', strtotime('-29 days'));
    $issu_end_date = date('Y-m-d');
} else {
    // explode by '-' (server-side trim like di contoh kamu)
    $parts = explode('-', $issueData);
    $left = isset($parts[0]) ? trim($parts[0]) : '';
    $right = isset($parts[1]) ? trim($parts[1]) : $left;

    // parse (support DD/MM/YYYY and MM/DD/YYYY)
    $issu_first_date = parse_date_to_mysql($left);
    $issu_end_date   = parse_date_to_mysql($right);
}

// suppliar_id: 'all' or numeric
if ($_SESSION['role_id'] == 1) {
    $suppliar_id = $_POST['suppliar_id'] ?? 'all';
} else {
    $suppliar_id = $_SESSION['distributor_id'];
}

// Build query: gunakan jenis transaksi sesuai db: 'pembelian','penjualan','refund'
$sqlBase = "
    SELECT 
      th.product_id,
      COALESCE(p.product_name, p.product_name, p.product_name) AS product_name,
      s.name AS supplier_name,
      SUM(CASE WHEN th.type = 'pembelian' THEN th.quantity ELSE 0 END) AS total_pembelian,
      SUM(CASE WHEN th.type = 'penjualan' THEN th.quantity ELSE 0 END) AS total_penjualan,
      SUM(CASE WHEN th.type = 'refund' THEN th.quantity ELSE 0 END) AS total_refund,
      MAX(th.created_at) AS last_update
    FROM transaction_histories th
    LEFT JOIN products p ON th.product_id = p.id
    LEFT JOIN suppliar s ON th.suppliar_id = s.id
    WHERE DATE(th.created_at) BETWEEN :start AND :end
";

$params = [
    ':start' => $issu_first_date,
    ':end'   => $issu_end_date
];

if ($suppliar_id !== 'all' && $suppliar_id !== '') {
    $sqlBase .= " AND th.suppliar_id = :suppliar_id ";
    $params[':suppliar_id'] = $suppliar_id;
}

$sqlBase .= " GROUP BY th.product_id ORDER BY COALESCE(p.product_name, '') ASC";

$stmt = $pdo->prepare($sqlBase);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_OBJ);

if (!$rows) {
    echo '<tr><td colspan="8" class="no-data">No stock data found</td></tr>';
    exit;
}

foreach ($rows as $r) {
    $pembelian = (int)$r->total_pembelian;
    $penjualan = (int)$r->total_penjualan;
    $refund    = (int)$r->total_refund;
    $jumlah    = $pembelian - $penjualan + $refund;
    $last      = $r->last_update ? date('d-m-Y H:i', strtotime($r->last_update)) : '-';

    echo "<tr>
            <td>".htmlspecialchars($r->product_id)."</td>
            <td>".htmlspecialchars($r->product_name)."</td>
            <td>".htmlspecialchars($r->supplier_name)."</td>
            <td>{$pembelian}</td>
            <td>{$penjualan}</td>
            <td>{$refund}</td>
            <td>{$jumlah}</td>
            <td>{$last}</td>
          </tr>";
}
