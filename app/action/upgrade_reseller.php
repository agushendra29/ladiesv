<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = $_POST['id'] ?? null;
    $new_role = $_POST['new_role'] ?? null;

    if ($id && $new_role) {
        try {
            $pdo->beginTransaction();

            // --- Ambil role lama sebelum update ---
            $stmt = $pdo->prepare("SELECT role_id,parent_id FROM suppliar WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $oldData = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$oldData) {
                throw new Exception('Suppliar tidak ditemukan.');
            }
            $oldRole   = $oldData['role_id'];
            $oldParent = $oldData['parent_id'];

            // --- Update role di tabel suppliar ---
            $stmt = $pdo->prepare("
                UPDATE suppliar 
                SET role_id = :role, parent_id = NULL, parent_id_code = ''
                WHERE id = :id
            ");
            $stmt->execute([
                ':role' => $new_role,
                ':id'   => $id
            ]);

            // --- Reset stok produk di distributor_stocks ---
            $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock = 0 WHERE suppliar_id = :id");
            $stmt->execute([':id' => $id]);

            // --- Update role di tabel user ---
            $stmt = $pdo->prepare("
                UPDATE user 
                SET role_id = :role 
                WHERE id = :id
            ");
            $stmt->execute([
                ':role' => $new_role,
                ':id'   => $id
            ]);

            $now = date('Y-m-d H:i:s');

            // --- Insert ke levelup_history ---
            $stmt = $pdo->prepare("
                INSERT INTO levelup_history 
                    (suppliar_id, role_from, role_to, created_at, updated_at, parent_id_before)
                VALUES 
                    (:suppliar_id, :role_from, :role_to, :created_at, :updated_at, :parent_id)
            ");
            $stmt->execute([
                ':suppliar_id' => $id,
                ':role_from'   => $oldRole,
                ':role_to'     => $new_role,
                ':created_at'  => $now,
                ':updated_at'  => $now,
                ':parent_id' => $oldParent
            ]);

            $pdo->commit();

            echo json_encode([
                'status'  => 'success',
                'message' => 'Reseller berhasil diupgrade, stok produk direset, dan riwayat level-up tercatat'
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
