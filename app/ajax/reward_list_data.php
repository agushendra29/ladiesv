<?php
require_once '../init.php';

$role_id = $_POST['role_id'] ?? '';

$sql = "SELECT * FROM rewards WHERE 1=1";
$params = [];

// Filter role jika bukan "all"
if ($role_id !== '' && $role_id !== 'all') {
    $sql .= " AND role_id = ?";
    $params[] = $role_id;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fungsi mapping role_id ke nama role
function getRoleName($role_id) {
    switch ((int)$role_id) {
        case 1: return 'HO';
        case 2: return 'HD';
        case 3: return 'D';
        case 4: return 'A';
        case 5: return 'R';
        case 10: return 'Super Admin';
        default: return '-';
    }
}

if (count($rows) > 0) {
    foreach ($rows as $index => $row) {
        $periode = htmlspecialchars($row['periode_hadiah_dari']) . " s/d " . htmlspecialchars($row['periode_hadiah_sampai']);
        $roleName = getRoleName($row['role_id']);

        // Tombol Hide / Unhide
        if ($row['is_active'] == 1) {
            $toggleBtn = "<button class='btn btn-sm btn-outline-secondary hideReward' data-id='{$row['id']}' data-status='0' title='Sembunyikan'>
                            <i class='fas fa-eye-slash'></i>
                          </button>";
        } else {
            $toggleBtn = "<button class='btn btn-sm btn-outline-success unhideReward' data-id='{$row['id']}' data-status='1' title='Tampilkan'>
                            <i class='fas fa-eye'></i>
                          </button>";
        }

        echo "<tr>
    <td>" . ($index + 1) . "</td>
    <td>" . htmlspecialchars($row['nama_reward']) . "</td>
    <td>{$periode}</td>
    <td>{$roleName}</td>
    <td>" . htmlspecialchars($row['jumlah_point']) . "</td>
    <td>" . htmlspecialchars($row['max_redeem']) . "</td>
    <td>
        <a href='index.php?page=edit_reward&edit_id={$row['id']}' class='btn btn-sm btn-outline-warning' title='Edit'>
            <i class='fas fa-edit'></i>
        </a>
        <button class='btn btn-sm btn-outline-danger deleteReward' data-id='{$row['id']}' title='Hapus'>
            <i class='fas fa-trash'></i>
        </button>
        {$toggleBtn}
    </td>
</tr>";

    }
} else {
    echo "<tr><td colspan='6' class='no-data'>Tidak ada data ditemukan.</td></tr>";
}
