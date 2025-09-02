<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issueData   = $_POST['issuedate'] ?? '';
    $suppliar_id = ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10) 
                    ? ($_POST['suppliar_id'] ?? 'all') 
                    : $_SESSION['distributor_id'];

    $type       = $_POST['type'] ?? 'all';

    $page   = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit  = 10;
    $offset = ($page - 1) * $limit;

    // ========== Date Filter ==========
    $data = explode('-', $issueData);
    $issu_first_date = isset($data[0]) ? $obj->convertDateMysql(trim($data[0])) : date('Y-m-d', strtotime('-30 days'));
    $issu_end_date   = isset($data[1]) ? $obj->convertDateMysql(trim($data[1]))   : date('Y-m-d');

    // ========== Build Filters ==========
    $whereClauses = [];
    $params       = [];

    if ($suppliar_id !== 'all') {
        $whereClauses[] = "(th.suppliar_id = :suppliar_id OR th.customer_id = :suppliar_id)";
        $params[':suppliar_id'] = $suppliar_id;
    }

    if($type !== 'all') {
        $whereClauses[] = "(LOWER(th.type) = LOWER(:type))";
        $params[':type'] = $type;
    }

    $whereClauses[] = "th.invoice_number != '-'";
    $whereClauses[] = "th.created_at BETWEEN :start AND :end";
    $params[':start'] = $issu_first_date . ' 00:00:00';
    $params[':end']   = $issu_end_date   . ' 23:59:59';

    $whereSQL = count($whereClauses) > 0 ? "WHERE " . implode(' AND ', $whereClauses) : "";

    // ========== Hitung Total ==========
    $countSql  = "SELECT COUNT(DISTINCT th.invoice_number) FROM transaction_histories th $whereSQL";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalData  = $countStmt->fetchColumn();
    $totalPages = ceil($totalData / $limit);

    // ========== Ambil Data ==========
   $dataSql = "
    SELECT th.invoice_number,
           th.type,
           th.created_at,
           th.suppliar_id,
           th.customer_id,
           th.is_refund,
           th.tanggal_refund,
           s1.name AS suppliar_name,
           s2.name AS customer_name,
           s2.suppliar_code AS customer_code,
           GROUP_CONCAT(CONCAT(p.product_name, '; (qty: ', 
                 CASE 
                    WHEN th.type = 'refund' 
                    THEN CONCAT('-', th.quantity) 
                    ELSE th.quantity 
                 END, 
           ')') SEPARATOR '<br>') AS products,
           GROUP_CONCAT(th.note SEPARATOR ' | ') AS notes
    FROM transaction_histories th
    LEFT JOIN suppliar s1 ON th.suppliar_id = s1.id
    LEFT JOIN suppliar s2 ON th.customer_id = s2.id
    LEFT JOIN products p  ON th.product_id = p.id
    $whereSQL
    GROUP BY th.invoice_number
    ORDER BY th.created_at DESC
    LIMIT :offset, :limit
";


    $stmt = $pdo->prepare($dataSql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    // ========== Output ==========
    if ($res) {
        foreach ($res as $data) {
            $dateFormatted = date('d-m', strtotime($data->created_at));
            $customer = !empty($data->customer_name) 
                        ? htmlspecialchars($data->customer_name . " - " . $data->customer_code) 
                        : "Penjualan Pribadi";

            echo "<tr>
                <td>{$dateFormatted}</td>
                <td>{$data->invoice_number}</td>
                <td>{$data->type}</td>
                <td>{$customer}</td>
                <td>{$data->products}</td>
                <td>" . htmlspecialchars($data->notes) . "</td>
                <td>";

            // === tombol refund / cancel refund ===
            if ($data->is_refund == 1) {
                echo "<button class='cancel-refund-btn' 
                            style='background:orange;color:white;border:solid 1px orange;border-radius:5px;'
                            data-invoice='{$data->invoice_number}'>
                        Cancel Refund
                      </button>";
            } else {
                echo "<button class='refund-btn' 
                            style='background:red;color:white;border:solid 1px red;border-radius:5px;'
                            data-invoice='{$data->invoice_number}'>
                        Refund
                      </button>";
            }

            echo "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='7' style='text-align:center;'>No data found</td></tr>";
    }

    // ========== Pagination ==========
    echo '<tr><td colspan="7" style="text-align:center;">';
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
