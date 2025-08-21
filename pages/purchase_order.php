<div style="padding-top:52px;background-color: #f6f7fb;">
  <!-- Purchase Order Page -->
  <section class="content">
    <div class="container-fluid">
      <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
          <h2 style="margin: 0; font-size: 26px; font-weight: 600; color: #222; user-select:none;">
            📦 DAFTAR PEMESANAN
          </h2>
          <?php if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10): ?>
          <a href="index.php?page=add_purchase_order" style="
               background-color: #0073ea;
               color: #fff;
               border-radius: 6px;
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
             " onmouseover="this.style.backgroundColor='#005bb5'; this.style.boxShadow='0 6px 18px rgba(0,91,181,0.5)'"
            onmouseout="this.style.backgroundColor='#0073ea'; this.style.boxShadow='0 4px 12px rgba(0,115,234,0.4)'">
            <i class="fas fa-plus" style="font-size: 14px;"></i> Tambah Pemesanan
          </a>
          <?php endif; ?>
        </div>
    

        <div class="table-responsive" style="overflow-x:auto;">
          <table id="purchaseOrderTable" class="display dataTable text-center" style="
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
            background: #fff;
            font-size: 11px !important;
            min-width: 900px;
          ">
            <thead
              style="background-color: #2563eb; color: #fff; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; font-size: 11px;">
              <tr>
                <th style="padding: 16px 20px; border-bottom: none; border-top-left-radius: 12px; font-size: 11px;">No
                  Invoice</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Nama Pemesan</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Total Bayar</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Status</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Produk</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Dibuat Pada</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Disetujui Pada</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px; border-top-right-radius: 12px;">
                  Aksi</th>
              </tr>
            </thead>


            <tbody style="color: #374151; font-weight: 500;">
              <!-- Rows akan diisi dari server -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<style>
  #purchaseOrderTable tbody tr {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  #purchaseOrderTable tbody tr:hover {
    background-color: #eff6ff;
    box-shadow: 0 6px 20px rgba(0, 115, 234, 0.15);
  }

  #purchaseOrderTable tbody tr td {
    padding: 14px 22px;
    vertical-align: middle;
  }

  /* Status badge styling */
  .status-badge {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    user-select: none;
  }

  .status-pending {
    background-color: #fef3c7;
    color: #b45309;
  }

  .status-approved {
    background-color: #d1fae5;
    color: #059669;
  }

  .status-rejected {
    background-color: #fee2e2;
    color: #dc2626;
  }

  /* Detail button style */
  .btn-detail {
    background-color: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 18px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    user-select: none;
  }

  .btn-detail:hover {
    background-color: #1d4ed8;
  }
</style>

<script>
  // Row hover handled by CSS, script not needed unless dynamic
</script>