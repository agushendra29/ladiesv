<?php
require_once '../init.php';

$role_id = $_POST['role_id'] ?? '';
$current_role_id = $_SESSION['role_id'] ?? ''; // role user yang sedang login

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
        // ⛔ Skip jika reward tidak aktif dan role login bukan Super Admin
        if ($current_role_id != 10 && $row['is_active'] == 0) {
            continue;
        }

        $periode = htmlspecialchars($row['periode_hadiah_dari']) . " s/d " . htmlspecialchars($row['periode_hadiah_sampai']);
        $roleName = getRoleName($row['role_id']);

        // Nama reward, jika tidak aktif dan role = 10 → tambahkan label
        $namaReward = htmlspecialchars($row['nama_reward']);
        if ($current_role_id == 10 && $row['is_active'] == 0) {
            $namaReward .= " <span style='color: red; font-style: italic;'>(Tidak Aktif)</span>";
        }

        // Tombol Hide / Unhide
        $toggleBtn = '';
        if ($row['is_active'] == 1) {
            $toggleBtn = "<button class='btn btn-sm btn-outline-secondary hideReward' data-id='{$row['id']}' data-status='0' title='Sembunyikan'>
                            Hide
                          </button>";
        } else {
            $toggleBtn = "<button class='btn btn-sm btn-outline-success unhideReward' data-id='{$row['id']}' data-status='1' title='Tampilkan'>
                            Publish
                          </button>";
        }

        echo "<tr>
            <td>" . ($index + 1) . "</td>
            <td>{$namaReward}</td>
            <td>{$periode}</td>
            <td>{$roleName}</td>
            <td>" . htmlspecialchars($row['jumlah_point']) . "</td>
            <td>" . htmlspecialchars($row['max_redeem']) . "</td>
            <td>";

        // ✅ Tampilkan aksi hanya jika role login = 10
        if ($current_role_id == 10) {
            echo "
                <a href='index.php?page=edit_reward&edit_id={$row['id']}' class='btn btn-sm btn-outline-warning' title='Edit'>
                    <i class='fas fa-edit'></i>
                </a>
                <button class='btn btn-sm btn-outline-danger deleteReward' data-id='{$row['id']}' title='Hapus'>
                    <i class='fas fa-trash'></i>
                </button>
                {$toggleBtn}
            ";
        }

        echo "</td></tr>";
    }
} else {
    echo "<tr><td colspan='7' class='no-data'>Tidak ada data ditemukan.</td></tr>";
}
?>
