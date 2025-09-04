<div style="padding-top:52px; background-color: #f6f7fb;">
  <!-- Stok Produk Page -->
  <section class="content">
    <div class="container-fluid">
      <div>
        <!-- Header -->
        <div class="header-block">
          <h2>
            📦 DAFTAR STOK PRODUK
          </h2>
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
            <tbody>
              <!-- DataTables -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <?php if ($_SESSION['role_id'] == 10): ?>
    <!-- Riwayat Log Perubahan Stok -->
    <div class="container-fluid" style="margin-top: 20px;">
      <div class="log-block">
        <!-- Header -->
        <div class="header-block">
          <h2>
            📝 RIWAYAT PERUBAHAN STOK
          </h2>
        </div>

        <!-- Table Log -->
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
            <tbody>
              <!-- DataTables -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </section>
</div>

<style>
/* Header block */
.header-block {
  display: flex;
  flex-direction: column; /* selalu 1 baris 1 baris */
  gap: 10px;
  margin-bottom: 24px;
}
.header-block h2 {
  margin: 0;
  font-size: 20px;   /* kecilin judul */
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

/* Table styling seragam */
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
  font-size:10px !important;
}
.stock-table td {
  padding: 12px 18px;
  vertical-align: middle;
  text-align: center;
}
.stock-table tbody tr {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
.stock-table tbody tr:hover {
  background-color: #eff6ff;
  box-shadow: 0 6px 20px rgba(0, 115, 234, 0.15);
}

/* Block untuk log */
.log-block {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 18px rgba(0,0,0,0.07);
  padding: 24px 24px 32px;
  min-height: 420px;
}

/* Responsif */
@media (max-width: 768px) {
  .header-block h2 {
    font-size: 16px; /* lebih kecil di mobile */
  }
  .btn-add {
    width: 100%;
    justify-content: center;
  }
  .stock-table {
    font-size: 11px !important;
    min-width: unset; /* biar bisa scroll responsif */
  }
  .stock-table th {
    font-size: 9px !important;
  }
  .stock-table td {
    font-size: 11px !important;
  }
}
</style>
