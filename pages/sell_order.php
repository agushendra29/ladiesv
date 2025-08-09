<div class="content-wrapper" style="margin-top:75px; margin-bottom:75px; background-color: #f6f7fb;">
  <!-- Purchase Order Page -->
  <section class="content">
    <div class="container-fluid" style="margin-top: 40px;">
      <div style="
        background: white;
        border-radius: 16px;
        box-shadow: 0px 4px 20px rgba(0,0,0,0.05);
        padding: 24px;
      ">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
          <h2 style="margin: 0; font-size: 20px; font-weight: 600; color: #333;">📋 Daftar Penjualan</h2>
          <a href="index.php?page=add_sell_order"
             style="
               background-color: #0073ea;
               color: white;
               border-radius: 10px;
               padding: 10px 20px;
               font-weight: 600;
               font-size: 14px;
               text-decoration: none;
               transition: background-color 0.3s ease;
             "
             onmouseover="this.style.backgroundColor='#005bb5'"
             onmouseout="this.style.backgroundColor='#0073ea'"
          >
            <i class="fas fa-plus mr-1"></i> Tambah Penjualan
          </a>
        </div>

        <div class="table-responsive">
          <table id="sellOrderTable" class="display dataTable text-center" style="
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
          ">
            <thead style="background-color: #f0f3f6; color: #555;">
              <tr>
                <th style="padding: 12px;">Invoice No</th>
                <th style="padding: 12px;">Nama Distributor</th>
                <th style="padding: 12px;">Nama Customer</th>
                <th style="padding: 12px;">Total Pembayaran</th>
                <th style="padding: 12px;">Tanggal Penjualan</th>
                <th style="padding: 12px;">Produk</th>
              </tr>
            </thead>
            <tbody>
              <!-- DataTables will inject data here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
