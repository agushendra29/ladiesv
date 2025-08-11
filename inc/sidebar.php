<!-- Main Sidebar Container -->
<aside style="
  width: 260px;
  background-color: #ffffff; /* tema putih */
  color: #374151; /* abu gelap untuk teks */
  height: 100vh;
  position: fixed;
  top: 0; left: 0;
  overflow-y: auto;
  border-right: 1px solid #e5e7eb; /* garis tipis sebelah kanan */
  box-shadow: 2px 0 12px rgba(0,0,0,0.05);
  z-index: 1030;
  padding-bottom: 40px;
">
  <nav style="padding-top: 16px;">
    <ul style="
      list-style: none;
      margin: 0;
      padding: 0 12px;
    ">
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=dashboard" class="nav-link <?php echo $actual_link=='dashboard'?'active':'';?>" style="
          display: flex;
          align-items: center;
          gap: 14px;
          font-size: 15px !important;
          font-weight: 600;
          color: <?= ($actual_link == 'dashboard') ? '#2563eb' : '#374151' ?>;
          text-decoration: none;
          padding: 14px 18px;
          border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'dashboard') ? '#2563eb' : '#374151' ?>'">
          <img src="assets/images/logo.png" alt="logo" style="
      max-width: 140px;
      height: auto;
      filter: none; /* jangan invert, tampil natural */
    ">
        </a>
      </li>

      <!-- Dashboard -->
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=dashboard" class="nav-link <?php echo $actual_link=='dashboard'?'active':'';?>" style="
          display: flex;
          align-items: center;
          gap: 14px;
          font-size: 15px !important;
          font-weight: 600;
          color: <?= ($actual_link == 'dashboard') ? '#2563eb' : '#374151' ?>;
          text-decoration: none;
          padding: 14px 18px;
          border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'dashboard') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined"
            style="font-size:22px;"><?= ($actual_link == 'dashboard') ? 'dashboard' : 'dashboard' ?></i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- Member (if role) -->
      <?php if ($roleId <= 1): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=suppliar" class="nav-link <?php echo $actual_link=='suppliar'?'active':'';?>" style="
          display: flex;
          align-items: center;
          gap: 14px;
          font-size: 15px !important;
          font-weight: 600;
          color: <?= ($actual_link == 'suppliar') ? '#2563eb' : '#374151' ?>;
          text-decoration: none;
          padding: 14px 18px;
          border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'suppliar') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">group</i>
          <span>Member</span>
        </a>
      </li>
      <?php endif; ?>
       <?php if ($roleId < 2): ?>
      <li style="margin-bottom: 6px;">
        <a href="index.php?page=news" class="nav-link <?php echo $actual_link=='news'?'active':'';?>" style="
          display: flex;
          align-items: center;
          gap: 14px;
          font-size: 15px !important;
          font-weight: 600;
          color: <?= ($actual_link == 'news') ? '#2563eb' : '#374151' ?>;
          text-decoration: none;
          padding: 14px 18px;
          border-radius: 12px;
          transition: background-color 0.25s ease, color 0.25s ease;
          user-select:none;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'news') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">news</i>
          <span>News</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Produk with submenu -->
      <li style="margin-bottom: 6px; position: relative;">
        <input type="checkbox" id="produk-menu-toggle" style="display:none;">
        <label for="produk-menu-toggle" style="
          display: flex;
          align-items: center;
          gap: 14px;
          font-size: 15px !important;
          font-weight: 600;
          color: <?= ($actual_link == 'add_product' || $actual_link == 'product_list' || $actual_link == 'stock_management') ? '#2563eb' : '#374151' ?>;
          padding: 14px 18px;
          border-radius: 12px;
          cursor: pointer;
          user-select:none;
          transition: background-color 0.25s ease, color 0.25s ease;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'add_product' || $actual_link == 'product_list' || $actual_link == 'stock_management') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">inventory</i>
          <span>Produk</span>
          <i class="fas fa-angle-down" style="margin-left:auto; font-size:16px; transition: transform 0.3s ease;"
            id="produk-arrow"></i>
        </label>
        <ul id="produk-submenu" style="
          list-style: none;
          padding-left: 24px;
          margin: 8px 0 0 0;
          display: none;
        ">
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=product_list"
              class="nav-link <?php echo $actual_link=='product_list'?'active':'';?>" style="
              display: block;
              font-size: 14px !important;
              font-weight: 500;
              color: <?= ($actual_link == 'product_list') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none;
              padding: 10px 18px;
              border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'product_list') ? '#2563eb' : '#4b5563' ?>'">
              Daftar Produk
            </a>
          </li>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=stock_management"
              class="nav-link <?php echo $actual_link=='stock_management'?'active':'';?>" style="
              display: block;
              font-size: 14px !important;
              font-weight: 500;
              color: <?= ($actual_link == 'stock_management') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none;
              padding: 10px 18px;
              border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'stock_management') ? '#2563eb' : '#4b5563' ?>'">
              Stok Produk
            </a>
          </li>
        </ul>
      </li>

      <!-- Penjualan with submenu -->
      <li style="margin-bottom: 6px; position: relative;">
        <input type="checkbox" id="penjualan-menu-toggle" style="display:none;">
        <label for="penjualan-menu-toggle" style="
          display: flex;
          align-items: center;
          gap: 14px;
          font-size: 15px !important;
          font-weight: 600;
          color: <?= ($actual_link == 'quick_sell' || $actual_link == 'sell_order' || $actual_link == 'sell_return_list') ? '#2563eb' : '#374151' ?>;
          padding: 14px 18px;
          border-radius: 12px;
          cursor: pointer;
          user-select:none;
          transition: background-color 0.25s ease, color 0.25s ease;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'quick_sell' || $actual_link == 'sell_order' || $actual_link == 'sell_return_list') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">sell</i>
          <span>Penjualan</span>
          <i class="fas fa-angle-down" style="margin-left:auto; font-size:16px; transition: transform 0.3s ease;"
            id="penjualan-arrow"></i>
        </label>
        <ul id="penjualan-submenu" style="
          list-style: none;
          padding-left: 24px;
          margin: 8px 0 0 0;
          display: none;
        ">
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=sell_order" class="nav-link <?php echo $actual_link=='sell_order'?'active':'';?>"
              style="
              display: block;
              font-size: 14px !important;
              font-weight: 500;
              color: <?= ($actual_link == 'sell_order') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none;
              padding: 10px 18px;
              border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'sell_order') ? '#2563eb' : '#4b5563' ?>'">
              Penjualan
            </a>
          </li>
               <?php if ($roleId < 2): ?>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=refund_process"
              class="nav-link <?php echo $actual_link=='refund_process'?'active':'';?>" style="
              display: block;
              font-size: 14px !important;
              font-weight: 500;
              color: <?= ($actual_link == 'refund_process') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none;
              padding: 10px 18px;
              border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'refund_process') ? '#2563eb' : '#4b5563' ?>'">
              Proses Refund
            </a>
          </li>
               <?php endif; ?>
        </ul>
      </li>

      <!-- Pemesanan with submenu -->
      <li style="margin-bottom: 6px; position: relative;">
        <input type="checkbox" id="pemesanan-menu-toggle" style="display:none;">
        <label for="pemesanan-menu-toggle" style="
          display: flex;
          align-items: center;
          gap: 14px;
          font-size: 15px !important;
          font-weight: 600;
          color: <?= ($actual_link == 'purchase_order' || $actual_link == 'buy_refund_list') ? '#2563eb' : '#374151' ?>;
          padding: 14px 18px;
          border-radius: 12px;
          cursor: pointer;
          user-select:none;
          transition: background-color 0.25s ease, color 0.25s ease;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'purchase_order' || $actual_link == 'buy_refund_list') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">payments</i>
          <span>Pemesanan</span>
          <i class="fas fa-angle-down" style="margin-left:auto; font-size:16px; transition: transform 0.3s ease;"
            id="pemesanan-arrow"></i>
        </label>
        <ul id="pemesanan-submenu" style="
          list-style: none;
          padding-left: 24px;
          margin: 8px 0 0 0;
          display: none;
        ">
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=purchase_order"
              class="nav-link <?php echo $actual_link=='purchase_order'?'active':'';?>" style="
              display: block;
              font-size: 14px !important;
              font-weight: 500;
              color: <?= ($actual_link == 'purchase_order') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none;
              padding: 10px 18px;
              border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'purchase_order') ? '#2563eb' : '#4b5563' ?>'">
              Daftar Transaksi
            </a>
          </li>
          
               <?php if ($roleId < 2): ?>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=refund_process"
              class="nav-link <?php echo $actual_link=='refund_process'?'active':'';?>" style="
              display: block;
              font-size: 14px !important;
              font-weight: 500;
              color: <?= ($actual_link == 'refund_process') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none;
              padding: 10px 18px;
              border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'refund_process') ? '#2563eb' : '#4b5563' ?>'">
              Proses Refund
            </a>
          </li>
           <?php endif; ?>
          
        </ul>
      </li>

      <!-- Reports with submenu -->
      <li style="margin-bottom: 6px; position: relative;">
        <input type="checkbox" id="reports-menu-toggle" style="display:none;">
        <label for="reports-menu-toggle" style="
          display: flex;
          align-items: center;
          gap: 14px;
          font-size: 15px !important;
          font-weight: 600;
          color: <?= ($actual_link == 'profit_loss' || $actual_link == 'sales_report' || $actual_link == 'purchase_report' || $actual_link == 'purchase_pay_report' || $actual_link == 'sell_pay_report') ? '#2563eb' : '#374151' ?>;
          padding: 14px 18px;
          border-radius: 12px;
          cursor: pointer;
          user-select:none;
          transition: background-color 0.25s ease, color 0.25s ease;
        " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#2563eb';"
          onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'profit_loss' || $actual_link == 'sales_report' || $actual_link == 'purchase_report' || $actual_link == 'purchase_pay_report' || $actual_link == 'sell_pay_report') ? '#2563eb' : '#374151' ?>'">
          <i class="material-symbols-outlined" style="font-size:22px;">lab_profile</i>
          <span>Reports</span>
          <i class="fas fa-angle-down" style="margin-left:auto; font-size:16px; transition: transform 0.3s ease;"
            id="reports-arrow"></i>
        </label>
        <ul id="reports-submenu" style="
          list-style: none;
          padding-left: 24px;
          margin: 8px 0 0 0;
          display: none;
        ">
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=sales_report"
              class="nav-link <?php echo $actual_link=='sales_report'?'active':'';?>" style="
              display: block;
              font-size: 14px !important;
              font-weight: 500;
              color: <?= ($actual_link == 'sales_report') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none;
              padding: 10px 18px;
              border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'sales_report') ? '#2563eb' : '#4b5563' ?>'">
              Transaksi 
            </a>
          </li>
          <li style="margin-bottom: 6px;">
            <a href="index.php?page=purchase_report"
              class="nav-link <?php echo $actual_link=='purchase_report'?'active':'';?>" style="
              display: block;
              font-size: 14px !important;
              font-weight: 500;
              color: <?= ($actual_link == 'purchase_report') ? '#2563eb' : '#4b5563' ?>;
              text-decoration: none;
              padding: 10px 18px;
              border-radius: 10px;
              transition: background-color 0.25s ease, color 0.25s ease;
            " onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#2563eb';"
              onmouseout="this.style.backgroundColor=''; this.style.color='<?= ($actual_link == 'purchase_report') ? '#2563eb' : '#4b5563' ?>'">
              Purchase report
            </a>
          </li>
          <!-- Add other report items similarly -->
        </ul>
      </li>

    </ul>
  </nav>
</aside>

<script>
  // Toggle submenu display
  document.querySelectorAll('input[type="checkbox"]').forEach(input => {
    input.addEventListener('change', (e) => {
      const ul = e.target.nextElementSibling.nextElementSibling; // ul after label
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

<style>
  /* Container showing entries */
.dataTables_info {
  font-weight: 600;
  color: #444;
  font-size: 14px;
  user-select: none;
  margin-top: 25px;
}

/* Pagination container */
.dataTables_paginate {
  margin-top: 25px;
  text-align: right;
  user-select: none;
}

/* Pagination buttons */
.dataTables_paginate .paginate_button {
  background-color: #f3f4f6;
  border: none;
  border-radius: 8px;
  color: #4b5563;
  padding: 6px 20px;
  margin: 0 4px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease;
  min-width: 36px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size:14px;
}

/* Hover and focus states */
.dataTables_paginate .paginate_button:hover,
.dataTables_paginate .paginate_button:focus {
  background-color: #0073ea;
  color: white !important;
  box-shadow: 0 4px 12px rgba(0, 115, 234, 0.4);
  outline: none;
}

/* Active/current page */
.dataTables_paginate .paginate_button.current {
  background-color: #005bb5;
  color: white !important;
  box-shadow: 0 6px 18px rgba(0, 91, 181, 0.6);
}

/* Disabled buttons */
.dataTables_paginate .paginate_button.disabled {
  background-color: #e5e7eb;
  color: #9ca3af !important;
  cursor: not-allowed;
  box-shadow: none;
}

/* Next & Prev buttons icon spacing */
.dataTables_paginate .paginate_button.previous::before,
.dataTables_paginate .paginate_button.next::after {
  content: '';
  display: inline-block;
  width: 8px;
  height: 8px;
  border-top: 2px solid currentColor;
  border-right: 2px solid currentColor;
  margin: 0 6px;
  transform: rotate(-135deg);
}

/* Next button arrow rotate differently */
.dataTables_paginate .paginate_button.next::after {
  transform: rotate(45deg);
}

/* Responsive tweaks */
@media (max-width: 600px) {
  .dataTables_paginate {
    text-align: center;
  }
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
  margin-bottom: 6px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 14px;
  color: #444;
  display: flex;
  align-items: center;
  gap: 12px;
}

/* Custom style select entries */
.dataTables_wrapper .dataTables_length select {
  padding: 6px 12px;
  border-radius: 8px;
  border: 1.5px solid #ccc;
  background-color: #f9fafc;
  color: #444;
  font-weight: 600;
  transition: border-color 0.3s ease;
  cursor: pointer;
}

.dataTables_wrapper .dataTables_length select:hover,
.dataTables_wrapper .dataTables_length select:focus {
  border-color: #0073ea;
  outline: none;
  box-shadow: 0 0 6px rgba(0,115,234,0.4);
}

/* Custom style search box */
.dataTables_wrapper .dataTables_filter input {
  padding: 8px 16px;
  border-radius: 12px;
  border: 1.5px solid #ccc;
  background-color: #f9fafc;
  font-size: 14px;
  font-weight: 600;
  color: #444;
  width: 220px;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.dataTables_wrapper .dataTables_filter input::placeholder {
  color: #999;
  font-weight: 400;
}

.dataTables_wrapper .dataTables_filter input:hover,
.dataTables_wrapper .dataTables_filter input:focus {
  border-color: #0073ea;
  outline: none;
  box-shadow: 0 0 8px rgba(0,115,234,0.5);
}

/* Responsive fix - search & length side by side */
@media (min-width: 768px) {
  .dataTables_wrapper .dataTables_length {
    order: 1;
  }
  .dataTables_wrapper .dataTables_filter {
    order: 2;
  }
  .dataTables_wrapper .dataTables_length,
  .dataTables_wrapper .dataTables_filter {
    flex: 1;
  }
}

/* Label text bold */
.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label {
  font-weight: 600;
  color: #333;
  user-select: none;
}

.dataTables_wrapper .dataTables_length label {
  display: flex;
  align-items: center;
  gap: 8px; /* kasih jarak antar label dan dropdown */
  font-weight: 600;
  color: #333;
  user-select: none;
}
</style>
    <?php require_once 'inc/member_add_modal.php'; ?>
    <?php require_once 'inc/catagory_modal.php'; ?>
    <?php require_once 'inc/suppliar_modal.php'; ?>
    <?php require_once 'inc/expense_catagory_modal.php'; ?>