<!-- Content Wrapper -->
<div style="padding-top:56px;">
  <!-- Product List Page -->
  <section class="content">
    <div class="container-fluid">
      <div class="section-card-body">
        <!-- Header -->
        <div class="page-header-custom">
          <div class="section-title">Daftar Produk</div>
          <?php if ($_SESSION['role_id'] <= 1 || $_SESSION['role_id'] == 10): ?>
          <a href="index.php?page=add_product" class="btn-custom">
            <i class="fas fa-plus"></i> Tambah Produk
          </a>
          <?php endif; ?>
        </div>
        <!-- Table untuk desktop -->
        <div class="table-responsive">
          <table id="productTable" class="display dataTable text-center custom-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- Rows dari server -->
            </tbody>
          </table>
        </div>

        <!-- Card view untuk mobile -->
        <div id="mobileProductCards"></div>
      </div>
    </div>
  </section>
</div>

<!-- CSS -->
<style>
/* Header */
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
#productTable tbody tr {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
#productTable tbody tr:hover {
  background-color: #eff6ff;
  box-shadow: 0 6px 20px rgba(0,115,234,0.15);
}

/* Mobile Cards */
#mobileProductCards {
  display: none;
}
#mobileProductCards .card-item {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  padding: 12px;
  margin-bottom: 8px;
  font-size: 12px;
  width: 100%;
  max-width: 400px;
}
#mobileProductCards .card-item h4 {
  font-size: 14px;
  margin-bottom: 4px;
  font-weight: 600;
}
#mobileProductCards .card-item .meta {
  font-size: 11px;
  color: #555;
  margin-bottom: 2px;
}
#mobileProductCards .card-item .actions {
  margin-top: 6px;
  display: flex;
  flex-wrap: nowrap;
  gap: 6px;
  justify-content: flex-start;
}
#mobileProductCards .card-item .actions .btn {
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
  #productTable thead tr, #productTable tbody tr td { display: none; }
  #mobileProductCards { display: flex; flex-direction: column; align-items: center; gap: 12px; margin-top: 12px; }
  #mobileProductCards .card-item { width: 100%; max-width: 400px; }
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

<!-- jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

