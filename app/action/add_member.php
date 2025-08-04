<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan sanitasi input
    $sup_name      = trim($_POST['sup_name'] ?? '');
    $sup_nik       = trim($_POST['sup_nik'] ?? '');
    $sup_rekening  = trim($_POST['sup_rekening'] ?? '');
    $sup_bank      = trim($_POST['sup_bank'] ?? '');
    $sup_contact   = trim($_POST['sup_contact'] ?? '');
    $sup_email     = trim($_POST['sup_email'] ?? '');
    $sup_password  = trim($_POST['sup_password'] ?? '');
    $sup_address   = trim($_POST['supaddress'] ?? '');
    
    $sup_role      = 4; // Tetap fix untuk role supplier
    $user_id       = $_SESSION['user_id'] ?? null;
    $suppliar_id   = '5' . time(); // ID unik supplier

    // Validasi input
    if (
        $sup_name && $sup_nik && $sup_rekening &&
        $sup_bank && $sup_contact && $sup_email &&
        $sup_password && $sup_address
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
                'update_by'   => $user_id,
                'role_id'     => $sup_role
            ];

            $res1 = $obj->create('suppliar', $sup_data);

            if ($res1) {
                // Simpan ke tabel `user`
                $user_data = [
                    'username'     => $sup_email,
                    'password'     => password_hash($sup_password, PASSWORD_BCRYPT),
                    'user_role'    => $sup_role,
                    'suppliar_id'  => $suppliar_id
                ];

                $res2 = $obj->create('user', $user_data);

                echo $res2 ? "yes" : "Gagal membuat akun user.";
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
