<div style="padding-top:52px; background-color: #f6f7fb;">
  <!-- Purchase Order Page -->
  <section class="content">
    <div class="container-fluid">
      <div>
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
          <h2 style="margin: 0; font-size: 18px; font-weight: 600; color: #222; user-select:none;">
            📦 DAFTAR PEMESANAN
          </h2>
          <?php if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10): ?>
          <a href="index.php?page=add_purchase_order" style="
              background-color: #0073ea;
              color: #fff;
              border-radius: 6px;
              padding: 6px 14px;
              font-weight: 600;
              font-size: 11px;
              text-decoration: none;
              box-shadow: 0 2px 8px rgba(0,115,234,0.35);
              transition: all 0.3s ease;
              display: flex;
              align-items: center;
              gap: 6px;
              user-select:none;
            "
            onmouseover="this.style.backgroundColor='#005bb5'; this.style.boxShadow='0 4px 12px rgba(0,91,181,0.45)'"
            onmouseout="this.style.backgroundColor='#0073ea'; this.style.boxShadow='0 2px 8px rgba(0,115,234,0.35)'">
            <i class="fas fa-plus" style="font-size: 11px;"></i> Tambah Pemesanan
          </a>
          <?php endif; ?>
        </div>

        <!-- Table -->
        <div class="card" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
          <div class="card-body" style="padding: 14px;">
            <div class="table-responsive">
              <table id="purchaseOrderTable" class="display nowrap" style="width:100%;">
                <thead style="background-color: #2563eb; color: #fff; text-transform: uppercase;">
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
                  <!-- data dari server -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Modal Approve -->
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

<!-- DataTables & SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
  #purchaseOrderTable thead th {
    font-size: 10px !important;
    padding: 10px 12px;
    font-weight: 600;
    letter-spacing: 0.04em;
  }

  #purchaseOrderTable tbody tr {
    background-color: #fff;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    transition: 0.3s;
  }

  #purchaseOrderTable tbody tr:hover {
    background-color: #f3f8ff;
    box-shadow: 0 3px 10px rgba(0, 115, 234, 0.1);
  }

  #purchaseOrderTable tbody td {
    padding: 8px 12px;
    vertical-align: middle;
    font-size: 11px;
    color: #374151;
  }

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

  .btn-detail {
    background-color: #2563eb;
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
