<!-- Content Wrapper -->
<div style="padding-top:52px;background-color: #f6f7fb;">
  <!-- Product List Page -->
  <section class="content">
    <div class="container-fluid">
      <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
          <h2 style="margin: 0; font-size: 26px; font-weight: 600; color: #222; user-select:none;">
            📦 DAFTAR PRODUK
          </h2>
          <?php if ($_SESSION['role_id'] <= 1 || $_SESSION['role_id'] == 10): ?>
          <a href="index.php?page=add_product" style="
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
            <i class="fas fa-plus" style="font-size: 14px;"></i> Tambah Produk
          </a>
          <?php endif; ?>
        </div>

        <div class="table-responsive" style="overflow-x:auto;">
          <table id="productTable" class="display dataTable text-center" style="
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
            background: #fff;
            font-size: 11px !important;
            min-width: 600px;
          ">
            <thead
              style="background-color: #2563eb; color: #fff; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; font-size: 11px;">
              <tr>
                <th style="padding: 16px 20px; border-bottom: none; border-top-left-radius: 12px; font-size: 11px;">No</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Nama Produk</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Harga</th>
                <th style="padding: 16px 20px; border-bottom: none; border-top-right-radius: 12px; font-size: 11px;">Aksi</th>
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
  #productTable tbody tr {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  #productTable tbody tr:hover {
    background-color: #eff6ff;
    box-shadow: 0 6px 20px rgba(0, 115, 234, 0.15);
  }

  #productTable tbody tr td {
    padding: 14px 22px;
    vertical-align: middle;
  }

  /* Button styling */
  .btn-action {
    background-color: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 13px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    user-select: none;
    margin: 0 4px;
  }

  .btn-action:hover {
    background-color: #1d4ed8;
  }

  /* Container for action buttons */
  .action-group {
    display: flex;
    justify-content: center;
    gap: 8px;
  }
</style>
