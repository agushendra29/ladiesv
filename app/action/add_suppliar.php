<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input dan sanitasi
    $sup_name      = trim($_POST['sup_name'] ?? '');
    $sup_nik       = trim($_POST['sup_nik'] ?? '');
    $sup_rekening  = trim($_POST['sup_rekening'] ?? '');
    $sup_bank      = trim($_POST['sup_bank'] ?? '');
    $sup_contact   = trim($_POST['sup_contact'] ?? '');
    $sup_email     = trim($_POST['sup_email'] ?? '');
    $sup_role      = trim($_POST['sup_role'] ?? '');
    $sup_password  = trim($_POST['sup_password'] ?? '');
    $sup_address   = trim($_POST['supaddress'] ?? '');
    $user_id       = $_SESSION['user_id'] ?? null;
    $serial_code   = 3 . time(); // Suppliar serial code (not primary key)

    // Validasi input
    if (
        $sup_name && $sup_nik && $sup_rekening && $sup_bank &&
        $sup_contact && $sup_email && $sup_role && $sup_password && $sup_address
    ) {
        try {
            // Simpan ke tabel `suppliar`
            $sup_data = [
                'serial_code' => $serial_code,
                'name'        => $sup_name,
                'address'     => $sup_address,
                'con_num'     => $sup_contact,
                'email'       => $sup_email,
                'nik'         => $sup_nik,
                'bank'        => $sup_bank,
                'rekening'    => $sup_rekening,
                'role_id'     => $sup_role,
                'update_by'   => $user_id
            ];

            $res1 = $obj->create('suppliar', $sup_data);

            // Dapatkan ID suppliar terakhir
            if ($res1) {
                $suppliar_id = $res1; // DAPATKAN PRIMARY KEY SUPPLIAR
                // Simpan user
                $user_general_data = [
                    'username'     => $sup_email,
                    'password'     => md5($sup_password),
                    'role_id'      => $sup_role,
                    'suppliar_id'  => $suppliar_id,
                    'is_active'    => 1
                ];
                $res2 = $obj->create('user', $user_general_data);

                echo $res2 ? "yes" : "Gagal membuat user.";
            } else {
                echo "Gagal menambahkan suppliar.";
            }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Semua field harus diisi.";
    }
}
?>
