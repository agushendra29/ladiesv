

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
/* Header */
.page-header-custom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 28px;
  flex-wrap: wrap;
  gap: 12px;
}
.page-header-custom h2 {
  margin: 0;
  font-size: 26px;
  font-weight: 600;
  color: #222;
  user-select: none;
}

/* Tombol */
.btn-custom {
  background-color: #0073ea;
  color: #fff;
  border-radius: 12px;
  padding: 8px 20px;
  font-weight: 600;
  font-size: 12px;
  border: none;
  box-shadow: 0 4px 12px rgba(0,115,234,0.4);
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}
.btn-custom:hover {
  background-color: #005bb5;
  box-shadow: 0 6px 18px rgba(0,91,181,0.5);
}

/* Filter */
.filter-wrapper {
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}
.filter-wrapper label {
  font-weight: 600;
  font-size: 13px;
  color: #333;
  margin-bottom: 0px;
}
.filter-wrapper select {
  padding: 8px 14px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  font-size: 13px;
  outline: none;
  min-width: 160px;
}

/* Table */
.custom-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 10px;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 18px rgba(0,0,0,0.07);
  background: #fff;
  font-size: 11px !important;
}
.custom-table thead {
  background-color: #2563eb;
  color: #fff;
  text-transform: uppercase;
  font-weight: 700;
  letter-spacing: 0.05em;
  font-size: 9px !important;
}
.custom-table th {
  padding: 10px 14px;
  line-height: 1.3;
  font-size: 10px !important;
  text-align:center !important; 
}
.custom-table td {
  padding: 14px 22px;
  vertical-align: middle;
  text-align: center;
}
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

/* Mobile Cards */
#mobileCards {
  display: none;
}
#mobileCards .card-item {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  padding: 12px;
  margin-bottom: 8px;
  font-size: 12px;
  width: 100%;
  max-width: 400px;
}


/* Responsif */
@media (max-width: 768px) {
  .page-header-custom h2 { font-size: 20px; }
  .btn-custom { width: 100%; justify-content: center; }
  .filter-wrapper { flex-direction: column; align-items: flex-start; }
  .filter-wrapper select { width: 100%; }

  /* Hanya sembunyikan isi tabel, bukan wrapper */
  #suppliarTable thead,
  #suppliarTable tbody { display: none; }

  #mobileCards {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    margin-top: 12px;
  }
  #mobileCards .card-item { width: 100%; max-width: 400px; }

  /* Search, pagination, length menu tetap rapi */
  .dataTables_wrapper .dataTables_filter,
  .dataTables_wrapper .dataTables_length,
  .dataTables_info,
  .dataTables_wrapper .dataTables_paginate {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin: 8px 0;
    gap: 10px;
    font-size:10px !important;
  }
  .dataTables_wrapper .dataTables_filter input { width: 100%; max-width: 280px; }
  .dataTables_wrapper .dataTables_paginate { margin-top: 4px; gap:6px; }
}
</style>
</head>
<div style="padding-top:52px; margin-bottom:50px; background-color: #f6f7fb;">

<section class="content">
  <div>
    <div>
      <!-- Header -->
      <div class="page-header-custom">
        <h2>📋 DAFTAR ANGGOTA</h2>
        <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
        <button type="button" data-toggle="modal" data-target=".suppliarModal" class="btn-custom">
          <i class="fas fa-plus"></i> Tambah Anggota
        </button>
        <?php endif; ?>
      </div>

      <!-- Filter -->
      <div class="filter-wrapper">
        <label for="roleFilter" style="margin-bottom: 0px;">Filter Role:</label>
        <select id="roleFilter">
          <option value="">Semua</option>
          <option value="1">HO</option>
          <option value="2">Head Distributor</option>
          <option value="3">Distributor</option>
          <option value="4">Agen</option>
          <option value="5">Reseller</option>
        </select>
      </div>

      <!-- Table desktop -->
      <div class="table-responsive">
        <table id="suppliarTable" class="display dataTable text-center custom-table">
          <thead>
            <tr>
              <th>Member ID</th>
              <th>Nama</th>
              <th>Alamat</th>
              <th>Kontak</th>
              <th>Level</th>
              <th>Tanggal Pendaftaran</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <!-- Card view mobile -->
      <div id="mobileCards"></div>
    </div>
  </div>
</section>

<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


</div>