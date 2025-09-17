<div style="padding-top:52px;">
  <!-- Purchase Order Page -->
  <section class="content">
    <div class="container-fluid">
      <div class="section-card-body">
        <!-- Header -->
        <div class="page-header-custom">
          <div class="section-title">Daftar Pemesanan</div>
          <?php if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10): ?>
          <a href="index.php?page=add_purchase_order" class="btn-custom">
            <i class="fas fa-plus"></i> Tambah Pemesanan
          </a>
          <?php endif; ?>
        </div>
        <!-- Table -->
        <div class="table-responsive">
          <table id="purchaseOrderTable" class="display dataTable text-center custom-table">
            <thead>
              <tr>
                <th style="width:180px;">No Invoice</th>
                <th>Nama Pemesan</th>
                <th>Total Bayar</th>
                <th>Status</th>
                <th>Produk</th>
                <th>Dibuat Pada</th>
                <th>Disetujui Pada</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- DataTables inject rows -->
            </tbody>
          </table>
        </div>

        <!-- Mobile Cards -->
        <div id="mobilePurchaseOrders" class="mobile-cards"></div>
        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="font-size: 12px;">

              <div class="modal-header">
                <h6 class="modal-title">Setujui Purchase Order</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                  <span>&times;</span>
                </button>
              </div>

              <form id="approveForm">
                <div class="modal-body">
                  <input type="hidden" name="approve_po_id" id="approve_po_id">

                  <div class="form-group">
                    <label for="payment_type">Tipe Pembayaran</label>
                    <select class="form-control" id="payment_type" name="payment_type" required>
                      <option value="">-- Pilih --</option>
                      <option value="cash">Cash</option>
                      <option value="bank BRI">Bank BRI</option>
                      <option value="bank BNI">Bank BNI</option>
                      <option value="bank Mandiri">Bank Mandiri</option>
                      <option value="bank BCA">Bank BCA</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="shipping_type">Jenis Pengiriman</label>
                    <input type="text" class="form-control" id="shipping_type" name="shipping_type"
                      placeholder="Contoh: Ambil sendiri, Kurir internal, JNE, J&T, dll" required>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success btn-sm">Setujui PO</button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
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
    box-shadow: 0 4px 12px rgba(0, 115, 234, 0.4);
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    text-decoration: none;
  }

  .btn-custom:hover {
    background-color: #005bb5;
    box-shadow: 0 6px 18px rgba(0, 91, 181, 0.5);
  }

  /* Table */
  .custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.07);
    background: #fff;
    font-size: 11px !important;
  }

  .custom-table thead {
    background-color: #EEA0A0;
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
    text-align: center !important;
  }

  .custom-table td {
    padding: 14px 22px;
    vertical-align: middle;
    text-align: center;
  }

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

  /* Mobile Cards */
  #mobilePurchaseOrders {
    display: none;
  }

  #mobilePurchaseOrders .card-item {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
    padding: 12px;
    margin-bottom: 8px;
    font-size: 12px;
    width: 100%;
    max-width: 400px;
  }

  #mobilePurchaseOrders .card-item h4 {
    font-size: 14px;
    margin-bottom: 4px;
    font-weight: 600;
  }

  #mobilePurchaseOrders .card-item .meta {
    font-size: 11px;
    color: #555;
    margin-bottom: 2px;
  }

  #mobilePurchaseOrders .card-item .actions {
    margin-top: 6px;
    display: flex;
    flex-wrap: nowrap;
    gap: 6px;
    justify-content: flex-start;
  }

  #mobilePurchaseOrders .card-item .actions .btn {
    padding: 4px 8px;
    font-size: 11px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    margin-bottom: 0;
  }

  /* Responsif */
  @media (max-width: 768px) {
    .page-header-custom h2 {
      font-size: 20px;
    }

    .btn-custom {
      width: 100%;
      justify-content: center;
    }

    /* sembunyikan isi tabel tapi biarkan wrapper DataTables tampil */
    #purchaseOrderTable thead tr,
    #purchaseOrderTable tbody tr td {
      display: none;
    }

    #mobilePurchaseOrders {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      margin-top: 12px;
    }

    #mobilePurchaseOrders .card-item {
      width: 100%;
      max-width: 400px;
    }

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

    .dataTables_wrapper .dataTables_filter input {
      width: 100%;
      max-width: 300px;
    }
  }

  /* Status badge */
  .status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 10px;
    text-transform: uppercase;
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

  /* Button */
  .btn-detail {
    background-color: #EEA0A0;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 11px;
    cursor: pointer;
    transition: 0.3s;
  }

  .btn-detail:hover {
    background-color: #1d4ed8;
  }

</style>


<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">