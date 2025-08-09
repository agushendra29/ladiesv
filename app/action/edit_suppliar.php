<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = $_POST['id'];
    $name       = trim($_POST['name']);
    $nik        = trim($_POST['nik']);
    $rekening   = trim($_POST['rekening']);
    $bank       = trim($_POST['bank']);
    $address    = trim($_POST['address']);
    $contact    = trim($_POST['contact']);
    $email      = trim($_POST['email']);
    $role_id    = $_POST['role'];
    $password   = $_POST['password'] ?? '';
    $old_pass   = $_POST['old_password'] ?? '';
    $update_at  = date('Y-m-d');

    // Check all required fields
    if (
        empty($name) || empty($nik) || empty($rekening) || empty($bank) ||
        empty($address) || empty($contact) || empty($email) || empty($role_id)
    ) {
        echo "All fields are required.";
        exit;
    }

    // Step 1: Get suppliar by ID
    $suppliar = $obj->find('suppliar', 'id', $id);
    if (!$suppliar) {
        echo "Suppliar not found.";
        exit;
    }

    // Step 2: Update suppliar table
    $supQuery = [
        'name'       => $name,
        'nik'        => $nik,
        'rekening'   => $rekening,
        'bank'       => $bank,
        'address'    => $address,
        'con_num'    => $contact,
        'email'      => $email,
        'update_at'  => $update_at
    ];
    $supRes = $obj->update('suppliar', 'id', $id, $supQuery);

    // Step 3: Update suppliar_user
    $suppliar_id = $suppliar->id;
    $stmt = $pdo->prepare("SELECT * FROM user WHERE suppliar_id = ?");
    $stmt->execute([$suppliar_id]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user) {
        if (!empty($password)) {
            // Validate old password
            if (empty($old_pass)) {
                echo "Please enter the old password.";
                exit;
            }

            // Check password (must be hashed in DB)
            if (!password_verify($old_pass, $user->password)) {
                echo "Old password is incorrect.";
                exit;
            }

            // Password is valid; update password and role
            $newPassHash = password_hash($password, PASSWORD_DEFAULT);
            $updateUser = $pdo->prepare("UPDATE user SET password = ?, role_id = ? WHERE suppliar_id = ?");
            $updateUser->execute([$newPassHash, $role_id, $suppliar_id]);

        } else {
            // No password change; update only role
            $updateUser = $pdo->prepare("UPDATE user SET role_id = ? WHERE suppliar_id = ?");
            $updateUser->execute([$role_id, $suppliar_id]);
        }
    } else {
        echo "No user found for this suppliar.";
        exit;
    }

    if ($supRes) {
        echo "Supplier updated successfully.";
    } else {
        echo "Failed to update supplier.";
    }
}
?>
