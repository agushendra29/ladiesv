<div style="padding-top:52px; padding-bottom:52px;">
  <section class="content">
    <div class="container-fluid">
      <div class="section-card-body">
        <div class="page-header-custom">
          <div class="section-title">Daftar Stok Produk</div>
          <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
          <a href="index.php?page=add_stock_management" target="_blank" class="btn-custom">
            <i class="fas fa-plus"></i> Tambah Stok
          </a>
          <?php endif; ?>
        </div>

        <!-- Table Stok -->
        <div>
          <div class="table-responsive">
            <table id="stockManagementTable" class="display dataTable text-center custom-table">
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
        </div>

        <!-- Card view untuk mobile -->
        <div id="mobileStockCards"></div>
      </div>
    </div>

    <?php if ($_SESSION['role_id'] == 10): ?>
    <!-- Riwayat Log Perubahan Stok -->
    <div class="container-fluid" style="margin-top: 20px;">
      <div class="log-block">
        <div class="header-block">
          <div class="section-title">Riwayat Perubahan Stok</h2>
        </div>

        <div class="table-responsive">
          <table id="stockLogsTable" class="display dataTable text-center custom-table">
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
  .page-header-custom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 28px;
  flex-wrap: wrap;
  gap: 12px;
}
.page-header-custom h2 {
  margin: 0;
  font-size: 26px;
  font-weight: 600;
  color: #222;
  user-select: none;
}
.btn-custom {
  background-color: #EB4F7D;
  color: #fff;
  border-radius: 12px;
  padding: 8px 20px;
  font-weight: 600;
  font-size: 12px;
  border: none;
  box-shadow: 0 4px 12px rgba(0,115,234,0.4);
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  text-decoration: none;
}
.btn-custom:hover {
  background-color: #005bb5;
  box-shadow: 0 6px 18px rgba(0,91,181,0.5);
}

/* Table */
.custom-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 10px;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 18px rgba(0,0,0,0.07);
  background: #fff;
  font-size: 11px !important;
}
.custom-table thead {
  background-color: #EEA0A0;
  color: #fff;
  text-transform: uppercase;
  font-weight: 700;
  letter-spacing: 0.05em;
  font-size: 9px !important;
}
.custom-table th {
  padding: 10px 14px;
  line-height: 1.3;
  font-size: 10px !important;
  text-align:center !important; 
}
.custom-table td {
  padding: 14px 22px;
  vertical-align: middle;
  text-align: center;
}
#stockManagementTable tbody tr {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
#stockManagementTable tbody tr:hover {
  background-color: #eff6ff;
  box-shadow: 0 6px 20px rgba(0,115,234,0.15);
}

#stockLogsTable tbody tr {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
#stockLogsTable tbody tr:hover {
  background-color: #eff6ff;
  box-shadow: 0 6px 20px rgba(0,115,234,0.15);
}


#mobileLogCards {
  display: none;
}
#mobileLogCards .card-item {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  padding: 12px;
  margin-bottom: 8px;
  font-size: 12px;
  width: 100%;
  max-width: 400px;
}
#mobileLogCards .card-item h4 {
  font-size: 14px;
  margin-bottom: 4px;
  font-weight: 600;
}
#mobileLogCards .card-item .meta {
  font-size: 11px;
  color: #555;
  margin-bottom: 2px;
}
#mobileLogCards .card-item .actions {
  margin-top: 6px;
  display: flex;
  flex-wrap: nowrap;
  gap: 6px;
  justify-content: flex-start;
}
#mobileLogCards .card-item .actions .btn {
  padding: 4px 8px;
  font-size: 11px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  white-space: nowrap;
  margin-bottom: 0;
}
/* Mobile Cards */
#mobileStockCards {
  display: none;
}
#mobileStockCards .card-item {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  padding: 12px;
  margin-bottom: 8px;
  font-size: 12px;
  width: 100%;
  max-width: 400px;
}
#mobileStockCards .card-item h4 {
  font-size: 14px;
  margin-bottom: 4px;
  font-weight: 600;
}
#mobileStockCards .card-item .meta {
  font-size: 11px;
  color: #555;
  margin-bottom: 2px;
}
#mobileStockCards .card-item .actions {
  margin-top: 6px;
  display: flex;
  flex-wrap: nowrap;
  gap: 6px;
  justify-content: flex-start;
}
#mobileStockCards .card-item .actions .btn {
  padding: 4px 8px;
  font-size: 11px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  white-space: nowrap;
  margin-bottom: 0;
}

/* Responsif */
@media (max-width: 768px) {
  .page-header-custom h2 { font-size: 20px; }
  .btn-custom { width: 100%; justify-content: center; }
  /* sembunyikan isi tabel tapi biarkan wrapper DataTables tampil */
  #stockManagementTable thead tr, #stockManagementTable tbody tr td { display: none; }
  #mobileStockCards { display: flex; flex-direction: column; align-items: center; gap: 12px; margin-top: 12px; }
  #mobileStockCards .card-item { width: 100%; max-width: 400px; }
  .dataTables_wrapper .dataTables_filter,
  .dataTables_wrapper .dataTables_paginate,
  .dataTables_wrapper .dataTables_length {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin: 8px 0;
    gap: 8px;
    font-size: 12px;
  }
  .dataTables_wrapper .dataTables_filter input { width: 100%; max-width: 300px; }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">