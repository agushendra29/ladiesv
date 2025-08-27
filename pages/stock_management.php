<div style="padding-top:52px; background-color: #f6f7fb;">
  <!-- Stok Produk Page -->
  <section class="content">
    <div class="container-fluid">
      <div style="">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
          <h2 style="margin: 0; font-size: 26px; font-weight: 600; color: #222; user-select:none;">
            📦 DAFTAR STOK PRODUK
          </h2>
          <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
          <a href="index.php?page=add_stock_management" target="_blank" style="
               background-color: #0073ea;
               color: #fff;
               border-radius: 12px;
               padding: 8px 20px;
               font-weight: 600;
               font-size: 12px;
               text-decoration: none;
               box-shadow: 0 4px 12px rgba(0,115,234,0.4);
               transition: background-color 0.3s ease, box-shadow 0.3s ease;
               display: flex;
               align-items: center;
               gap: 8px;
               user-select:none;
             "
             onmouseover="this.style.backgroundColor='#005bb5'; this.style.boxShadow='0 6px 18px rgba(0,91,181,0.5)'"
             onmouseout="this.style.backgroundColor='#0073ea'; this.style.boxShadow='0 4px 12px rgba(0,115,234,0.4)'"
          >
            <i class="fas fa-plus" style="font-size: 16px;"></i> Tambah Stok
          </a>
          <?php endif; ?>
        </div>

        <!-- Table Stok -->
        <div class="table-responsive" style="overflow-x:auto;">
          <table id="stockManagementTable" class="display dataTable text-center" style="
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
            background: #fff;
            font-size: 12px !important;
            min-width: 900px;
          ">
            <thead style="background-color: #2563eb; color: #fff; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; font-size: 11px;">
              <tr>
                <th style="padding: 16px 20px; border-bottom: none; border-top-left-radius: 12px; font-size: 11px;">No</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Nama Produk</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Distributor</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Jumlah Stok</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px; border-top-right-radius: 12px;">Aksi</th>
              </tr>
            </thead>
            <tbody style="color: #374151; font-weight: 500;">
              <!-- DataTables akan menampilkan data di sini -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
    <!-- Riwayat Log Perubahan Stok -->
    <div class="container-fluid" style="margin-top: 10px;">
      <div style="
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.07);
        padding: 30px 30px 40px;
        min-height: 420px;
      ">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
          <h2 style="margin: 0; font-size: 26px; font-weight: 600; color: #222; user-select:none;">
            📝 RIWAYAT PERUBAHAN STOK
          </h2>
        </div>

        <!-- Table Log -->
        <div class="table-responsive" style="overflow-x:auto;">
          <table id="stockLogsTable" class="display dataTable text-center" style="
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
            background: #fff;
            font-size: 12px !important;
            min-width: 900px;
          ">
            <thead style="background-color: #2563eb; color: #fff; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; font-size: 11px;">
              <tr>
                <th style="padding: 16px 20px; font-size: 11px;">Distributor</th>
                <th style="padding: 16px 20px; font-size: 11px;">Produk</th>
                <th style="padding: 16px 20px; font-size: 11px;">Aksi</th>
        <th style="padding: 16px 20px; font-size: 11px;">+/-</th>
                <th style="padding: 16px 20px; font-size: 11px;">Jumlah Sebelumnya</th>
                <th style="padding: 16px 20px; font-size: 11px;">Jumlah Baru</th>
                <th style="padding: 16px 20px; font-size: 11px;">Diubah Oleh</th>
                <th style="padding: 16px 20px; font-size: 11px;">Tanggal</th>
              </tr>
            </thead>
            <tbody style="color: #374151; font-weight: 500;">
              <!-- DataTables akan menampilkan data log di sini -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </section>
</div>
