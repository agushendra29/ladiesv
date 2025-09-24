<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id           = intval($_POST['id'] ?? 0);
    $title        = trim($_POST['title'] ?? '');
    $category     = trim($_POST['category'] ?? '');
    $publish_date = trim($_POST['publish_date'] ?? '');
    $content      = trim($_POST['content'] ?? '');
    $roles        = $_POST['roles'] ?? []; // array dari checkbox multiple

    // gabungkan roles menjadi string "1,3,4" atau kosong bila tidak ada pilihan
    $rolesStr = '';
    if (!empty($roles) && is_array($roles)) {
        // pastikan hanya angka yang masuk
        $rolesClean = array_map('intval', $roles);
        $rolesStr   = implode(',', $rolesClean);
    }

    if (
        $id > 0 &&
        $title !== '' &&
        $category !== '' &&
        $publish_date !== '' &&
        $content !== ''
    ) {
        try {
            $pdo->beginTransaction();

            $newsData = [
                'title'        => $title,
                'category'     => $category,
                'publish_date' => $publish_date,
                'content'      => $content,
                'role'         => $rolesStr,          // ✅ simpan roles
                'updated_at'   => date('Y-m-d H:i:s')
            ];

            $obj->update('news', 'id', $id, $newsData);

            $pdo->commit();
            echo 'Berita berhasil diperbarui.';      // balasan sukses
        } catch (Exception $e) {
            $pdo->rollBack();
            echo 'Gagal memperbarui berita: ' . $e->getMessage();
        }
    } else {
        echo 'Data tidak lengkap atau ID tidak valid.';
    }
} else {
    http_response_code(405);
    echo 'Method not allowed';
}
