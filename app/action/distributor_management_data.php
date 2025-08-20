<?php
require_once '../init.php'; // sesuaikan path

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $mappings = $_POST['mappings'] ?? [];
    $deleted = $_POST['deleted'] ?? [];

    // Update parent_id untuk mapping baru
    foreach($mappings as $headId => $childs){
        foreach($childs as $childId => $data){
            $stmt = $pdo->prepare("UPDATE suppliar SET parent_id = ? WHERE id = ?");
            $stmt->execute([$headId, $childId]);
        }
    }

    // Set parent_id = NULL untuk child yang dihapus
    if(!empty($deleted)){
        $in  = str_repeat('?,', count($deleted) - 1) . '?';
        $stmt = $pdo->prepare("UPDATE suppliar SET parent_id = NULL WHERE id IN ($in)");
        $stmt->execute($deleted);
    }

    echo "Data distributor berhasil diperbarui!";
}
