<!-- CSS daterangepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>

  .no-data {
    text-align: center;
    color: #9ca3af;
    font-style: italic;
    padding: 30px 0;
  }
</style>

<div class="container-fluid" style="padding-top:72px;">
  <div class="section-card-body" style="padding-top:72px;">
  <div class="page-custom-header">
  <div class="mb-3 section-title">Stock Monitoring</div>
</div>
  <div class="row mb-3">
    <div class="col-md-4">
      <label>Periode</label>
      <input type="text" id="issuedate" class="form-control" readonly />
    </div>
  
   <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
  <div class="col-md-4">
    <label>Pilih Supplier</label>
  <select id="supplier_id" class="form-control">
  <option value="">-- Pilih Supplier --</option>
  <?php
    $sessionRole = $_SESSION['role_id'] ?? '';
    $suppliers   = $obj->all("suppliar");

    foreach ($suppliers as $sup) {
        // === Kondisi untuk Super Admin (10) ===
        if ((int)$sessionRole === 10) {
            // Jika supplier HO tapi bukan 000001, skip
            if ((int)$sup->role_id === 1 && ($sup->suppliar_code ?? '') !== '000001') {
                continue;
            }
            // Jika HO dengan 000001, tampilkan sebagai Head Office
            if ((int)$sup->role_id === 1 && $sup->suppliar_code === '000001') {
                echo '<option value="'.htmlspecialchars($sup->id).'">Head Office</option>';
            } else {
                echo '<option value="'.htmlspecialchars($sup->id).'">'.htmlspecialchars($sup->name).' - '.htmlspecialchars($sup->suppliar_code).'</option>';
            }
            continue;
        }

        // === Kondisi untuk Head Office (1) ===
        if ((int)$sessionRole === 1) {
            // Skip semua supplier role 10
            if ((int)$sup->role_id === 10) {
                continue;
            }
            // Jika supplier HO tapi bukan 000001, skip
            if ((int)$sup->role_id === 1 && ($sup->suppliar_code ?? '') !== '000001') {
                continue;
            }
            // Jika HO dengan 000001 → Head Office
            if ((int)$sup->role_id === 1 && $sup->suppliar_code === '000001') {
                echo '<option value="'.htmlspecialchars($sup->id).'">Head Office</option>';
            } else {
                echo '<option value="'.htmlspecialchars($sup->id).'">'.htmlspecialchars($sup->name).' - '.htmlspecialchars($sup->suppliar_code).'</option>';
            }
            continue;
        }

        // === Role lain (distributor/agen) bisa tambah kondisi lain bila perlu ===
    }
  ?>
  <option value="all">Semua Supplier</option>
</select>

  </div>
<?php endif; ?>

    <div class="col-md-3">
  <label>Pilih Produk</label>
  <select id="productFilter" class="form-control">
    <option value="all">- All -</option>
    <?php 
      $all_products = $obj->all('products');
      foreach ($all_products as $p) {
        echo '<option value="'.$p->id.'">'.$p->product_name.'</option>';
      }
    ?>
  </select>
</div>
    

    <div class="col-md-2 align-self-end mt-3">
      <button id="btnSearchStock" class="btn-custom">Filter</button>
    </div>
  </div>

  <div style="padding:5px;background:white;">
  <div class="table-responsive">
    <table id="stockMonitoringTable" class="text-center dataTable custom-table">
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Pembelian</th>
          <th>Penjualan</th>
          <th>Refund</th>
          <th>Last Updated</th>
        </tr>
      </thead>
      <tbody id="stock_monitoring_res">
        <tr>
          <td colspan="5" class="no-data">Pilih supplier & periode untuk melihat data.</td>
        </tr>
      </tbody>
    </table>
  </div>
    </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
   document.addEventListener("DOMContentLoaded", function() {
  new Choices("#supplier_id", {
    searchEnabled: true,   // aktifkan fitur search
    itemSelectText: '',    // hilangkan tulisan "Press to select"
    shouldSort: false,     // biar urutan option asli tidak berubah
    placeholderValue: "Pilih Distributor/Agen"
  });
});
  var start = moment().subtract(29, 'days');
  var end = moment();

  function cb(start, end) {
    $('#issuedate').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
  }

  $('#issuedate').daterangepicker({
    startDate: start,
    endDate: end,
    locale: { format: 'DD/MM/YYYY' },
    opens: 'left',
    ranges: {
      'Hari Ini': [moment(), moment()],
      'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
      '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
      'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
      'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
  }, cb);

  cb(start, end);

  // Fungsi load stock monitoring
  function loadStockMonitoring(supplier_id, issuedate, product_id) {
    console.log('AJAX stock_monitoring -> supplier_id:', supplier_id, 'issuedate:', issuedate);

    $.ajax({
      url: 'app/ajax/search_stock_monitoring.php',
      method: 'POST',
      data: {
        suppliar_id: supplier_id,
        issuedate: issuedate,
        product_id: product_id
      },
      cache: false,
      success: function(data) {
        $("#stock_monitoring_res").html(data);
      },
      error: function(xhr, status, err) {
        $("#stock_monitoring_res").html('<tr><td colspan="5" class="no-data">Error loading data: ' + err + '</td></tr>');
        console.error('AJAX error', status, err);
      }
    });
  }

  // Klik tombol cari
  $(document).on('click', '#btnSearchStock', function() {
    var role_id = <?php echo json_encode($_SESSION['role_id']); ?>;
    var distributor_id = <?php echo json_encode($_SESSION['distributor_id']); ?>;
    var supplier_id = role_id == 1 || role_id == 10 ? $("#supplier_id").val() : distributor_id;
    var issuedate = $("#issuedate").val();
    var product_id = $("#productFilter").val();

    if (!supplier_id) {
      alert("Pilih supplier terlebih dahulu!");
      $("#supplier_id").focus();
      return;
    }
    if (!issuedate) {
      alert("Pilih periode tanggal!");
      $("#issuedate").focus();
      return;
    }


    loadStockMonitoring(supplier_id, issuedate, product_id);
  });

</script>
