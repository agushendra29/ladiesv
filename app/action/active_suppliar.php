<?php 
require '../init.php';

if (isset($_POST['active_data'])) {
    $delete_id = $_POST['delete_id'];
    $new_status = $_POST['new_status']; // 0 = suspend, 1 = aktifkan

    // Update suppliar
    $stmt1 = $pdo->prepare("UPDATE suppliar SET is_active = ? WHERE id = ?");
    $res1 = $stmt1->execute([$new_status, $delete_id]);

    // Update user
    $stmt2 = $pdo->prepare("UPDATE user SET is_active = ? WHERE suppliar_id = ?");
    $res2 = $stmt2->execute([$new_status, $delete_id]);

    if ($res1 && $res2) {
        echo "true"; // sukses
    } else {
        echo "Failed to update suppliar status";
    }
}