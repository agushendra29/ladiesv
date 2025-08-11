<div style="padding-top:52px; background-color: #f6f7fb;">
  <!-- Anggota List Page -->
  <section class="content">
    <div class="container-fluid">
      <div>
        <!-- Header dengan Judul dan Tombol Tambah -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
          <h2 style="margin: 0; font-size: 26px; font-weight: 600; color: #222; user-select:none;">
            📋 DAFTAR ANGGOTA
          </h2>
          <?php if ($_SESSION['role_id'] == 1): ?>
          <button type="button" data-toggle="modal" data-target=".suppliarModal" style="
               background-color: #0073ea;
               color: #fff;
               border-radius: 12px;
               padding: 8px 20px;
               font-weight: 600;
               font-size: 12px;
               border: none;
               box-shadow: 0 4px 12px rgba(0,115,234,0.4);
               transition: background-color 0.3s ease, box-shadow 0.3s ease;
               display: flex;
               align-items: center;
               gap: 8px;
               user-select:none;
             "
             onmouseover="this.style.backgroundColor='#005bb5'; this.style.boxShadow='0 6px 18px rgba(0,91,181,0.5)'" 
             onmouseout="this.style.backgroundColor='#0073ea'; this.style.boxShadow='0 4px 12px rgba(0,115,234,0.4)'">
            <i class="fas fa-plus" style="font-size: 16px;"></i> Tambah Anggota
          </button>
          <?php endif; ?>
        </div>

        <!-- Table Responsive -->
        <div class="table-responsive" style="overflow-x:auto;">
          <table id="suppliarTable" class="display dataTable text-center" style="
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
                <th style="padding: 16px 20px; border-bottom: none; border-top-left-radius: 12px; font-size: 11px;">Member ID</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Nama</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px; width: 240px;">Alamat</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Kontak</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Level</th>
                <th style="padding: 16px 20px; border-bottom: none; font-size: 11px; border-top-right-radius: 12px;">Aksi</th>
              </tr>
            </thead>

            <tbody style="color: #374151; font-weight: 500;">
              <!-- DataTables will inject data here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<style>
  #suppliarTable tbody tr {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  #suppliarTable tbody tr:hover {
    background-color: #eff6ff;
    box-shadow: 0 6px 20px rgba(0, 115, 234, 0.15);
  }

  #suppliarTable tbody tr td {
    padding: 14px 22px;
    vertical-align: middle;
  }
</style>
