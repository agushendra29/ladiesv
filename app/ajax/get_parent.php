<?php
require_once '../init.php';

$stmt = $pdo->prepare("SELECT id, name FROM suppliar WHERE role IN (2,3)");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);