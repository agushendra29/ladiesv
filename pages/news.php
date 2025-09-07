<div style="padding-top:52px; background-color: white;">
  <section class="content">
    <div class="container-fluid">
      <div>
        <!-- Header -->
        <div class="page-header-custom">
          <h2>📰 DAFTAR BERITA</h2>
          <a href="index.php?page=add_news" class="btn-custom">
            <i class="fas fa-plus"></i> Tambah Berita
          </a>
        </div>

        <!-- Table untuk desktop -->
        <div class="table-responsive">
          <table id="newsTable" class="display dataTable text-center custom-table">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Isi Berita</th>
                <th>Tanggal Publikasi</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- DataTables inject -->
            </tbody>
          </table>
        </div>

        <!-- Card view untuk mobile -->
        <div id="mobileNewsCards"></div>
      </div>
    </div>
  </section>
</div>

<!-- CSS -->
<style>
/* Header */
.btn-action {
  display: inline-block;
  padding: 6px 10px;        /* lebih kecil */
  font-size: 12px;          /* kecil */
  font-weight: 500;
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.25s ease, box-shadow 0.25s ease;
  text-decoration: none;
  line-height: 1.4;
  box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}

/* Hover */
.btn-action:hover {
  opacity: 0.9;
  text-decoration: none;
  color: #fff;
}

/* Variasi warna */
.btn-blue {
  background-color: #EEA0A0;
}
.btn-blue:hover {
  background-color: #1e40af;
}

.btn-red {
  background-color: #dc2626;
}
.btn-red:hover {
  background-color: #b91c1c;
}

.btn-green {
  background-color: #16a34a;
}
.btn-green:hover {
  background-color: #15803d;
}
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
.btn-custom {
  background-color: #EB4F7D;
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
  text-decoration: none;
}
.btn-custom:hover {
  background-color: #005bb5;
  box-shadow: 0 6px 18px rgba(0,91,181,0.5);
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
  font-size: 12px !important;
  min-width: 900px;
}
.custom-table thead {
  background-color: #EEA0A0;
  color: #fff;
  text-transform: uppercase;
  font-weight: 700;
  letter-spacing: 0.05em;
  font-size: 11px;
}
.custom-table th {
  padding: 16px 20px;
  line-height: 1.3;
  font-size: 11px !important;
  text-align: center !important;
}
.custom-table td {
  padding: 14px 22px;
  vertical-align: middle;
  text-align: center;
}
#newsTable tbody tr {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
#newsTable tbody tr:hover {
  background-color: #eff6ff;
  box-shadow: 0 6px 20px rgba(0,115,234,0.15);
}
/* Clamp isi berita maksimal 2 baris */
#newsTable td:nth-child(2) {
  max-width: 500px;
  white-space: normal;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

/* Mobile Cards */
#mobileNewsCards {
  display: none;
}
#mobileNewsCards .card-item {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  padding: 12px;
  margin-bottom: 8px;
  font-size: 12px;
  width: 100%;
  max-width: 500px;
}
#mobileNewsCards .card-item h4 {
  font-size: 14px;
  margin-bottom: 4px;
  font-weight: 600;
}
#mobileNewsCards .card-item .meta {
  font-size: 11px;
  color: #555;
  margin-bottom: 6px;
}
#mobileNewsCards .card-item .actions {
  margin-top: 6px;
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
}
#mobileNewsCards .card-item .actions .btn {
  padding: 4px 8px;
  font-size: 11px;
  border-radius: 4px;
}

/* Responsif */
@media (max-width: 768px) {
  .page-header-custom h2 { font-size: 20px; }
  .btn-custom { width: 100%; justify-content: center; }
  /* sembunyikan isi tabel tapi biarkan wrapper DataTables tampil */
  #newsTable thead tr, #newsTable tbody tr td { display: none; }
  #mobileNewsCards { display: flex; flex-direction: column; align-items: center; gap: 12px; margin-top: 12px; }
  #mobileNewsCards .card-item { width: 100%; max-width: 500px; }
  .dataTables_wrapper .dataTables_filter,
  .dataTables_wrapper .dataTables_paginate,
  .dataTables_wrapper .dataTables_length {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin: 8px 0;
    gap: 8px;
    font-size: 12px;
  }
  .dataTables_wrapper .dataTables_filter input { width: 100%; max-width: 300px; }
}

@media (max-width: 768px) {
  /* Sembunyikan row tabel saja */
  #newsTable thead,
  #newsTable tbody {
    display: none !important;
  }

  /* Bungkus tabel tetap ada supaya search & pagination tetap jalan */
  #newsTable {
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
  }

  /* Card aktif */
  #mobileNewsCards { 
    display: flex !important;
    flex-direction: column;
    gap: 12px;
    margin-top: 12px;
  }
}
</style>

<!-- jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

