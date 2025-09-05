<div style="padding-top:52px; padding-bottom:52px; background-color: #f6f7fb;">
  <!-- Stok Produk Page -->
  <section class="content">
    <div class="container-fluid">
      <div>
        <!-- Header -->
        <div class="header-block">
          <h2>📦 DAFTAR STOK PRODUK</h2>
          <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
          <a href="index.php?page=add_stock_management" target="_blank" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Stok
          </a>
          <?php endif; ?>
        </div>

        <!-- Table Stok -->
        <div class="table-responsive" style="overflow-x:auto;">
          <table id="stockManagementTable" class="display dataTable text-center stock-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Distributor</th>
                <th>Jumlah Stok</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <!-- Card view untuk mobile -->
      <?php if ($_SESSION['role_id'] == 10): ?>
        <div id="mobileStockCards"></div>
      <?php endif; ?>
      </div>
    </div>

    <?php if ($_SESSION['role_id'] == 10): ?>
    <!-- Riwayat Log Perubahan Stok -->
    <div class="container-fluid" style="margin-top: 20px;">
      <div class="log-block">
        <div class="header-block">
          <h2>📝 RIWAYAT PERUBAHAN STOK</h2>
        </div>

        <div class="table-responsive" style="overflow-x:auto;">
          <table id="stockLogsTable" class="display dataTable text-center stock-table">
            <thead>
              <tr>
                <th>Distributor</th>
                <th>Produk</th>
                <th>Aksi</th>
                <th>+/-</th>
                <th>Jumlah Sebelumnya</th>
                <th>Jumlah Baru</th>
                <th>Diubah Oleh</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <!-- Card view untuk mobile -->
        <div id="mobileLogCards"></div>
      </div>
    </div>
    <?php endif; ?>
  </section>
</div>

<style>
/* Header block */
.header-block {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 24px;
}
.header-block h2 {
  margin: 0;
  font-size: 20px;
  font-weight: 600;
  color: #222;
  user-select: none;
}
.btn-add {
  background-color: #0073ea;
  color: #fff;
  border-radius: 10px;
  padding: 8px 18px;
  font-weight: 600;
  font-size: 12px;
  text-decoration: none;
  box-shadow: 0 4px 12px rgba(0,115,234,0.4);
  display: inline-flex;
  align-items: center;
  gap: 6px;
  width: fit-content;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
.btn-add:hover {
  background-color: #005bb5;
  box-shadow: 0 6px 18px rgba(0,91,181,0.5);
}

/* Table styling */
.stock-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 10px;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 18px rgba(0,0,0,0.07);
  background: #fff;
  font-size: 12px !important;
  min-width: 900px;
}
.stock-table thead {
  background-color: #2563eb;
  color: #fff;
  text-transform: uppercase;
  font-weight: 600;
  letter-spacing: 0.04em;
  font-size: 10px !important;
}
.stock-table th { font-size:10px !important; }
.stock-table td { padding: 12px 18px; vertical-align: middle; text-align: center; }
.stock-table tbody tr {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
.stock-table tbody tr:hover {
  background-color: #eff6ff;
  box-shadow: 0 6px 20px rgba(0,115,234,0.15);
}

/* Card views */
#mobileStockCards, #mobileLogCards {
  display: none;
}
#mobileStockCards .card-item, #mobileLogCards .card-item {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  padding: 12px;
  margin-bottom: 8px;
  font-size: 12px;
  width: 100%;
  max-width: 400px;
}
#mobileStockCards .card-item h4, #mobileLogCards .card-item h4 {
  font-size: 14px;
  margin-bottom: 4px;
  font-weight: 600;
}
#mobileStockCards .card-item .meta, #mobileLogCards .card-item .meta {
  font-size: 11px;
  color: #555;
  margin-bottom: 2px;
}
#mobileStockCards .card-item .actions, #mobileLogCards .card-item .actions {
  margin-top: 6px;
  display: flex;
  flex-wrap: nowrap;
  gap: 6px;
  justify-content: flex-start;
}
#mobileStockCards .card-item .actions .btn, #mobileLogCards .card-item .actions .btn {
  padding: 4px 8px;
  font-size: 11px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  white-space: nowrap;
}

/* Responsif */
@media (max-width: 768px) {
  .header-block h2 { font-size: 16px; }
  .btn-add { width: 100%; justify-content: center; }
  .stock-table { font-size: 11px !important; min-width: unset; }
  .stock-table th { font-size: 9px !important; }
  .stock-table td { font-size: 11px !important; }

  /* Hide table, show cards */
  #stockManagementTable thead, #stockManagementTable tbody,
  #stockLogsTable thead, #stockLogsTable tbody { display: none; }
  #mobileStockCards, #mobileLogCards { display: flex; flex-direction: column; align-items: center; gap: 12px; margin-top: 12px; }

  .dataTables_wrapper .dataTables_filter,
  .dataTables_wrapper .dataTables_paginate,
  .dataTables_wrapper .dataTables_length {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      margin: 8px 0;
      gap: 8px;
      font-size: 12px;
      text-align:center;
  }
  .dataTables_wrapper .dataTables_filter input { width: 100%; max-width: 300px; }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

