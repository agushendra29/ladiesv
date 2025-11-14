<?php
require '../init.php'; // sesuaikan path config/db

$role_id = $_SESSION['role_id'] ?? 0;
$user_id = $_SESSION['distributor_id'] ?? 0;

if ($role_id == 1 || $role_id == 10) {
    // Jika Admin atau Super Admin → tampilkan semua data
    $stmt = $pdo->prepare("
        SELECT 
            rr.*, 
            r.nama_reward, 
            s.name AS supplier_name,
            s.suppliar_code AS code,
            ro.name AS supplier_role
        FROM reward_redemptions rr
        JOIN rewards r ON r.id = rr.reward_id
        LEFT JOIN suppliar s ON s.id = rr.user_id
        LEFT JOIN role ro ON ro.id = s.role_id
        ORDER BY rr.created_at DESC
    ");
    $stmt->execute();
} else {
    // Jika user biasa → hanya tampilkan data miliknya
    $stmt = $pdo->prepare("
        SELECT 
            rr.*, 
            r.nama_reward,
            s.name AS supplier_name,
            ro.name AS supplier_role
        FROM reward_redemptions rr
        JOIN rewards r ON r.id = rr.reward_id
        LEFT JOIN suppliar s ON s.id = rr.user_id
        LEFT JOIN role ro ON ro.id = s.role_id
        WHERE rr.user_id = ?
        ORDER BY rr.created_at DESC
    ");
    $stmt->execute([$user_id]);
}

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);
