<!-- Main Sidebar Container -->
<aside  id="sidebar" class="sidebar">
  <nav style="padding-top: 16px;">
    <ul style="list-style: none; margin: 0; padding: 0 12px;">
      <!-- Dashboard -->
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=dashboard" class="nav-link <?php echo $actual_link=='dashboard'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 14px !important; font-weight: 400;
          color: <?= ($actual_link == 'dashboard') ? '#D92765' : '#D92765' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#D92765';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'dashboard') ? '#D92765' : '#D92765' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">dashboard</i>
          <span>Dashboard</span>
        </a>
      </li>
      <!-- News -->
      <?php if ($roleId < 2 || $roleId == 10): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=news" class="nav-link <?php echo $actual_link=='news'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 14px !important; font-weight: 400;
          color: <?= ($actual_link == 'news') ? '#D92765' : '#D92765' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#D92765';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'news') ? '#D92765' : '#D92765' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">news</i>
          <span>News</span>
        </a>
      </li>
      <?php endif; ?>
      <?php if ($roleId != 5): ?>
      <li style="margin-bottom: 6px; position: relative;">
        <input type="checkbox" id="user-menu-toggle" style="display:none;">
        <label for="user-menu-toggle" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 14px !important; font-weight: 400;
          color: <?= ($actual_link == 'distributor_management' || $actual_link == 'suppliar' || $actual_link == 'add_register_reseller') ? '#D92765' : '#D92765' ?>;
          padding: 14px 18px; border-radius: 12px; cursor: pointer;
          user-select:none; transition: background-color 0.25s ease, color 0.25s ease;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#D92765';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'distributor_management' || $actual_link == 'suppliar' || $actual_link == 'add_register_reseller') ? '#D92765' : '#D92765' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">inventory</i>
          <span>Pendaftaran</span>
          <i class="fas fa-angle-down" style="margin-left:auto; font-size:16px; transition: transform 0.3s ease;" id="produk-arrow"></i>
        </label>
        <ul id="user-submenu" style="list-style: none; padding-left: 24px; margin: 8px 0 0 0; display: none;">
          <?php if ($roleId == 1 || $roleId == 10 || $roleId == 2): ?>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=suppliar" class="nav-link <?php echo $actual_link=='suppliar'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'suppliar') ? '#D92765' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#D92765';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'suppliar') ? '#D92765' : '#4b5563' ?>'">
              Anggota
            </a>
          </li>
          <?php endif; ?>
          <?php if ($roleId != 5): ?>
           <li style="margin-bottom: 6px;">
            <a href="index.php?page=add_register_reseller" class="nav-link <?php echo $actual_link=='add_register_reseller'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'add_register_reseller') ? '#D92765' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#D92765';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'add_register_reseller') ? '#D92765' : '#4b5563' ?>'">
              Reseller
            </a>
          </li>
          <?php endif; ?>
          <?php if ($roleId == 1 || $roleId == 10): ?>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=distributor_management" class="nav-link <?php echo $actual_link=='distributor_management'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'distributor_management') ? '#D92765' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#D92765';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'distributor_management') ? '#D92765' : '#4b5563' ?>'">
              HD Sponsorisasi
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </li>
      <?php endif; ?>
       <?php if ($roleId ==5): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=suppliar" class="nav-link <?php echo $actual_link=='suppliar'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 14px !important; font-weight: 400;
          color: <?= ($actual_link == 'suppliar') ? '#D92765' : '#D92765' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#D92765';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'suppliar') ? '#D92765' : '#D92765' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">sell</i>
          <span>Daftar Distributor</span>
        </a>
      </li>
      <?php endif ?>


      <!-- Produk with toggle -->
      <li style="margin-bottom: 6px; position: relative;">
        <input type="checkbox" id="produk-menu-toggle" style="display:none;">
        <label for="produk-menu-toggle" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 14px !important; font-weight: 400;
          color: <?= ($actual_link == 'add_product' || $actual_link == 'product_list' || $actual_link == 'stock_management') ? '#D92765' : '#D92765' ?>;
          padding: 14px 18px; border-radius: 12px; cursor: pointer;
          user-select:none; transition: background-color 0.25s ease, color 0.25s ease;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#D92765';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'add_product' || $actual_link == 'product_list' || $actual_link == 'stock_management') ? '#D92765' : '#D92765' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">inventory</i>
          <span>Produk</span>
          <i class="fas fa-angle-down" style="margin-left:auto; font-size:16px; transition: transform 0.3s ease;" id="produk-arrow"></i>
        </label>
        <ul id="produk-submenu" style="list-style: none; padding-left: 24px; margin: 8px 0 0 0; display: none;">
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=product_list" class="nav-link <?php echo $actual_link=='product_list'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'product_list') ? '#D92765' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#D92765';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'product_list') ? '#D92765' : '#4b5563' ?>'">
              Daftar Produk
            </a>
          </li>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=stock_management" class="nav-link <?php echo $actual_link=='stock_management'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'stock_management') ? '#D92765' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#D92765';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'stock_management') ? '#D92765' : '#4b5563' ?>'">
              Stok Produk
            </a>
          </li>
        </ul>
      </li>

      <!-- Penjualan -->
          <?php if ($roleId !=5): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=sell_order" class="nav-link <?php echo $actual_link=='sell_order'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 14px !important; font-weight: 400;
          color: <?= ($actual_link == 'sell_order') ? '#D92765' : '#D92765' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#D92765';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'sell_order') ? '#D92765' : '#D92765' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">sell</i>
          <span>Penjualan</span>
        </a>
      </li>
      <?php endif ?>

      <!-- Pemesanan -->
       
      <?php if ($roleId <= 3 || $roleId == 10): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=purchase_order" class="nav-link <?php echo $actual_link=='purchase_order'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 14px !important; font-weight: 400;
          color: <?= ($actual_link == 'purchase_order') ? '#D92765' : '#D92765' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#D92765';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'purchase_order') ? '#D92765' : '#D92765' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">add_shopping_cart</i>
          <span>Pemesanan</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Proses Refund -->
       
      <?php if ($roleId == 10): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=refund_order" class="nav-link <?php echo $actual_link=='refund_order'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 14px !important; font-weight: 400;
          color: <?= ($actual_link == 'refund_order') ? '#D92765' : '#D92765' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#D92765';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'refund_order') ? '#D92765' : '#D92765' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">restart_alt</i>
          <span>Cancel Invoice</span>
        </a>
      </li>
      
      <?php endif; ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=reward_list" class="nav-link <?php echo $actual_link=='reward_list'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 14px !important; font-weight: 400;
          color: <?= ($actual_link == 'reward_list') ? '#D92765' : '#D92765' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#D92765';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'reward_list') ? '#D92765' : '#D92765' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">star</i>
          <span>Promo Rewards</span>
        </a>
      </li>
      <!-- Reports -->
      <li style="margin-bottom: 6px; position: relative;">
        <input type="checkbox" id="reports-menu-toggle" style="display:none;">
        <label for="reports-menu-toggle" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 14px !important; font-weight: 400;
          color: <?= ($actual_link == 'sales_report' || $actual_link == 'purchase_report') ? '#D92765' : '#D92765' ?>;
          padding: 14px 18px; border-radius: 12px; cursor: pointer;
          user-select:none; transition: background-color 0.25s ease, color 0.25s ease;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#D92765';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'sales_report' || $actual_link == 'purchase_report') ? '#D92765' : '#D92765' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">lab_profile</i>
          <span>Reports</span>
          <i class="fas fa-angle-down" style="margin-left:auto; font-size:16px; transition: transform 0.3s ease;" id="reports-arrow"></i>
        </label>
        <ul id="reports-submenu" style="list-style: none; padding-left: 24px; margin: 8px 0 0 0; display: none;">
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=sales_report" class="nav-link <?php echo $actual_link=='sales_report'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'sales_report') ? '#D92765' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#D92765';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'sales_report') ? '#D92765' : '#4b5563' ?>'">
              Transaksi
            </a>
          </li>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=stock_monitoring" class="nav-link <?php echo $actual_link=='stock_monitoring'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'stock_monitoring') ? '#D92765' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#D92765';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'stock_monitoring') ? '#D92765' : '#4b5563' ?>'">
              Stock Monitoring
            </a>
          </li>
            <?php if ($roleId <= 2): ?>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=distributor_report" class="nav-link <?php echo $actual_link=='distributor_report'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'distributor_report') ? '#D92765' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#D92765';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'distributor_report') ? '#D92765' : '#4b5563' ?>'">
              Penjualan Distributor 
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </li>

    </ul>
  </nav>
</aside>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
  document.querySelectorAll('input[type="checkbox"]').forEach(input => {
    input.addEventListener('change', (e) => {
      const ul = e.target.nextElementSibling.nextElementSibling;
      const arrow = e.target.nextElementSibling.querySelector('i.fas.fa-angle-down');
      if (e.target.checked) {
        ul.style.display = 'block';
        if (arrow) arrow.style.transform = 'rotate(180deg)';
      } else {
        ul.style.display = 'none';
        if (arrow) arrow.style.transform = 'rotate(0deg)';
      }
    });
  });
</script>

  <?php require_once 'inc/member_add_modal.php'; ?>
    <?php require_once 'inc/catagory_modal.php'; ?>
    <?php require_once 'inc/suppliar_modal.php'; ?>
