<?php
require_once '../init.php'; // sesuaikan path

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $head_id = $_POST['head_id'];

    $stmt = $pdo->prepare("SELECT id, name, suppliar_code, parent_id, 
                                   (SELECT name FROM suppliar WHERE id = parent_id) as head_name
                            FROM suppliar
                            WHERE role_id = 3 AND parent_id = ?");
    $stmt->execute([$head_id]);
    $childs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($childs);
}
