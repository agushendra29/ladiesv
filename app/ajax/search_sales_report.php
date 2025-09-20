<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issueData   = $_POST['issuedate'] ?? '';
    $suppliar_id = ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10) 
                    ? ($_POST['suppliar_id'] ?? 'all') 
                    : $_SESSION['distributor_id'];

    $product_id = $_POST['product_id'] ?? 'all';
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

    if($product_id !== 'all') {
        $whereClauses[] = "th.product_id = :product_id";
        $params[':product_id'] = $product_id;
    }


    $currentUserId = (int)($_SESSION['distributor_id'] ?? 0);
$whereClauses[] = "NOT (th.type = 'penjualan' AND th.customer_id = :current_user OR th.type = 'pembelian' AND th.suppliar_id = :current_user)";
$params[':current_user'] = $currentUserId;

    $whereClauses[] = "th.created_at BETWEEN :start AND :end";
    $params[':start'] = $issu_first_date . ' 00:00:00';
    $params[':end']   = $issu_end_date   . ' 23:59:59';

    $whereSQL = count($whereClauses) > 0 ? "WHERE " . implode(' AND ', $whereClauses) : "";

    // ========== Hitung Total ==========
    $countSql  = "SELECT COUNT(*) FROM transaction_histories th $whereSQL";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalData  = $countStmt->fetchColumn();
    $totalPages = ceil($totalData / $limit);

    // ========== Ambil Data ==========
    $dataSql = "
        SELECT th.*, 
               s1.name AS suppliar_name, 
               s2.name AS customer_name,
               s2.suppliar_code AS customer_code,
               s1.suppliar_code AS suppliar_code,
               p.product_name AS product_name
        FROM transaction_histories th
        LEFT JOIN suppliar s1 ON th.suppliar_id = s1.id
        LEFT JOIN suppliar s2 ON th.customer_id = s2.id
        LEFT JOIN products p  ON th.product_id = p.id
        $whereSQL
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
            // mapping type berdasarkan posisi suppliar_id / customer_id
            $transType = $data->type;

        
            // filter type manual
            if ($type !== 'all' && strtolower($transType) !== strtolower($type)) {
                continue;
            }

             $partnerName = '';
        $partnerCode = '';

        if ($transType === 'penjualan') {
            // tampilkan pembeli
            $partnerName = $data->customer_name ?: 'Penjualan Pribadi';
            $partnerCode = $data->customer_code ?: '';
        } elseif ($transType === 'pembelian') {
            // tampilkan penjual
            $partnerName = $data->suppliar_name ?: '-';
            // ambil kode suppliar kalau diperlukan
            $partnerCode = $data->suppliar_code ?? '';
        } else {
            // default (refund, dll.)
            $partnerName = $data->customer_name ?: '-';
            $partnerCode = $data->customer_code ?: '';
        }

            // handle quantity
            $quantityDisplay = $data->quantity;
            if (strtolower($data->type) === 'refund') {
                $quantityDisplay = "<span style='color:red;'>-" . $data->quantity . "</span>";
            }

            $dateFormatted = date('d-m', strtotime($data->created_at));

            echo "<tr>
                <td>{$dateFormatted}</td>
                <td>{$data->invoice_number}</td>
                <td>{$transType}</td>
                 <td>" . htmlspecialchars(trim($partnerName . '-' . $partnerCode)) . "</td>
                <td>{$data->product_name}</td>
                <td>{$quantityDisplay}</td>
                <td>" . htmlspecialchars($data->note) . "</td>
            </tr>";
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
