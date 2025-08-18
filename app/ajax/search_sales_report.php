<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issueData = $_POST['issuedate'] ?? '';
    $suppliar_id = ($_SESSION['role_id'] == 1) || ($_SESSION['role_id'] == 10) ? ($_POST['suppliar_id'] ?? 'all') : $_SESSION['distributor_id'];
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = 10; // jumlah data per halaman
    $offset = ($page - 1) * $limit;

    // Pecah range tanggal, format yang diharapkan: "MM/DD/YYYY - MM/DD/YYYY"
    $data = explode('-', $issueData);
    $issu_first_date = isset($data[0]) ? $obj->convertDateMysql(trim($data[0])) : date('Y-m-d', strtotime('-30 days'));
    $issu_end_date = isset($data[1]) ? $obj->convertDateMysql(trim($data[1])) : date('Y-m-d');

    // Siapkan kondisi where dan params dinamis
    $whereClauses = [];
    $params = [];

    // Filter suppliar
    if ($suppliar_id !== 'all') {
        $whereClauses[] = "th.suppliar_id = :suppliar_id";
        $params[':suppliar_id'] = $suppliar_id;
    }

    // Filter tanggal
    $whereClauses[] = "th.created_at BETWEEN :start AND :end";
    $params[':start'] = $issu_first_date . ' 00:00:00';
    $params[':end'] = $issu_end_date . ' 23:59:59';

    // Gabungkan kondisi
    $whereSQL = count($whereClauses) > 0 ? "WHERE " . implode(' AND ', $whereClauses) : "";

    // Hitung total data dulu
    $countSql = "SELECT COUNT(*) AS total FROM transaction_histories th $whereSQL";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalData = $countStmt->fetchColumn();
    $totalPages = ceil($totalData / $limit);

    // Query data dengan limit offset
    $dataSql = "
        SELECT th.*, 
               s1.name AS suppliar_name, 
               s2.name AS customer_name
        FROM transaction_histories th
        LEFT JOIN suppliar s1 ON th.suppliar_id = s1.id
        LEFT JOIN suppliar s2 ON th.customer_id = s2.id
        $whereSQL
        ORDER BY th.created_at DESC
        LIMIT :offset, :limit
    ";

    $stmt = $pdo->prepare($dataSql);
    // Bind params dinamis dulu
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    // Bind limit & offset sebagai integer
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($res) {
        foreach ($res as $data) {
            $quantityDisplay = $data->quantity;
            if (strtolower($data->type) === 'refund') {
              $quantityDisplay = "<span style='color:red;'>-" . $data->quantity . "</span>";
            }
            $dateFormatted = date('d-m', strtotime($data->created_at));
            echo "<tr>
                <td>{$dateFormatted}</td>
                <td>{$data->invoice_number}</td>
                <td>{$data->type}</td>
                <td>".htmlspecialchars($data->customer_name ?? 'Penjualan Pribadi')."</td> 
                <td>{$quantityDisplay}</td>
                <td>".htmlspecialchars($data->note)."</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='text-align:center;'>No data found</td></tr>";
    }

    // Pagination controls
    echo '<tr><td colspan="6" style="text-align:center;">';
    echo '<nav><ul class="pagination justify-content-center" style="margin-top:15px;">';
    for ($p = 1; $p <= $totalPages; $p++) {
        $activeClass = ($p == $page) ? 'active' : '';
        echo '<li class="page-item ' . $activeClass . '">';
        echo '<a href="#" class="page-link" data-page="' . $p . '">' . $p . '</a>';
        echo '</li>';
    }
    echo '</ul></nav>';
    echo '</td></tr>';
}
