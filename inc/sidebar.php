<!-- Main Sidebar Container -->
<aside style="
  width: 260px;
  background-color: #ffffff;
  color: #374151;
  height: 100vh;
  position: fixed;
  top: 0; left: 0;
  overflow-y: auto;
  border-right: 1px solid #e5e7eb;
  box-shadow: 2px 0 12px rgba(0,0,0,0.05);
  z-index: 1030;
  padding-bottom: 40px;
">
  <nav style="padding-top: 16px;">
    <ul style="list-style: none; margin: 0; padding: 0 12px;">

      <!-- Logo -->
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=dashboard" style="
          display: flex; align-items: center; justify-content: center;
          padding: 14px 18px;
        ">
          <img src="assets/images/logo.png" alt="logo" style="max-width: 140px; height: auto;">
        </a>
      </li>

      <!-- Dashboard -->
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=dashboard" class="nav-link <?php echo $actual_link=='dashboard'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 15px !important; font-weight: 600;
          color: <?= ($actual_link == 'dashboard') ? '#2563eb' : '#374151' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'dashboard') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">dashboard</i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- Member -->
      <?php if ($roleId <= 1 || $roleId == 4 || $roleId == 5): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=suppliar" class="nav-link <?php echo $actual_link=='suppliar'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 15px !important; font-weight: 600;
          color: <?= ($actual_link == 'suppliar') ? '#2563eb' : '#374151' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'suppliar') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">group</i>
          <span>Member</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- News -->
      <?php if ($roleId < 2): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=news" class="nav-link <?php echo $actual_link=='news'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 15px !important; font-weight: 600;
          color: <?= ($actual_link == 'news') ? '#2563eb' : '#374151' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'news') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">news</i>
          <span>News</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Produk with toggle -->
      <li style="margin-bottom: 6px; position: relative;">
        <input type="checkbox" id="produk-menu-toggle" style="display:none;">
        <label for="produk-menu-toggle" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 15px !important; font-weight: 600;
          color: <?= ($actual_link == 'add_product' || $actual_link == 'product_list' || $actual_link == 'stock_management') ? '#2563eb' : '#374151' ?>;
          padding: 14px 18px; border-radius: 12px; cursor: pointer;
          user-select:none; transition: background-color 0.25s ease, color 0.25s ease;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'add_product' || $actual_link == 'product_list' || $actual_link == 'stock_management') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">inventory</i>
          <span>Produk</span>
          <i class="fas fa-angle-down" style="margin-left:auto; font-size:16px; transition: transform 0.3s ease;" id="produk-arrow"></i>
        </label>
        <ul id="produk-submenu" style="list-style: none; padding-left: 24px; margin: 8px 0 0 0; display: none;">
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=product_list" class="nav-link <?php echo $actual_link=='product_list'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'product_list') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'product_list') ? '#2563eb' : '#4b5563' ?>'">
              Daftar Produk
            </a>
          </li>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=stock_management" class="nav-link <?php echo $actual_link=='stock_management'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'stock_management') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'stock_management') ? '#2563eb' : '#4b5563' ?>'">
              Stok Produk
            </a>
          </li>
        </ul>
      </li>

      <!-- Penjualan -->
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=sell_order" class="nav-link <?php echo $actual_link=='sell_order'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 15px !important; font-weight: 600;
          color: <?= ($actual_link == 'sell_order') ? '#2563eb' : '#374151' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'sell_order') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">sell</i>
          <span>Penjualan</span>
        </a>
      </li>

      <!-- Pemesanan -->
       
      <?php if ($roleId <= 3): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=purchase_order" class="nav-link <?php echo $actual_link=='purchase_order'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 15px !important; font-weight: 600;
          color: <?= ($actual_link == 'purchase_order') ? '#2563eb' : '#374151' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'purchase_order') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">add_shopping_cart</i>
          <span>Pemesanan</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Proses Refund -->
       
      <?php if ($roleId <=1): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=refund_process" class="nav-link <?php echo $actual_link=='refund_process'?'active':'';?>" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 15px !important; font-weight: 600;
          color: <?= ($actual_link == 'refund_process') ? '#2563eb' : '#374151' ?>;
          text-decoration: none; padding: 14px 18px; border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'refund_process') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">restart_alt</i>
          <span>Proses Refund</span>
        </a>
      </li>
      
      <?php endif; ?>

      <!-- Reports -->
      <li style="margin-bottom: 6px; position: relative;">
        <input type="checkbox" id="reports-menu-toggle" style="display:none;">
        <label for="reports-menu-toggle" style="
          display: flex; align-items: center; gap: 14px;
          font-size: 15px !important; font-weight: 600;
          color: <?= ($actual_link == 'sales_report' || $actual_link == 'purchase_report') ? '#2563eb' : '#374151' ?>;
          padding: 14px 18px; border-radius: 12px; cursor: pointer;
          user-select:none; transition: background-color 0.25s ease, color 0.25s ease;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'sales_report' || $actual_link == 'purchase_report') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">lab_profile</i>
          <span>Reports</span>
          <i class="fas fa-angle-down" style="margin-left:auto; font-size:16px; transition: transform 0.3s ease;" id="reports-arrow"></i>
        </label>
        <ul id="reports-submenu" style="list-style: none; padding-left: 24px; margin: 8px 0 0 0; display: none;">
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=sales_report" class="nav-link <?php echo $actual_link=='sales_report'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'sales_report') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'sales_report') ? '#2563eb' : '#4b5563' ?>'">
              Transaksi
            </a>
          </li>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=stock_monitoring" class="nav-link <?php echo $actual_link=='stock_monitoring'?'active':'';?>" style="
              display: block; font-size: 14px !important; font-weight: 500;
              color: <?= ($actual_link == 'stock_monitoring') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none; padding: 10px 18px; border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'stock_monitoring') ? '#2563eb' : '#4b5563' ?>'">
              Stock Monitoring
            </a>
          </li>
        </ul>
      </li>

    </ul>
  </nav>
</aside>

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
    <?php require_once 'inc/expense_catagory_modal.php'; ?>
