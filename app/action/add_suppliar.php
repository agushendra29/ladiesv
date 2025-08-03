<?php 
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize inputs
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
    $suppliar_id = "S" . time(); // unique ID based on timestamp

    // Validate all fields
    if (
        !empty($sup_name) && !empty($sup_nik) && !empty($sup_rekening) &&
        !empty($sup_bank) && !empty($sup_contact) && !empty($sup_email) &&
        !empty($sup_role) && !empty($sup_password) && !empty($sup_address)
    ) {
        try {
            // Insert into 'suppliar' table
            $sup_data = [
                'suppliar_id' => $suppliar_id,
                'name'        => $sup_name,
                'address'     => $sup_address,
                'con_num'     => $sup_contact,
                'email'       => $sup_email,
                'nik'         => $sup_nik,
                'bank'   => $sup_bank,
                'rekening'    => $sup_rekening,
                'role_id' => $sup_role,
                'update_by'   => $user_id
            ];

            $res1 = $obj->create('suppliar', $sup_data);

            // If suppliar added successfully
            if ($res1) {
                // Insert into 'suppliar_user' table
                $user_data = [
                    'suppliar_id' => $suppliar_id,
                    'email'       => $sup_email,
                    'password'    => md5($sup_password),
                    'role_id'        => $sup_role,
                    'status'      => 1
                ];
                $res2 = $obj->create('suppliar_user', $user_data);

              if ($res2) {
        // Insert also into 'users' table
        $user_general_data = [
            'username'      => $sup_email,
            'password'   => md5($sup_password),
            'user_role'  => $sup_role,
        ];
        $res3 = $obj->create('user', $user_general_data);

        if ($res3) {
            echo "yes";
        } else {
            echo "User created in suppliar_user, but failed in users table.";
        }
            } else {
                echo "Failed to add distributor.";
            }
        }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "All fields are required.";
    }
}
?>
