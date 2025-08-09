<div class="content-wrapper" style="margin-top:75px; margin-bottom:75px; background-color: #f6f7fb;">
  <!-- Anggota List Page -->
  <section class="content">
    <div class="container-fluid" style="margin-top: 40px;">
      <div style="
        background: white;
        border-radius: 16px;
        box-shadow: 0px 4px 20px rgba(0,0,0,0.05);
        padding: 24px;
      ">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
          <h2 style="margin: 0; font-size: 20px; font-weight: 600; color: #333;">📋 List Anggota</h2>
          <?php if ($_SESSION['role_id'] == 1): ?>
          <button type="button" data-toggle="modal" data-target=".suppliarModal"
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
            <i class="fas fa-plus mr-1"></i> Tambah Anggota
          </button>
          <?php endif; ?>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table id="suppliarTable" class="display dataTable text-center" style="
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
          ">
            <thead style="background-color: #f0f3f6; color: #555;">
              <tr>
                <th style="padding: 12px;">Member ID</th>
                <th style="padding: 12px;">Nama</th>
                <th style="padding: 12px;width:240px">Alamat</th>
                <th style="padding: 12px;">Kontak</th>
                <th style="padding: 12px;">Level</th>
                <th style="padding: 12px;">Aksi</th>
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
