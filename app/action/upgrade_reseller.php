<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $new_role = $_POST['new_role'] ?? null;

    if ($id && $new_role) {
        try {
            $pdo->beginTransaction();

            // Update role di tabel suppliar
            $stmt = $pdo->prepare("UPDATE suppliar SET role_id = :role WHERE id = :id");
            $stmt->execute([
                ':role' => $new_role,
                ':id'   => $id
            ]);

            // Reset stok produk di distributor_stocks
            $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = 0 WHERE suppliar_id = :id");
            $stmt->execute([':id' => $id]);

            // Reset stok produk di user
            $stmt = $pdo->prepare("UPDATE user SET role_id = :role  WHERE suppliar_id = :id");
            $stmt->execute([   ':role' => $new_role,
                ':id'   => $id]);

            $pdo->commit();

            echo json_encode([
                'status'  => 'success',
                'message' => 'Reseller berhasil diupgrade dan stok produk sudah direset'
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Data tidak lengkap'
        ]);
    }
}
