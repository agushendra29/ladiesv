<?php 
require '../init.php';

if (isset($_POST['active_data'])) {
    $delete_id = $_POST['delete_id'];

		 $stmt1 = $pdo->prepare("UPDATE suppliar SET is_active = ? WHERE id = ?");
    $res1 = $stmt1->execute([0, $delete_id]);

    // Update user
    $stmt2 = $pdo->prepare("UPDATE user SET is_active = ? WHERE suppliar_id = ?");
    $res2 = $stmt2->execute([0, $delete_id]);

        if ($res1 && $res2) {
            echo "true"; // sukses
        } else {
            echo "Failed to update suppliar status";
        }
    
}
