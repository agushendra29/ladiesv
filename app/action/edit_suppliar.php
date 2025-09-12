<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id           = $_POST['id'];
    $name         = trim($_POST['name']);
    $nik          = trim($_POST['nik']);
    $rekening     = trim($_POST['rekening']);
    $bank         = trim($_POST['bank']);
    $address      = trim($_POST['address']);
    $address_ktp  = trim($_POST['address_ktp']);
    $contact      = trim($_POST['contact']);
    $email        = trim($_POST['email']);
    $role_id      = trim($_POST['role']);
    $date_input   = $_POST['date_of_birth'] ?? null; // input dari user
    $password     = $_POST['password'] ?? '';
    $update_at    = date('Y-m-d');
    $sup_akun     = trim($_POST['sup_name_bank'] ?? '');
    $provinsi_id  = $_POST['provinsi'] ?? '';
    $kota_id      = $_POST['kota'] ?? '';
    $kecamatan    = $_POST['kecamatan'] ?? '';

    // Validasi semua field wajib
    if (
        empty($name) || empty($nik) || empty($rekening) || empty($bank) || empty($sup_akun) || 
        empty($address) || empty($address_ktp) || empty($contact) || 
        empty($email) || empty($role_id) || empty($provinsi_id) || empty($kota_id) || empty($kecamatan)
    ) {
        echo "All required fields must be filled.";
        exit;
    }

    // ===== PARSING TANGGAL dd-mm-yyyy =====
    $date_of_birth = null;
    if (!empty($date_input)) {
        if (preg_match('/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-(\d{4})$/', $date_input, $matches)) {
            $day   = $matches[1];
            $month = $matches[2];
            $year  = $matches[3];
            $date_of_birth = "$year-$month-$day"; // format Y-m-d untuk DB
        } else {
            echo "Format tanggal salah! Gunakan dd-mm-yyyy.";
            exit;
        }
    }

    // Cari suppliar berdasar ID
    $suppliar = $obj->find('suppliar', 'id', $id);
    if (!$suppliar) {
        echo "Supplier not found.";
        exit;
    }

    // Update tabel suppliar
    $supQuery = [
        'name'          => $name,
        'nik'           => $nik,
        'rekening'      => $rekening,
        'bank'          => $bank,
        'address'       => $address,
        'address_ktp'   => $address_ktp,
        'con_num'       => $contact,
        'email'         => $email,
        'date_of_birth' => $date_of_birth, // simpan dalam format Y-m-d
        'update_at'     => $update_at,
        'nama_rekening' => $sup_akun,
        'provinsi'      => $provinsi_id,
        'kota'          => $kota_id,
        'kecamatan'     => $kecamatan,
        'role_id' => $role_id
    ];

    $supRes = $obj->update('suppliar', 'id', $id, $supQuery);

    // Update user terkait
    $suppliar_id = $suppliar->id;
    $stmt = $pdo->prepare("SELECT * FROM user WHERE suppliar_id = ?");
    $stmt->execute([$suppliar_id]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user) {
        if (!empty($password)) {
            $updateUser = $pdo->prepare("UPDATE user SET password = ?, role_id = ?, username = ? WHERE suppliar_id = ?");
            $updateUser->execute([$password, $role_id, $email, $suppliar_id]);
        } else {
            $updateUser = $pdo->prepare("UPDATE user SET role_id = ? WHERE suppliar_id = ?");
            $updateUser->execute([$role_id, $suppliar_id]);
        }
    } else {
        echo "No user found for this supplier.";
        exit;
    }

    if ($supRes !== false) {
        echo "Supplier updated successfully.";
    } else {
        echo "Failed to update supplier.";
    }
}
?>
