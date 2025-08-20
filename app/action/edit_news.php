<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id           = intval($_POST['id'] ?? 0);
    $title        = trim($_POST['title'] ?? '');
    $category     = trim($_POST['category'] ?? '');
    $publish_date = trim($_POST['publish_date'] ?? '');
    $content      = trim($_POST['content'] ?? '');

    if ($id > 0 && !empty($title) && !empty($category) && !empty($publish_date) && !empty($content)) {
        try {
            $pdo->beginTransaction();

            $newsData = [
                'title'        => $title,
                'category'     => $category,
                'publish_date' => $publish_date,
                'content'      => $content,
                'updated_at'   => date('Y-m-d H:i:s')
            ];

            // update by id
            $obj->update('news', 'id', $id, $newsData);

            $pdo->commit();
            echo 'Berita berhasil diperbarui.';
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
