<?php
require_once '../../init.php'; // sesuaikan path

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = $_POST['supplier_id'] ?? null;
    $child_id = $_POST['child_id'] ?? null;

    if ($supplier_id && $child_id) {
        try {
            // Cek apakah sudah ada
            $exists = $obj->single("SELECT * FROM suppliar_child WHERE supplier_id=? AND child_id=?", [$supplier_id, $child_id]);
            if ($exists) {
                echo json_encode(["success" => false, "message" => "Sudah ditambahkan sebelumnya"]);
                exit;
            }

            // Insert
            $obj->insert("suppliar_child", [
                "supplier_id" => $supplier_id,
                "child_id"    => $child_id
            ]);

            echo json_encode(["success" => true]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
    }
}
