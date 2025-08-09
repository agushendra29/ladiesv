<div class="content-wrapper" style="margin-top:75px; margin-bottom:75px; background-color: #f6f7fb;">
  <!-- Halaman Daftar Stok Produk -->
  <section class="content">
    <div class="container-fluid" style="margin-top: 40px;">
      <div style="
        background: white;
        border-radius: 16px;
        box-shadow: 0px 4px 20px rgba(0,0,0,0.05);
        padding: 24px;
        min-height:420px;
      ">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
          <h2 style="margin: 0; font-size: 20px; font-weight: 600; color: #333;">📦 Daftar Stok Produk</h2>
            <?php if ($_SESSION['role_id'] == 1): ?><a href="index.php?page=add_stock_management" target="_blank"
            style="
              background-color: #0073ea;
              color: white;
              border-radius: 10px;
              padding: 10px 20px;
              font-weight: 600;
              font-size: 14px;
              text-decoration: none;
              border: none;
              transition: background-color 0.3s ease;
            "
            onmouseover="this.style.backgroundColor='#005bb5'"
            onmouseout="this.style.backgroundColor='#0073ea'"
          >
            <i class="fas fa-plus mr-1"></i> Tambah Stok
          </a>
          
<?php endif; ?>
        </div>

        <!-- Table Stok -->
        <div class="table-responsive">
          <table id="stockManagementTable" class="display dataTable text-center" style="
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
          ">
            <thead style="background-color: #f0f3f6; color: #555;">
              <tr>
                <th style="padding: 12px;">No</th>
                <th style="padding: 12px;">Nama Produk</th>
                <th style="padding: 12px;">Distributor</th>
                <th style="padding: 12px;">Jumlah Stok</th>
                <th style="padding: 12px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- DataTables akan menampilkan data di sini -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

   <?php if ($_SESSION['role_id'] == 1): ?>
    <!-- Riwayat Log Perubahan Stok -->
    <div class="container-fluid" style="margin-top: 10px;">
      <div style="
        background: white;
        border-radius: 16px;
        box-shadow: 0px 4px 20px rgba(0,0,0,0.05);
        padding: 24px;
        min-height:420px;
      ">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
          <h2 style="margin: 0; font-size: 20px; font-weight: 600; color: #333;">📝 Riwayat Perubahan Stok</h2>
        </div>

        <!-- Table Log -->
        <div class="table-responsive">
          <table id="stockLogsTable" class="display dataTable text-center" style="
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
          ">
            <thead style="background-color: #f0f3f6; color: #555;">
              <tr>
                <th>Distributor</th>
                <th>Produk</th>
                <th>Aksi</th>
                <th>Jumlah Sebelumnya</th>
                <th>Jumlah Baru</th>
                <th>Diubah Oleh</th>
                <th>Tanggal</th>
                <th>Catatan</th>
              </tr>
            </thead>
            <tbody>
              <!-- DataTables akan menampilkan data log di sini -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
<?php endif; ?>
  </section>
</div>
