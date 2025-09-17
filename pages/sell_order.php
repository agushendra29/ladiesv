<div style="padding-top:52px; padding-bottom:52px;">
  <!-- Sell Order Page -->
  <section class="content">
    <div class="container-fluid">
      <div class="section-card-body">
        <!-- Header -->
        <div class="page-header-custom">
          <div class="section-title">
            Daftar Penjualan
          </div>
          <a href="index.php?page=add_sell_order" class="btn-custom">
            <i class="fas fa-plus"></i> Tambah Penjualan
          </a>
        </div>
        <div class="table-responsive">
          <table id="sellOrderTable" class="display dataTable text-center custom-table">
            <thead>
              <tr>
                <th>No Invoice</th>
                <th>DiProses Oleh</th>
                <th>DiPesan Oleh</th>
                <th>Total Bayar</th>
                <th>Tanggal Penjualan</th>
                <th>Produk</th>
              </tr>
            </thead>
            <tbody>
              <!-- DataTables inject rows -->
            </tbody>
          </table>
        </div>

        <!-- Mobile Sell Orders Cards -->
        <div id="mobileSellOrders"></div>

      </div>
    </div>
  </section>
</div>

<style>
 
#sellOrderTable tbody tr {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
#sellOrderTable tbody tr:hover {
  background-color: #eff6ff;
  box-shadow: 0 6px 20px rgba(0,115,234,0.15);
}

/* Mobile Cards */
#mobileSellOrders {
  display: none;
}
#mobileSellOrders .card-item {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  padding: 12px;
  margin-bottom: 8px;
  font-size: 12px;
  width: 100%;
  max-width: 400px;
}
#mobileSellOrders .card-item h4 {
  font-size: 14px;
  margin-bottom: 4px;
  font-weight: 600;
}
#mobileSellOrders .card-item .meta {
  font-size: 11px;
  color: #555;
  margin-bottom: 2px;
}
#mobileSellOrders .card-item .actions {
  margin-top: 6px;
  display: flex;
  flex-wrap: nowrap;
  gap: 6px;
  justify-content: flex-start;
}
#mobileSellOrders .card-item .actions .btn {
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
  #sellOrderTable thead tr, #sellOrderTable tbody tr td { display: none; }
  #mobileSellOrders { display: flex; flex-direction: column; align-items: center; gap: 12px; margin-top: 12px; }
  #mobileSellOrders .card-item { width: 100%; max-width: 400px; }
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