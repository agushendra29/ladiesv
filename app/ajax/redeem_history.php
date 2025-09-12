<?php
require '../init.php'; // sesuaikan path config/db
$user_id = $_SESSION['distributor_id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT rr.*, r.nama_reward
    FROM reward_redemptions rr
    JOIN rewards r ON r.id = rr.reward_id
    WHERE rr.user_id = ?
    ORDER BY rr.created_at DESC
");
$stmt->execute([$user_id]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
