<div style="padding-top:52px; background-color: white;">
  <!-- Purchase Order Page -->
  <section class="content">
    <div class="container-fluid">
      <div>
        <!-- Header -->
        <div class="header-block">
          <h2>📦 DAFTAR PEMESANAN</h2>
          <?php if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10): ?>
          <a href="index.php?page=add_purchase_order" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Pemesanan
          </a>
          <?php endif; ?>
        </div>

        <!-- Controls (Show & Search) -->
        <div class="table-controls">
          <div id="purchaseOrderTable_length"></div>
          <div id="purchaseOrderTable_filter"></div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table id="purchaseOrderTable" class="display dataTable text-center po-table">
            <thead>
              <tr>
                <th>No Invoice</th>
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
/* Header */
.header-block {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 24px;
}
.header-block h2 {
  margin: 0;
  font-size: 20px;
  font-weight: 600;
  color: #222;
  user-select: none;
}
.btn-add {
  background-color: #EB4F7D;
  color: #fff;
  border-radius: 10px;
  padding: 8px 18px;
  font-weight: 600;
  font-size: 12px;
  text-decoration: none;
  box-shadow: 0 4px 12px rgba(0,115,234,0.4);
  display: inline-flex;
  align-items: center;
  gap: 6px;
  width: fit-content;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
.btn-add:hover {
  background-color: #005bb5;
  box-shadow: 0 6px 18px rgba(0,91,181,0.5);
}

/* Table controls (Show & Search) */
.table-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  flex-wrap: wrap;
  gap: 10px;
}
.table-controls label {
  font-size: 13px;
  color: #374151;
  display: flex;
  align-items: center;
  gap: 6px;
}
.table-controls select,
.table-controls input {
  border: 1px solid #d1d5db;
  border-radius: 8px;
  padding: 4px 8px;
  font-size: 13px;
}

/* Table */
.po-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 10px;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 18px rgba(0,0,0,0.07);
  background: #fff;
  font-size: 12px !important;
  min-width: 1000px;
}
.po-table thead {
  background-color: #EEA0A0;
  color: #fff;
  text-transform: uppercase;
  font-weight: 600;
  letter-spacing: 0.04em;
  font-size: 10px !important;
}
.po-table th {
  font-size: 10px !important;
  padding: 14px 20px;
}
.po-table td {
  padding: 14px 20px;
  vertical-align: middle;
  text-align: center;
  font-size: 12px !important;
  color: #374151;
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

/* Status badge */
.status-badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 12px;
  font-weight: 600;
  font-size: 10px;
  text-transform: uppercase;
}
.status-pending { background-color: #fef3c7; color: #b45309; }
.status-approved { background-color: #d1fae5; color: #059669; }
.status-rejected { background-color: #fee2e2; color: #dc2626; }

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
.btn-detail:hover { background-color: #1d4ed8; }

/* Mobile responsive */
@media (max-width: 768px) {
  .header-block h2 { font-size: 16px; }
  .btn-add { width: 100%; justify-content: center; }
  .table-controls { flex-direction: column; align-items: flex-start; }
  .po-table { font-size: 11px !important; min-width: unset; }
  .po-table th { font-size: 9px !important; padding: 10px 12px; }
  .po-table td { font-size: 11px !important; padding: 10px 12px; }
}

.mobile-cards {
  display: none;
  margin-top: 15px;
}
.po-card {
  background: #fff;
  border-radius: 12px;
  padding: 15px;
  margin-bottom: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
  font-size: 13px;
}
.po-card h4 {
  margin: 0 0 10px;
  font-size: 15px;
  font-weight: 600;
  color: #222;
}
.po-card p {
  margin: 4px 0;
  color: #444;
}

/* Hanya tampilkan cards di mobile */
@media (max-width: 768px) {
  #purchaseOrderTable {
    display: none; /* sembunyikan tabel DataTables */
  }
  #mobilePurchaseOrders {
    display: block;
  }
}
</style>
