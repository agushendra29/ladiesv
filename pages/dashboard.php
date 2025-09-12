<style>
  /* Base */
  body {
    background: #f5f7fb;
    color: #333;
    line-height: 1.6;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  section.content {
    min-height: 85vh;
    padding: 2rem 1rem;
  }

  h3.section-title {
    font-weight: 600;
    font-size: 1.6rem;
    margin-bottom: 1.5rem;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* News Section */
  .news-section {
    margin-bottom: 2rem;
  }

  .news-scroll {
    display: flex;
    gap: 18px;
    overflow-x: auto;
    padding-bottom: 12px;
    scroll-snap-type: x mandatory;
  }
  .news-scroll::-webkit-scrollbar {
    height: 6px;
  }
  .news-scroll::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 6px;
  }

  .news-card {
    flex: 0 0 320px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform .25s ease, box-shadow .25s ease;
    scroll-snap-align: start;
  }
  .news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.15);
  }

  .news-icon {
    background: linear-gradient(135deg, #3A7BDA, #EE6C6C);
    display: flex;
    align-items: center;
    justify-content: center;
    height: 110px;
    font-size: 42px;
    color: white;
  }

  .news-body {
    padding: 1.2rem;
    display: flex;
    flex-direction: column;
    flex: 1;
  }
  .news-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 0.4rem 0;
    color: #1e3a8a;
  }
  .news-meta {
    font-size: 0.85rem;
    color: #6b7280;
    margin-bottom: 0.75rem;
  }
  .news-excerpt {
    font-size: 0.9rem;
    color: #374151;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .read-more-btn {
    margin-top: auto;
    background: linear-gradient(135deg, #FF3D7C, #FF3D7C);
    color: white;
    text-decoration: none;
    padding: 9px 16px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
  }
  .read-more-btn:hover {
    background: linear-gradient(135deg, #f97316, #fb923c);
  }

  /* Info boxes */
  .info-boxes {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
  }
  .info-box {
    border-radius: 18px;
    padding: 1.5rem;
    color: white;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    text-align: center;
    transition: transform .25s ease, box-shadow .25s ease;
  }
  .info-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 22px rgba(0,0,0,0.15);
  }
  .info-box .info-box-text {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: .5rem;
  }
  .info-box .sell {
    font-weight: 600;
    font-size: 1rem;
    margin-top: 1rem;
  }
  .sell ul {
    margin: 0.5rem 0 0;
    padding: 0;
    list-style: none;
  }
  .sell li {
    font-size: 0.9rem;
    margin: 2px 0;
  }
  .sell i {
    font-size: 0.85rem;
  }
.bg-cards-1 {
  background: linear-gradient(135deg, #f9a8d4, #ec4899);
  color: white;
  border: none;
}

.bg-cards-2 {
  background: linear-gradient(135deg, #fda4af, #f43f5e);
  color: white;
  border: none;
}


  /* Responsive tweak */
  @media (max-width: 600px) {
    h3.section-title {
      font-size: 1.3rem;
    }
    .news-card {
      flex: 0 0 260px;
    }
    .info-box .info-box-text {
      font-size: 1.2rem;
    }
  }
</style>

<div>
  <section class="content">
    <div class="container-fluid pt-4">

      <!-- News Section -->
      <div class="news-section">
        <h3 class="section-title">📰 Latest News</h3>
        <div class="news-scroll">
          <?php 
            $stmt = $pdo->prepare("SELECT * FROM news WHERE is_active = 1 ORDER BY publish_date DESC");
            $stmt->execute();
            $newsList = $stmt->fetchAll(PDO::FETCH_OBJ);
            if($newsList):
              foreach($newsList as $news):
          ?>
          <div class="news-card">
            <div class="news-icon">
              <i class="fas fa-newspaper"></i>
            </div>
            <div class="news-body">
              <h4 class="news-title"><?= htmlspecialchars($news->title) ?></h4>
              <span class="news-meta">
                <?= htmlspecialchars($news->category) ?> | <?= date('d M Y', strtotime($news->publish_date)) ?>
              </span>
              <p class="news-excerpt"><?= nl2br(htmlspecialchars($news->content)) ?></p>
              <a href="index.php?page=news_detail&id=<?= $news->id ?>" class="read-more-btn">Read More</a>
            </div>
          </div>
          <?php endforeach; else: ?>
            <p class="no-news">Belum ada berita yang tersedia.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Statistik Boxes -->
      <div class="info-boxes">
        <!-- Today -->
        <div class="info-box bg-cards-1">
          <h2 class="info-box-text">Today</h2>
          <span class="sell">
            Sell:
            <?php
            $role_id     = $_SESSION['role_id'] ?? 0;
            $suppliar_id = $_SESSION['distributor_id'] ?? 0;

            $start = (new DateTime('today'))->format('Y-m-d 00:00:00');
            $end   = (new DateTime('tomorrow'))->format('Y-m-d 00:00:00');

            $sqlDetail = "
              SELECT p.product_name, SUM(t.quantity) AS total_sold
              FROM transaction_histories t
              JOIN products p ON p.id = t.product_id
              WHERE t.created_at >= :start
                AND t.created_at < :end
                AND t.type = 'penjualan'
                AND COALESCE(t.is_refund, 0) = 0
            ";

            $params = ['start' => $start, 'end' => $end];
            if (!in_array($role_id, [1,10])) {
                $sqlDetail .= " AND t.suppliar_id = :sid";
                $params['sid'] = $suppliar_id;
            }

            $sqlDetail .= " GROUP BY t.product_id, p.product_name
                            ORDER BY p.product_name ASC";

            $stmt2 = $pdo->prepare($sqlDetail);
            $stmt2->execute($params);
            $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            if ($rows) {
              echo "<ul>";
              foreach ($rows as $r) {
                echo "<li>" . htmlspecialchars($r['product_name']) . " : " . (int)$r['total_sold'] . " pcs</li>";
              }
              echo "</ul>";
            } else {
              echo "<i>Tidak ada penjualan hari ini</i>";
            }
            ?>
          </span>
        </div>

        <!-- Monthly -->
        <div class="info-box bg-cards-2">
          <h2 class="info-box-text">Monthly</h2>
          <span class="sell">
            Sell:
            <?php
            $start = date('Y-m-01 00:00:00');
            $end   = date('Y-m-t 23:59:59');

            $sqlDetail = "
              SELECT p.product_name, SUM(t.quantity) AS total_sold
              FROM transaction_histories t
              JOIN products p ON p.id = t.product_id
              WHERE t.created_at BETWEEN :start AND :end
                AND t.type = 'penjualan'
                AND COALESCE(t.is_refund, 0) = 0
            ";

            $params = ['start' => $start, 'end' => $end];
            if (!in_array($role_id, [1,10])) {
                $sqlDetail .= " AND t.suppliar_id = :sid";
                $params['sid'] = $suppliar_id;
            }

            $sqlDetail .= " GROUP BY t.product_id, p.product_name
                            ORDER BY p.product_name ASC";

            $stmt2 = $pdo->prepare($sqlDetail);
            $stmt2->execute($params);
            $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            if ($rows) {
              echo "<ul>";
              foreach ($rows as $r) {
                echo "<li>" . htmlspecialchars($r['product_name']) . " : " . (int)$r['total_sold'] . " pcs</li>";
              }
              echo "</ul>";
            } else {
              echo "<i>Tidak ada penjualan dalam bulan ini</i>";
            }
            ?>
          </span>
        </div>
      </div>

    </div>
  </section>
</div>
