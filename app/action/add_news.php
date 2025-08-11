<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title        = trim($_POST['title'] ?? '');
    $category     = trim($_POST['category'] ?? '');
    $publish_date = trim($_POST['publish_date'] ?? '');
    $content      = trim($_POST['content'] ?? '');

    if (!empty($title) && !empty($category) && !empty($publish_date) && !empty($content)) {
        try {
            $pdo->beginTransaction();

            $newsData = [
                'title'        => $title,
                'category'     => $category,
                'publish_date' => $publish_date,
                'content'      => $content,
                'created_at'   => date('Y-m-d H:i:s')
            ];

            $obj->create('news', $newsData);

            $pdo->commit();
            echo 'Berita berhasil disimpan.';
        } catch (Exception $e) {
            $pdo->rollBack();
            echo 'Gagal menyimpan berita: ' . $e->getMessage();
        }
    } else {
        echo 'Silakan lengkapi semua field.';
    }
} else {
    http_response_code(405);
    echo 'Method not allowed';
}
