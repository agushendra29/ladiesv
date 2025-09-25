<div style="padding-top:52px; padding-bottom:52px;">
  <section class="content">
    <div class="container-fluid">
      <div class="section-card-body">
        <!-- Header -->
        <div class="page-header-custom" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
          <div class="section-title">Daftar Penjualan</div>
          <a href="index.php?page=add_sell_order" class="btn-custom">
            <i class="fas fa-plus"></i> Tambah Penjualan
          </a>
        </div>

        <!-- 🔎 Filter Periode -->
        <div class="filter-box">
  <label for="start_date" class="mb-0 fw-bold">Periode:</label>
  <input type="date" id="start_date" class="form-control">
  <span class="range-label">s/d</span>
  <input type="date" id="end_date" class="form-control">
  <button id="filterBtn" class="btn-custom">Filter</button>
</div>

        <!-- Table -->
        <div class="table-responsive">
          <table id="sellOrderTable" class="display dataTable text-center custom-table" style="width:100%;">
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
            <tbody><!-- DataTables inject rows --></tbody>
          </table>
        </div>

        <!-- Mobile Sell Orders Cards -->
        <div id="mobileSellOrders"></div>
      </div>
    </div>
  </section>
</div>

<style>

  .filter-box {
  margin: 20px 0;
  display: flex;
  flex-wrap: wrap;           /* elemen akan turun ke baris berikutnya */
  gap: 10px;
  align-items: center;
  font-size: 14px;
}

.filter-box .form-control {
  max-width: 200px;
  font-size: 12px;
}

/* Mobile view */
@media (max-width: 576px) {
  .filter-box {
    flex-direction: column;  /* stack vertical */
    align-items: stretch;    /* isi lebar penuh */
  }
  .filter-box label,
  .filter-box .form-control,
  .filter-box .range-label,
  .filter-box button {
    width: 100%;
    max-width: 100%;
  }
  .filter-box .range-label {
    text-align: center;      /* tengah untuk teks "s/d" */
  }
}
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

/* Filter Box */
.filter-box label { font-weight:600; }

/* Mobile Cards */
#mobileSellOrders { display: none; }
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
#mobileSellOrders .card-item h4 { font-size: 14px; margin-bottom: 4px; font-weight: 600; }
#mobileSellOrders .card-item .meta { font-size: 11px; color: #555; margin-bottom: 2px; }

/* Responsif */
@media (max-width: 768px) {
  .page-header-custom h2 { font-size: 20px; }
  .btn-custom { width: 100%; justify-content: center; }
  #sellOrderTable thead tr, #sellOrderTable tbody tr td { display: none; }
  #mobileSellOrders { display: flex; flex-direction: column; align-items: center; gap: 12px; margin-top: 12px; }
  #mobileSellOrders .card-item { width: 100%; max-width: 400px; }
  .dataTables_wrapper .dataTables_filter,
  .dataTables_wrapper .dataTables_paginate,
  .dataTables_wrapper .dataTables_length {
    display: flex; justify-content: center; flex-wrap: wrap;
    margin: 8px 0; gap: 8px; font-size: 12px;
  }
  .dataTables_wrapper .dataTables_filter input { width: 100%; max-width: 300px; }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>
$(document).ready(function () {
  const table = $("#sellOrderTable").DataTable({
    processing: true,
    serverSide: true,
    serverMethod: "post",
    ajax: {
      url: "app/ajax/sell_order_data.php",
      data: function (d) {
        d.start_date = $("#start_date").val();
        d.end_date   = $("#end_date").val();
      }
    },
    columns: [
      { data: "invoice_number" },
      { data: "distributor_name" },
      { data: "customer_name" },
      { data: "net_total" },
      { data: "order_date" },
      { data: "items_summary" }
    ],
     order: [[0, "desc"]],
    drawCallback: function (settings) {
      const api = this.api();
      const data = api.rows({ page: "current" }).data();
      const $mobileCards = $("#mobileSellOrders").empty();
      data.each(function (row) {
        $mobileCards.append(`
          <div class="card-item">
            <h4>${row.invoice_number}</h4>
            <p class="meta"><strong>Distributor:</strong> ${row.distributor_name}</p>
            <p class="meta"><strong>Pelanggan:</strong> ${row.customer_name}</p>
            <p class="meta"><strong>Total Bayar:</strong> ${row.net_total}</p>
            <p class="meta"><strong>Tanggal:</strong> ${row.order_date}</p>
            <p class="meta"><strong>Produk:</strong><br>${row.items_summary}</p>
          </div>`);
      });
    }
  });

  // tombol filter
  $("#filterBtn").on("click", function () {
    table.ajax.reload();
  });

  // tombol reset
  $("#resetBtn").on("click", function () {
    $("#start_date").val('');
    $("#end_date").val('');
    table.ajax.reload();
  });
});
</script>
