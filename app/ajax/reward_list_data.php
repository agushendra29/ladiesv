<?php
require_once '../init.php';

$role_id = $_POST['role_id'] ?? '';

$sql = "SELECT * FROM rewards WHERE 1=1";
$params = [];

if ($role_id !== '' && $role_id !== 'all') {
    $sql .= " AND role_id = ?";
    $params[] = $role_id;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) > 0) {
    foreach ($rows as $index => $row) {
         $periode = htmlspecialchars($row['periode_hadiah_dari']) . " s/d " . htmlspecialchars($row['periode_hadiah_sampai']);
        echo "<tr>
            <td>" . ($index + 1) . "</td>
            <td>{$row['nama_reward']}</td>
            <td>{$periode}</td>
            <td>{$row['role_id']}</td>
            <td>{$row['jumlah_point']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6' class='no-data'>Tidak ada data ditemukan.</td></tr>";
}
