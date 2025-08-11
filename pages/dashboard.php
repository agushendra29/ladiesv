<style>
  /* Reset & base */
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f8f9fa;
    color: #333;
  }

  section.content {
    min-height: 80vh;
    padding: 2.5rem 1rem;
  }

  h3 {
    font-weight: 700;
    color: #222;
    margin-bottom: 1rem;
  }

  /* Card style */
  .card-custom {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    padding: 1.5rem;
    margin-bottom: 2.5rem;
  }

  /* Grid layout for news */
  .news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(250px,1fr));
    gap: 1.25rem;
  }

  /* News card */
  .news-card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    background: #fff;
    transition: transform 0.2s ease;
  }
  .news-card:hover {
    transform: translateY(-5px);
  }
  .news-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    flex-shrink: 0;
  }
  .news-content {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }
  .news-content h5 {
    font-weight: 600;
    font-size: 1.125rem;
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
    color: #222;
  }
  .news-content h6 {
    font-size: 0.875rem;
    color: #777;
    margin: 0 0 1rem 0;
    font-weight: 500;
  }
  .news-content p {
    flex-grow: 1;
    font-size: 0.875rem;
    color: #444;
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 5;
    -webkit-box-orient: vertical;
  }

  /* Info boxes */
  .info-boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
  }
  .info-box {
    flex: 1 1 280px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  .info-box .info-box-text {
    font-size: 1.125rem;
    font-weight: 600;
  }
  .info-box .info-box-number {
    font-size: 2rem;
    font-weight: 700;
    margin-top: 0.25rem;
  }
  .info-box-icon {
    font-size: 2.5rem;
    opacity: 0.8;
  }
  .bg-danger { background-color: #e55353; }
  .bg-success { background-color: #4caf50; }
  .bg-cards-1 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
  .bg-cards-2 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

  /* Sell / Buy labels inside info-boxes */
  .sell, .buy {
    display: block;
    font-weight: 600;
    font-size: 1.125rem;
    margin-top: 0.5rem;
  }
  .sell {
    color: #ffe9e9;
  }
  .buy {
    color: #d2f7f1;
  }

  /* Table styling */
  table {
    width: 100%;
    border-collapse: collapse;
  }
  thead {
    background-color: #343a40;
    color: white;
  }
  thead th {
    padding: 0.75rem 1rem;
    text-align: center;
    font-weight: 600;
  }
  tbody tr:hover {
    background-color: #f1f1f1;
  }
  tbody td {
    padding: 0.75rem 1rem;
    text-align: center;
    border-bottom: 1px solid #e0e0e0;
  }

  /* Responsive */
  @media(max-width: 768px) {
    .info-boxes {
      flex-direction: column;
    }
  }
</style>

<div>
  <section class="content">
    <div class="container-fluid">

      <!-- News Section -->
      <div>
        <h3>News</h3>
        <div class="news-grid mb-4">
          <?php 
            $stmt = $pdo->prepare("SELECT * FROM news ORDER BY publish_date DESC LIMIT 3");
            $stmt->execute();
            $newsList = $stmt->fetchAll(PDO::FETCH_OBJ);
            if($newsList):
              foreach($newsList as $news):
                $thumbnail = "assets/images/7567.jpg"; // Replace with actual thumbnail if any
          ?>
          <article class="news-card" role="article">
            <img src="<?= $thumbnail ?>" alt="Thumbnail for <?= htmlspecialchars($news->title) ?>">
            <div class="news-content">
              <h5><?= htmlspecialchars($news->title) ?></h5>
              <h6><?= htmlspecialchars($news->category) ?> | <?= date('d M Y', strtotime($news->publish_date)) ?></h6>
              <p><?= nl2br(htmlspecialchars(substr($news->content, 0, 400))) ?>...</p>
            </div>
          </article>
          <?php 
              endforeach; 
            else: ?>
          <p>Belum ada berita untuk ditampilkan.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Statistik Boxes -->

      <!-- Today & Monthly Sell/Buy -->
      <div class="mb-4">
        <div class="info-boxes">
          <div class="info-box bg-cards-1" role="region" aria-label="Today's Sales and Purchases">
            <div class="text-center text-white">
              <h2 class="info-box-text">Today</h2>
              <span class="sell">Sell: 
                <?php 
                  $today = date('Y-m-d');
                  $stmt = $pdo->prepare("SELECT SUM(`net_total`) FROM `invoice` WHERE `order_date` = :today");
                  $stmt->execute(['today' => $today]);
                  $res = $stmt->fetch(PDO::FETCH_NUM);
                  echo $res[0] ?? '0';
                ?>
              </span>
            </div>
          </div>

          <div class="info-box bg-cards-2" role="region" aria-label="Monthly Sales and Purchases">
            <div class="text-center text-white">
              <h2 class="info-box-text">Monthly</h2>
              <span class="sell">Sell: 
                <?php 
                  $start_date = date('Y-m-01');
                  $end_date = date('Y-m-t');
                  $stmt = $pdo->prepare("SELECT SUM(`net_total`) FROM `invoice` WHERE `order_date` BETWEEN :start AND :end");
                  $stmt->execute(['start' => $start_date, 'end' => $end_date]);
                  $res = $stmt->fetch(PDO::FETCH_NUM);
                  echo $res[0] ?? '0';
                ?>
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Factory Product Stock Alert -->
      <div class="card-custom">
        <div class="card-header" style="font-weight:600; font-size:1.125rem; border-bottom:none; padding-bottom:1rem;">
          Factory Product Stock Alert
        </div>
        <div style="overflow-x:auto;">
          <table role="table" aria-label="Factory product stock alert" style="min-width: 100%;">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Quantity</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $stmt = $pdo->prepare("SELECT * FROM `factory_products` WHERE `quantity` <= `alert_quantity` ORDER BY quantity ASC");
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_OBJ);
                $count = 1;
                if ($res):
                  foreach ($res as $product):
              ?>
              <tr>
                <td><?= $count++; ?></td>
                <td><?= htmlspecialchars($product->product_id); ?></td>
                <td><?= htmlspecialchars($product->product_name); ?></td>
                <td><?= htmlspecialchars($product->quantity); ?></td>
              </tr>
              <?php 
                  endforeach;
                else: ?>
              <tr>
                <td colspan="4" style="text-align:center; padding: 1rem;">No products with low stock</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>
