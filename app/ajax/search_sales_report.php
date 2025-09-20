<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---------- PARAMETER ----------
    $issueData   = $_POST['issuedate'] ?? '';
    $suppliar_id = ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10)
        ? ($_POST['suppliar_id'] ?? 'all')
        : ($_SESSION['distributor_id'] ?? 0);

    $product_id  = $_POST['product_id'] ?? 'all';
    $type        = $_POST['type'] ?? 'all';
    $page        = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit       = 10;
    $offset      = ($page - 1) * $limit;

    // ============================================================
    // 1️⃣  LOAD PERTAMA  (periode kosong + role HEAD)
    // ============================================================
    if (empty($issueData) && (int)($_SESSION['role_id'] ?? 0) === 2) {
        $headId = (int)$_SESSION['distributor_id'];
        $childStmt = $pdo->prepare("SELECT id,name,suppliar_code FROM suppliar WHERE parent_id = :pid");
        $childStmt->execute([':pid' => $headId]);
        $children = $childStmt->fetchAll(PDO::FETCH_OBJ);

        if ($children) {
            foreach ($children as $c) {
                echo "<tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>".htmlspecialchars($c->name.' - '.$c->suppliar_code)."</td>
                        <td>0</td>
                        <td>-</td>
                        <td>-</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Tidak ada distributor anak</td></tr>";
        }
        exit;
    }

    // ============================================================
    // 2️⃣  PROSES NORMAL (jika sudah filter)
    // ============================================================
    $data = explode('-', $issueData);
    $issu_first_date = isset($data[0]) ? $obj->convertDateMysql(trim($data[0])) : date('Y-m-d', strtotime('-30 days'));
    $issu_end_date   = isset($data[1]) ? $obj->convertDateMysql(trim($data[1]))   : date('Y-m-d');

    $where   = [];
    $params  = [];

    if ($suppliar_id !== 'all') {
        $where[] = "(th.suppliar_id = :suppliar_id OR th.customer_id = :suppliar_id)";
        $params[':suppliar_id'] = $suppliar_id;
    }
    if ($product_id !== 'all') {
        $where[] = "th.product_id = :product_id";
        $params[':product_id'] = $product_id;
    }

    $currentUser = (int)($_SESSION['distributor_id'] ?? 0);
    $where[] = "NOT ( (th.type='penjualan' AND th.customer_id = :current_user)
                   OR (th.type='pembelian' AND th.suppliar_id = :current_user) )";
    $params[':current_user'] = $currentUser;

    $where[] = "th.created_at BETWEEN :start AND :end";
    $params[':start'] = $issu_first_date . ' 00:00:00';
    $params[':end']   = $issu_end_date   . ' 23:59:59';

    $whereSQL = $where ? 'WHERE '.implode(' AND ', $where) : '';

    // Hitung total
    $cnt = $pdo->prepare("SELECT COUNT(*) FROM transaction_histories th $whereSQL");
    $cnt->execute($params);
    $totalData  = $cnt->fetchColumn();
    $totalPages = ceil($totalData / $limit);

    // ✅ Query ambil data + role_id dari suppliar & customer
    $sql = "SELECT th.*,
                   s1.name AS suppliar_name, s1.suppliar_code AS suppliar_code, s1.role_id AS sup_role,
                   s2.name AS customer_name, s2.suppliar_code AS customer_code, s2.role_id AS cust_role,
                   p.product_name
            FROM transaction_histories th
            LEFT JOIN suppliar s1 ON th.suppliar_id = s1.id
            LEFT JOIN suppliar s2 ON th.customer_id = s2.id
            LEFT JOIN products p  ON th.product_id = p.id
            $whereSQL
            ORDER BY th.created_at DESC
            LIMIT :offset, :limit";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($rows) {
        foreach ($rows as $r) {
            $transType = $r->type;
            if ($type !== 'all' && strtolower($transType) !== strtolower($type)) continue;

            // ✅ Tentukan partner dengan cek role_id = 1/10 -> Head Office
            if ($transType === 'penjualan') {
                if (in_array((int)$r->cust_role, [1,10])) {
                    $partnerName = 'Head Office';
                    $partnerCode = '';
                } else {
                    $partnerName = $r->customer_name ?: 'Penjualan Pribadi';
                    $partnerCode = $r->customer_code ?: '';
                }
            } elseif ($transType === 'pembelian') {
                if (in_array((int)$r->sup_role, [1,10])) {
                    $partnerName = 'Head Office';
                    $partnerCode = '';
                } else {
                    $partnerName = $r->suppliar_name ?: '-';
                    $partnerCode = $r->suppliar_code ?: '';
                }
            } else {
                if (in_array((int)$r->cust_role, [1,10])) {
                    $partnerName = 'Head Office';
                    $partnerCode = '';
                } else {
                    $partnerName = $r->customer_name ?: '-';
                    $partnerCode = $r->customer_code ?: '';
                }
            }

            $qty  = strtolower($r->type) === 'refund'
                ? "<span style='color:red;'>-{$r->quantity}</span>"
                : $r->quantity;

            $date = date('d-m', strtotime($r->created_at));

            echo "<tr>
                    <td>{$date}</td>
                    <td>{$r->invoice_number}</td>
                    <td>{$transType}</td>
                    <td>".htmlspecialchars(trim($partnerName.($partnerCode ? ' - '.$partnerCode : '')))."</td>
                    <td>{$qty}</td>
                    <td>{$r->product_name}</td>
                    <td>".htmlspecialchars($r->note)."</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7' style='text-align:center;'>No data found</td></tr>";
    }

    // Pagination
    echo '<tr><td colspan="7" style="text-align:center;">';
    echo '<nav><ul class="pagination justify-content-center" style="margin-top:15px;">';
    for ($p=1; $p <= $totalPages; $p++) {
        $active = ($p==$page)?'active':'';
        echo "<li class='page-item $active'><a href='#' class='page-link' data-page='$p'>$p</a></li>";
    }
    echo '</ul></nav></td></tr>';
}
