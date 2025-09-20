<!-- Main Sidebar Container -->
<aside id="sidebar" class="sidebar">
  <nav class="sidebar-nav">
    <ul class="menu-list">
      <!-- Dashboard -->
      <li>
        <a href="index.php?page=dashboard"
           class="nav-link <?php echo $actual_link=='dashboard'?'active':'';?>">
          <i class="material-symbols-outlined">dashboard</i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- News -->
      <?php if ($roleId < 2 || $roleId == 10): ?>
      <li>
        <a href="index.php?page=news"
           class="nav-link <?php echo $actual_link=='news'?'active':'';?>">
          <i class="material-symbols-outlined">news</i>
          <span>News</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if ($roleId != 5): ?>
      <!-- Pendaftaran -->
      <li class="has-submenu">
        <input type="checkbox" id="user-menu-toggle">
        <label style="font-weight:600;" for="user-menu-toggle" class="nav-label <?php echo in_array($actual_link,['distributor_management','suppliar','add_register_reseller'])?'active':'';?>">
          <i class="material-symbols-outlined">inventory</i>
          <span>Pendaftaran</span>
          <i class="fas fa-angle-down submenu-arrow"></i>
        </label>
        <ul class="submenu">
          <?php if ($roleId == 1 || $roleId == 10 || $roleId == 2): ?>
          <li>
            <a href="index.php?page=suppliar"
               class="nav-sublink <?php echo $actual_link=='suppliar'?'active':'';?>">
              Anggota
            </a>
          </li>
          <?php endif; ?>
          <?php if ($roleId != 5): ?>
          <li>
            <a href="index.php?page=add_register_reseller"
               class="nav-sublink <?php echo $actual_link=='add_register_reseller'?'active':'';?>">
              Reseller
            </a>
          </li>
          <?php endif; ?>
          <?php if ($roleId == 1 || $roleId == 10): ?>
          <li>
            <a href="index.php?page=distributor_management"
               class="nav-sublink <?php echo $actual_link=='distributor_management'?'active':'';?>">
              HD Sponsorisasi
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </li>
      <?php endif; ?>

      <?php if ($roleId == 4): ?>
      <li>
        <a href="index.php?page=suppliar"
           class="nav-link <?php echo $actual_link=='suppliar'?'active':'';?>">
          <i class="material-symbols-outlined">sell</i>
          <span>Daftar Distributor</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Produk -->
      <li class="has-submenu">
        <input type="checkbox" id="produk-menu-toggle">
        <label style="font-weight:600;" for="produk-menu-toggle" class="nav-label <?php echo in_array($actual_link,['add_product','product_list','stock_management'])?'active':'';?>">
          <i class="material-symbols-outlined">inventory</i>
          <span>Produk</span>
          <i class="fas fa-angle-down submenu-arrow"></i>
        </label>
        <ul class="submenu">
          <li>
            <a href="index.php?page=product_list"
               class="nav-sublink <?php echo $actual_link=='product_list'?'active':'';?>">
              Daftar Produk
            </a>
          </li>
          <?php if ($roleId != 5): ?>
          <li>
            <a href="index.php?page=stock_management"
               class="nav-sublink <?php echo $actual_link=='stock_management'?'active':'';?>">
              Stok Produk
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </li>

      <!-- Penjualan -->
      <?php if ($roleId != 5): ?>
      <li>
        <a href="index.php?page=sell_order"
           class="nav-link <?php echo $actual_link=='sell_order'?'active':'';?>">
          <i class="material-symbols-outlined">sell</i>
          <span>Penjualan</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Pemesanan -->
      <?php if ($roleId <= 3 || $roleId == 10): ?>
      <li>
        <a href="index.php?page=purchase_order"
           class="nav-link <?php echo $actual_link=='purchase_order'?'active':'';?>">
          <i class="material-symbols-outlined">add_shopping_cart</i>
          <span>Pemesanan</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Refund -->
      <?php if ($roleId == 10): ?>
      <li>
        <a href="index.php?page=refund_order"
           class="nav-link <?php echo $actual_link=='refund_order'?'active':'';?>">
          <i class="material-symbols-outlined">restart_alt</i>
          <span>Cancel Invoice</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Rewards -->
      <li>
        <a href="index.php?page=reward_list"
           class="nav-link <?php echo $actual_link=='reward_list'?'active':'';?>">
          <i class="material-symbols-outlined">star</i>
          <span>Promo Rewards</span>
        </a>
      </li>

      <!-- Reports -->
      <li class="has-submenu">
        <input type="checkbox" id="reports-menu-toggle">
        <label style="font-weight:600;" for="reports-menu-toggle" class="nav-label <?php echo in_array($actual_link,['sales_report','purchase_report','stock_monitoring','distributor_report'])?'active':'';?>">
          <i class="material-symbols-outlined">lab_profile</i>
          <span>Reports</span>
          <i class="fas fa-angle-down submenu-arrow"></i>
        </label>
        <ul class="submenu">
          <li>
            <a href="index.php?page=sales_report"
               class="nav-sublink <?php echo $actual_link=='sales_report'?'active':'';?>">
              Transaksi
            </a>
          </li>
          <?php if ($roleId != 5): ?>
          <li>
            <a href="index.php?page=stock_monitoring"
               class="nav-sublink <?php echo $actual_link=='stock_monitoring'?'active':'';?>">
              Stock Monitoring
            </a>
          </li>
          <?php endif; ?>
          <?php if ($roleId == 2): ?>
          <li>
            <a href="index.php?page=distributor_report"
               class="nav-sublink <?php echo $actual_link=='distributor_report'?'active':'';?>">
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

<style>
/* Sidebar Layout */
.sidebar {
  background: #F7CECE;
  width: 250px;
  min-height: 100vh;
  padding-top:15px;
}
.sidebar-nav {
  padding: 0 10px;
}
.menu-list {
  list-style: none;
  margin: 0;
  padding: 0 12px;
}
.menu-list li {
  margin-bottom: 6px;
}
.nav-link, .nav-label {
  display: flex;
  align-items: center;
  gap: 14px;
  font-size: 14px;
  font-weight: 600;
  padding: 10px 15px;
  border-radius: 12px;
  color: #000;
  text-decoration: none;
  transition: background-color 0.25s ease, color 0.25s ease;
  user-select: none;
}

.material-symbols-outlined {
  font-size: 22px;
  padding-left: 10px;   /* ← beri jarak kiri */
  box-sizing: content-box;
}
.nav-link:hover,
.nav-label:hover {
  background-color: #D92765;
  color: white;
}
.nav-link.active,
.nav-label.active {
  background-color: #D92765;
  color: #fff !important;
}
.material-symbols-outlined {
  font-size: 22px;
}

/* Submenu */
.has-submenu input[type="checkbox"] {
  display: none;
}
.submenu {
  list-style: none;
  padding-left: 24px;
  margin: 8px 0 0 0;
  display: none;
}
.nav-sublink {
  display: block;
  font-size: 12px;
  font-weight: 500;
  color: #4b5563;
  text-decoration: none;
  padding: 10px 18px;
  border-radius: 10px;
  transition: background-color 0.25s ease, color 0.25s ease;
}
.nav-sublink:hover {
  color: white;
  background: #D92765;
  font-size:13px;
}
.nav-sublink.active {
  background-color: #D92765;
  color: #fff !important;
  font-size:13px;
}
.submenu-arrow {
  margin-left: auto;
  font-size: 16px;
  transition: transform 0.3s ease;
}
</style>

<script>
document.querySelectorAll('input[type="checkbox"]').forEach(input => {
  input.addEventListener('change', e => {
    const ul = e.target.nextElementSibling.nextElementSibling;
    const arrow = e.target.nextElementSibling.querySelector('.submenu-arrow');
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
