<?php
require_once 'app/init.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='text-align:center; padding:20px;'>Berita tidak ditemukan.</p>";
    exit;
}

$id = (int) $_GET['id'];

// Ambil data berita
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$news = $stmt->fetch(PDO::FETCH_OBJ);

if (!$news) {
    echo "<p style='text-align:center; padding:20px;'>Berita tidak ditemukan.</p>";
    exit;
}
?>

<style>
  .news-detail-wrapper {
    padding: 80px 20px;
    background-color: white;
  }
  .news-detail-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    padding: 30px;
    max-width: 900px;
    margin: auto;
  }
  .news-detail-title {
    font-size: 28px;
    font-weight: 700;
    color: #222;
    margin-bottom: 10px;
    line-height: 1.3;
  }
  .news-detail-meta {
    font-size: 14px;
    color: #777;
    margin-bottom: 25px;
  }
  .news-detail-content {
    font-size: 16px;
    color: #444;
    line-height: 1.7;
    white-space: pre-line;
  }
  .btn-back {
    display: inline-block;
    margin-top: 30px;
    background: #EB4F7D;
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: background-color 0.3s ease;
  }
  .btn-back:hover {
    background-color: #005bb5;
  }
</style>

<div class="news-detail-wrapper">
  <div class="news-detail-container">
    <!-- Judul -->
    <h1 class="news-detail-title"><?= htmlspecialchars($news->title) ?></h1>

    <!-- Meta info -->
    <p class="news-detail-meta">
      <?= htmlspecialchars($news->category) ?> &nbsp;•&nbsp; 
      <?= date('d M Y', strtotime($news->publish_date)) ?>
    </p>

    <!-- Isi berita -->
    <div class="news-detail-content">
      <?= nl2br(htmlspecialchars($news->content)) ?>
    </div>

    <!-- Tombol kembali -->
    <a href="index.php" class="btn-back">← Kembali ke Beranda</a>
  </div>
</div>
