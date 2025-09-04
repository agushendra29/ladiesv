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
    $sup_address   = trim($_POST['supaddress'] ?? '');
    $sup_address_ktp = trim($_POST['supaddressktp'] ?? '');
    $sup_provinsi  = trim($_POST['sup_provinsi'] ?? '');   // ✅ baru
    $sup_kota      = trim($_POST['sup_kota'] ?? '');       // ✅ baru
      $sup_kecamatan      = trim($_POST['sup_kecamatan'] ?? '');       // ✅ baru
    $sup_akun      = trim($_POST['sup_name_bank'] ?? '');
    $user_id       = $_SESSION['user_id'] ?? null;

    if (!empty($_POST['birth_date'])) {
        $birth_date = date('Y-m-d', strtotime($_POST['birth_date']));
    } else {
        $birth_date = null;
    }

    $name_prefix = substr(preg_replace('/[^A-Za-z]/', '', $sup_name), 0, 3);
    $dob_format  = $birth_date ? date('dmY', strtotime($birth_date)) : '';
    $sup_password_plain = strtolower($name_prefix) . $dob_format;

    // Validasi NIK
    if (!preg_match('/^[0-9]{16}$/', $sup_nik)) {
        echo "NIK harus berupa 16 digit angka.";
        exit;
    }

    if (
        $sup_name && $sup_nik && $sup_rekening && $sup_bank && $birth_date && $sup_akun &&
        $sup_contact && $sup_email && $sup_role && $sup_password_plain && $sup_address &&
        $sup_provinsi && $sup_kota && $sup_kecamatan  // ✅ wajib isi
    ) {
        try {
            $sup_data = [
                'name'          => $sup_name,
                'address'       => $sup_address,
                'con_num'       => $sup_contact,
                'email'         => $sup_email,
                'nik'           => $sup_nik,
                'bank'          => $sup_bank,
                'rekening'      => $sup_rekening,
                'role_id'       => $sup_role,
                'update_by'     => $user_id,
                'address_ktp'   => $sup_address_ktp,
                'date_of_birth' => $birth_date,
                'nama_rekening' => $sup_akun,
                'provinsi'   => $sup_provinsi,   // ✅ simpan id provinsi
                'kota'       => $sup_kota,       // ✅ simpan id kabupaten/kota
                   'kecamatan'       => $sup_kecamatan,       // ✅ simpan id kabupaten/kota
                'is_active'     => 1,
            ];

            $suppliar_id = $obj->create('suppliar', $sup_data);

            if ($suppliar_id) {
                $suppliar_code = str_pad($suppliar_id, 6, "0", STR_PAD_LEFT);
                $obj->update('suppliar', 'id', $suppliar_id, [
                    'suppliar_code' => $suppliar_code
                ]);

                $user_general_data = [
                    'username'      => $sup_email,
                    'password'      => $sup_password_plain,
                    'role_id'       => $sup_role,
                    'suppliar_id'   => $suppliar_id,
                    'is_active'     => 1,
                    'suppliar_code' => $suppliar_code
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
