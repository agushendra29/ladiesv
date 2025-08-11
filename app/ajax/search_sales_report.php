<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issueData = $_POST['issuedate'];
    $suppliar_id = $_SESSION['role_id'] == 1 ?  $_POST['suppliar_id'] : $_SESSION['distributor_id'];

    // Pecah range tanggal
    $data = explode('-', $issueData);
    $issu_first_date = $obj->convertDateMysql(trim($data[0]));
    $issu_end_date   = $obj->convertDateMysql(trim($data[1]));

    // Query transaksi + join suppliar
    if ($suppliar_id === 'all') {
        $stmt = $pdo->prepare("
            SELECT th.*, 
                   s1.name AS suppliar_name, 
                   s2.name AS customer_name
            FROM transaction_histories th
            LEFT JOIN suppliar s1 ON th.suppliar_id = s1.id
            LEFT JOIN suppliar s2 ON th.customer_id = s2.id
            WHERE th.created_at BETWEEN :start AND :end
            ORDER BY th.created_at DESC
        ");
        $stmt->execute([
            ':start' => $issu_first_date . ' 00:00:00',
            ':end'   => $issu_end_date . ' 23:59:59'
        ]);
    } else {
        $stmt = $pdo->prepare("
            SELECT th.*, 
                   s1.name AS suppliar_name, 
                   s2.name AS customer_name
            FROM transaction_histories th
            LEFT JOIN suppliar s1 ON th.suppliar_id = s1.id
            LEFT JOIN suppliar s2 ON th.customer_id = s2.id
            WHERE th.suppliar_id = :suppliar_id
              AND th.created_at BETWEEN :start AND :end
            ORDER BY th.created_at DESC
        ");
        $stmt->execute([
            ':suppliar_id' => $suppliar_id,
            ':start'       => $issu_first_date . ' 00:00:00',
            ':end'         => $issu_end_date . ' 23:59:59'
        ]);
    }

    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($res) {
        $i = 0;
        foreach ($res as $data) {
            $quantityDisplay = $data->quantity;
            if (strtolower($data->type) === 'refund') {
              $quantityDisplay = "<span style='color:red;'>-" . $data->quantity . "</span>";
            }
             $dateFormatted = date('d-m', strtotime($data->created_at));
            $i++;
            echo "<tr>
            
                <td>{$dateFormatted}</td>
                <td>{$data->invoice_number}</td>
                <td>{$data->type}</td>
                <td>".($data->customer_name ?? 'Penjualan Pribadi')."</td> 
                <td>{$quantityDisplay}</td>
                <td>{$data->note}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='8' style='text-align:center;'>No data found</td></tr>";
    }
}