<?php
require_once '../init.php'; // sesuaikan path

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $mappings = $_POST['mappings'] ?? [];
    $deleted = $_POST['deleted'] ?? [];

    $now = date('Y-m-d H:i:s');
    $pdo->beginTransaction(); // Mulai transaksi untuk memastikan konsistensi data

    try {
        // --- Operasi 1: Update parent_id untuk mapping baru ---
        $historyData = [];
        $childIdsToFetch = [];

        // Kumpulkan childId yang akan diupdate untuk mencari parent_id_before
        foreach($mappings as $headId => $childs){
            foreach($childs as $childId => $data){
                $childIdsToFetch[] = $childId;
            }
        }
        
        // Ambil parent_id_before massal
        if (!empty($childIdsToFetch)) {
            $in = str_repeat('?,', count($childIdsToFetch) - 1) . '?';
            $stmt = $pdo->prepare("SELECT id, parent_id FROM suppliar WHERE id IN ($in)");
            $stmt->execute($childIdsToFetch);
            $parentsBefore = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        }

        // Lakukan UPDATE dan kumpulkan data history
        foreach($mappings as $headId => $childs){
            foreach($childs as $childId => $data){
                $parentBefore = $parentsBefore[$childId] ?? NULL;
                
                // UPDATE suppliar
                $stmt = $pdo->prepare("UPDATE suppliar SET parent_id = ? WHERE id = ?");
                $stmt->execute([$headId, $childId]);

                // Kumpulkan data untuk INSERT ke history
                $historyData[] = [
                    'suppliar_id' => $childId,
                    'parent_id_before' => $parentBefore,
                    'current_parent_id' => $headId
                ];
            }
        }

        // --- Operasi 2: Set parent_id = NULL untuk child yang dihapus ---
        if(!empty($deleted)){
            // Ambil parent_id_before untuk data yang dihapus
            $in = str_repeat('?,', count($deleted) - 1) . '?';
            $stmt = $pdo->prepare("SELECT id, parent_id FROM suppliar WHERE id IN ($in)");
            $stmt->execute($deleted);
            $parentsBeforeDeleted = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Lakukan UPDATE suppliar
            $stmt = $pdo->prepare("UPDATE suppliar SET parent_id = NULL WHERE id IN ($in)");
            $stmt->execute($deleted);

            // Kumpulkan data history untuk child yang dihapus (current_parent_id = NULL)
            foreach($deleted as $childId){
                $historyData[] = [
                    'suppliar_id' => $childId,
                    'parent_id_before' => $parentsBeforeDeleted[$childId] ?? NULL,
                    'current_parent_id' => NULL
                ];
            }
        }

        // --- Operasi 3: Insert ke distributor_management_history (Massal) ---
        if (!empty($historyData)) {
            $historySql = "INSERT INTO distributor_management_history 
                           (suppliar_id, parent_id_before, current_parent_id, created_at) 
                           VALUES (?, ?, ?, ?)"; // NOW() diganti dengan placeholder
            $stmt = $pdo->prepare($historySql);

            foreach ($historyData as $data) {
                // Pastikan nilai NULL dikirim dengan benar ke database
                $parentBefore = $data['parent_id_before'];
                $currentParent = $data['current_parent_id'];
                
                $stmt->execute([
                    $data['suppliar_id'],
                    $parentBefore === null ? NULL : $parentBefore,
                    $currentParent === null ? NULL : $currentParent,
                    $now // Variabel $now digunakan di sini
                ]);
            }
        }

        $pdo->commit(); // Selesaikan transaksi
        echo "Data distributor berhasil diperbarui dan riwayat disimpan!";

    } catch (Exception $e) {
        $pdo->rollBack(); // Batalkan semua operasi jika terjadi kesalahan
        // Log error jika diperlukan
        http_response_code(500);
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}