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
    $user_id = $_SESSION['user_id'] ?? null;
    $suppliar_id = 3 . time(); // Suppliar ID unik

    // Validasi input
    if (
        $sup_name && $sup_nik && $sup_rekening && $sup_bank &&
        $sup_contact && $sup_email && $sup_role && $sup_password && $sup_address
    ) {
        try {
            // Simpan ke tabel `suppliar`
            $sup_data = [
                'suppliar_id' => $suppliar_id,
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

            // Simpan ke tabel `user` jika suppliar berhasil ditambahkan
            if ($res1) {
                $user_general_data = [
                    'username'   => $sup_email,
                    'password'   => md5($sup_password),
                    'user_role'  => $sup_role,
                    'suppliar_id'=> $suppliar_id
                ];
                $res2 = $obj->create('user', $user_general_data);

                if ($res2) {
                    echo "yes";
                } else {
                    echo "Gagal membuat user.";
                }
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
