<?php
// require_once '../init.php'; // pastikan koneksi & session sudah di-include

$role_id     = $_SESSION['role_id'] ?? 0;
$suppliar_id = $_SESSION['distributor_id'] ?? 0;

// ==== Pagination Logic ====
// gunakan 'pg' agar tidak bentrok dengan router ?page=news
$perPage = 3;
$pg      = max(1, (int)($_GET['pg'] ?? 1));
$offset  = ($pg - 1) * $perPage;

$totalStmt  = $pdo->query("SELECT COUNT(*) FROM news WHERE is_active = 1");
$totalNews  = (int)$totalStmt->fetchColumn();
$totalPages = ceil($totalNews / $perPage);

$stmt = $pdo->prepare("
    SELECT * FROM news
    WHERE is_active = 1
    ORDER BY publish_date DESC
    LIMIT :offset, :limit
");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
$stmt->execute();
$newsList = $stmt->fetchAll(PDO::FETCH_OBJ);
?>
<style>
  body {
    background: #f5f7fb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    line-height: 1.6;
  }
  section.content { padding: 2rem 1rem; min-height: 85vh; }

  .section-title { font-size: 1.6rem; font-weight: 700; color: #1e293b; margin-bottom: .25rem; }
  .section-title-description { color:#64748b; margin-bottom:1.5rem; }

  .dashboard-grid {
    display: grid;
    grid-template-columns: 3fr 1fr;
    gap: 2rem;
  }
  @media (max-width: 992px) { .dashboard-grid { grid-template-columns: 1fr; } }

  /* Container Berita */
  .news-container {
    background:#fff;
    padding:1.5rem;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,0.06);
  }

  .news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
  }

  .news-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.07);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform .25s ease, box-shadow .25s ease;
  }
  .news-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
  }
  .news-icon {
    background: linear-gradient(135deg, #667eea, #764ba2);
    display:flex; align-items:center; justify-content:center;
    height:90px; font-size:36px; color:#fff;
  }
  .news-body { padding:1rem 1.2rem; display:flex; flex-direction:column; flex:1; }
  .news-title { font-size:1rem; font-weight:700; color:#1e3a8a; margin-bottom:.4rem; }
  .news-meta { font-size:0.8rem; color:#6b7280; margin-bottom:.75rem; }
  .news-excerpt {
    font-size:12px; color:#374151; margin-bottom:1rem;
    display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;
  }
  .read-more-btn {
    margin-top:auto;
    background: linear-gradient(135deg, #ff3d7c, #ff7096);
    color:#fff; text-decoration:none; padding:6px 12px;
    border-radius:6px; font-size:0.8rem; font-weight:600;
    text-align:center; transition:all 0.3s ease;
  }
  .read-more-btn:hover { background: linear-gradient(135deg, #f97316, #fb923c); }

  /* Pagination */
  .pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 10px;
  }
  .pagination a,
  .pagination span {
    padding: 6px 14px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    background: #4f46e5;
    color: #fff;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    transition: background 0.25s ease;
  }
  .pagination a:hover { background:#4338ca; }
  .pagination .disabled {
    background: #cbd5e1;
    color: #475569;
    cursor: default;
    box-shadow: none;
  }

  /* Sales Widgets */
  .sales-widgets { display:flex; flex-direction:column; gap:1.2rem; }
  .sales-widget-card {
    border-radius:12px; padding:1.2rem; color:#fff;
    border:1px solid rgba(255,255,255,0.3);
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
  }
  .sales-widget-title { font-size:1.1rem; font-weight:700; text-align:center; margin-bottom:.5rem; }
  .sales-widget-body ul { margin:0; padding-left:1rem; list-style:disc; }
  .sales-widget-body i { display:block; text-align:center; }
  .bg-cards-1 { background:linear-gradient(135deg,#f9a8d4,#ec4899); }
  .bg-cards-2 { background:linear-gradient(135deg,#fda4af,#f43f5e); }
</style>

<div>
  <section class="content">
    <div class="container-fluid pt-4">
      <div class="dashboard-grid">
        <!-- Kiri: Berita Terkini -->
        <div class="news-container">
          <div class="section-title">Berita Terkini</div>
          <p class="section-title-description">Pengumuman terbaru untuk semua member.</p>

          <div class="news-grid">
            <?php if($newsList): foreach($newsList as $news): ?>
              <div class="news-card">
                <div class="news-icon"><i class="fas fa-newspaper"></i></div>
                <div class="news-body">
                  <h4 class="news-title"><?= htmlspecialchars($news->title) ?></h4>
                  <span class="news-meta">
                    <?= htmlspecialchars($news->category) ?> |
                    <b><?= date('d M Y', strtotime($news->publish_date)) ?></b>
                  </span>
                  <p class="news-excerpt"><?= nl2br(htmlspecialchars($news->content)) ?></p>
                  <a href="index.php?page=news_detail&id=<?= $news->id ?>" class="read-more-btn">Read More</a>
                </div>
              </div>
            <?php endforeach; else: ?>
              <p>Belum ada berita yang tersedia.</p>
            <?php endif; ?>
          </div>

          <?php if ($totalPages > 1): ?>
          <div class="pagination">
            <?php if ($pg > 1): ?>
              <a href="index.php?page=dashboard&pg=<?= $pg-1 ?>">Prev</a>
            <?php else: ?>
              <span class="disabled">Prev</span>
            <?php endif; ?>

            <?php if ($pg < $totalPages): ?>
              <a href="index.php?page=dashboard&pg=<?= $pg+1 ?>">Next</a>
            <?php else: ?>
              <span class="disabled">Next</span>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- Kanan: Widget Penjualan -->
        <div>
          <div class="sales-widgets">
            <!-- Today -->
            <div class="sales-widget-card bg-cards-1">
              <div class="sales-widget-title">Today</div>
              <div class="sales-widget-body">
                <?php
                $start = (new DateTime('today'))->format('Y-m-d 00:00:00');
                $end   = (new DateTime('tomorrow'))->format('Y-m-d 00:00:00');
                $sqlDetail = "
                  SELECT p.product_name, SUM(t.quantity) AS total_sold
                  FROM transaction_histories t
                  JOIN products p ON p.id = t.product_id
                  WHERE t.created_at >= :start
                    AND t.created_at < :end
                    AND t.type = 'penjualan'
                    AND COALESCE(t.is_refund,0) = 0
                ";
                $params = ['start'=>$start,'end'=>$end];
                if(!in_array($role_id,[1,10])){
                  $sqlDetail .= " AND t.suppliar_id = :sid";
                  $params['sid'] = $suppliar_id;
                }
                $sqlDetail .= " GROUP BY p.product_name ORDER BY p.product_name ASC";
                $stmt2 = $pdo->prepare($sqlDetail);
                $stmt2->execute($params);
                $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                if($rows){
                  echo "<ul>";
                  foreach($rows as $r){
                    echo "<li>".htmlspecialchars($r['product_name'])." : ".(int)$r['total_sold']." pcs</li>";
                  }
                  echo "</ul>";
                } else {
                  echo "<i>Tidak ada penjualan hari ini</i>";
                }
                ?>
              </div>
            </div>

            <!-- Monthly -->
            <div class="sales-widget-card bg-cards-2">
              <div class="sales-widget-title">Monthly</div>
              <div class="sales-widget-body">
                <?php
                $start = date('Y-m-01 00:00:00');
                $end   = date('Y-m-t 23:59:59');
                $sqlDetail = "
                  SELECT p.product_name, SUM(t.quantity) AS total_sold
                  FROM transaction_histories t
                  JOIN products p ON p.id = t.product_id
                  WHERE t.created_at BETWEEN :start AND :end
                    AND t.type = 'penjualan'
                    AND COALESCE(t.is_refund,0) = 0
                ";
                $params = ['start'=>$start,'end'=>$end];
                if(!in_array($role_id,[1,10])){
                  $sqlDetail .= " AND t.suppliar_id = :sid";
                  $params['sid'] = $suppliar_id;
                }
                $sqlDetail .= " GROUP BY p.product_name ORDER BY p.product_name ASC";
                $stmt2 = $pdo->prepare($sqlDetail);
                $stmt2->execute($params);
                $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                if($rows){
                  echo "<ul>";
                  foreach($rows as $r){
                    echo "<li>".htmlspecialchars($r['product_name'])." : ".(int)$r['total_sold']." pcs</li>";
                  }
                  echo "</ul>";
                } else {
                  echo "<i>Tidak ada penjualan dalam bulan ini</i>";
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div><!-- end dashboard-grid -->
    </div>
  </section>
</div>
