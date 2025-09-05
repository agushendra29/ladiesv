<div style="padding-top:52px; padding-bottom:52px; background-color: #f6f7fb;">
  <!-- Sell Order Page -->
  <section class="content">
    <div class="container-fluid">
      <div>
        <!-- Header -->
        <div class="header-block">
          <h2>
            📋 DAFTAR PENJUALAN
          </h2>
          <a href="index.php?page=add_sell_order" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Penjualan
          </a>
        </div>

        <!-- Controls (Show & Search) -->
        <div class="table-controls">
          <div id="sellOrderTable_length"></div>
          <div id="sellOrderTable_filter"></div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table id="sellOrderTable" class="display dataTable text-center stock-table">
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
<div id="mobileSellOrders" class="mobile-cards"></div>

      </div>
    </div>
  </section>
</div>

<style>
/* Header */
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

/* Table controls (Show & Search) */
.table-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  flex-wrap: wrap;
  gap: 10px;
}
.table-controls label {
  font-size: 13px;
  color: #374151;
  display: flex;
  align-items: center;
  gap: 6px;
}
.table-controls select,
.table-controls input {
  border: 1px solid #d1d5db;
  border-radius: 8px;
  padding: 4px 8px;
  font-size: 13px;
}

/* Table */
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
.stock-table th {
  font-size: 10px !important;
  padding: 14px 20px;
}
.stock-table td {
  padding: 14px 22px;
  vertical-align: middle;
  text-align: center;
}
#sellOrderTable tbody tr {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
#sellOrderTable tbody tr:hover {
  background-color: #eff6ff;
  box-shadow: 0 6px 20px rgba(0, 115, 234, 0.15);
}

/* Responsive */
@media (max-width: 768px) {
  .header-block h2 {
    font-size: 16px;
  }
  .btn-add {
    width: 100%;
    justify-content: center;
  }
  .table-controls {
    flex-direction: column;
    align-items: flex-start;
  }
  .stock-table {
    font-size: 11px !important;
    min-width: unset; /* biar bisa scroll */
  }
  .stock-table th {
    font-size: 9px !important;
    padding: 10px 12px;
  }
  .stock-table td {
    font-size: 11px !important;
    padding: 10px 12px;
  }
}

.mobile-cards {
  display: none;
  margin-top: 15px;
}
.sell-card {
  background: #fff;
  border-radius: 12px;
  padding: 15px;
  margin-bottom: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
  font-size: 13px;
}
.sell-card h4 {
  margin: 0 0 10px;
  font-size: 15px;
  font-weight: 600;
  color: #222;
}
.sell-card p {
  margin: 4px 0;
  color: #444;
}

/* Hanya tampilkan cards di mobile */
@media (max-width: 768px) {
  #sellOrderTable {
    display: none; /* sembunyikan tabel DataTables */
  }
  #mobileSellOrders {
    display: block;
  }
}
</style>
