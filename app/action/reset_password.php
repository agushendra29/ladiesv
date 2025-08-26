<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $nik_last6 = trim($_POST['nik_last6'] ?? '');

    if ($username === '' || $nik_last6 === '') {
        $_SESSION['reset_error'] = "Harap isi semua field.";
        exit;
    }

    try {
        // Cek user
    $stmt = $pdo->prepare("
            SELECT u.id, u.suppliar_code, s.nik, s.name, s.date_of_birth
            FROM user u
            INNER JOIN suppliar s ON s.id = u.suppliar_id
            WHERE u.suppliar_code = :username
        ");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            // Ambil NIK terakhir
            $nik = $user['nik'] ?? '';
            $last6 = substr($nik, -6);

            if ($last6 === $nik_last6) {
                    $sup_name   = $user['name'] ?? '';
                $birth_date = $user['date_of_birth'] ?? null;

                // Format password baru
                $name_prefix = substr(preg_replace('/[^A-Za-z]/', '', $sup_name), 0, 3); // hanya huruf
                $dob_format  = $birth_date ? date('dmY', strtotime($birth_date)) : '';
                $sup_password_plain = strtolower($name_prefix) . $dob_format;

                $newPassword = $nik_last6; 

                $update = $pdo->prepare("UPDATE user SET password = :password WHERE id = :id");
                $update->execute([
                    ':password' => $sup_password_plain,
                    ':id' => $user['id']
                ]);

                $_SESSION['reset_success'] = "Password berhasil direset! Password baru: <b>$sup_password_plain</b>";
                exit;
            } else {
                $_SESSION['reset_error'] = "6 digit NIK tidak sesuai.";
                exit;
            }
        } else {
            $_SESSION['reset_error'] = "Username tidak ditemukan.";
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['reset_error'] = "Terjadi kesalahan: " . $e->getMessage();

        exit;
    }
} else {
    exit;
}
