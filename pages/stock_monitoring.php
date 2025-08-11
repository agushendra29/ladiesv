<!-- head: tambahkan CSS daterangepicker di header jika belum -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div class="container mt-4">
  <h3 class="mb-3">Stock Monitoring</h3>
  
  <!-- Filter -->
  <div class="row mb-3">
    <div class="col-md-4">
      <label>Periode</label>
      <input type="text" id="issuedate" class="form-control" readonly />
    </div>

    <div class="col-md-4">
      <label>Pilih Supplier</label>
      <select id="supplier_id" class="form-control">
        <option value="">-- Pilih Supplier --</option>
        <?php
          $suppliers = $obj->all("suppliar");
          foreach($suppliers as $sup){
            echo '<option value="'.htmlspecialchars($sup->id).'">'.htmlspecialchars($sup->name).'</option>';
          }
        ?>
        <option value="all">Semua Supplier</option>
      </select>
    </div>

    <div class="col-md-2 align-self-end">
      <button id="btnSearchStock" class="btn btn-primary">Cari</button>
    </div>
  </div>

  <!-- Tabel Stock Monitoring -->
  <div class="table-responsive">
    <table id="stockMonitoringTable" class="table text-center">
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Product Name</th>
          <th>Supplier</th>
          <th>Pembelian</th>
          <th>Penjualan</th>
          <th>Refund</th>
          <th>Jumlah Stock</th>
          <th>Last Updated</th>
        </tr>
      </thead>
      <tbody id="stock_monitoring_res">
        <tr>
          <td colspan="8" class="no-data">Pilih supplier & periode untuk melihat data.</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- JS libs -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
  // Inisialisasi daterangepicker (format DD/MM/YYYY - cocok untuk Indonesia)
  var start = moment().subtract(29, 'days');
  var end = moment();

  function cb(start, end) {
    // isi input dengan format yang dikirim ke server (DD/MM/YYYY - DD/MM/YYYY)
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

  cb(start, end); // set awal

  // fungsi AJAX: load stock monitoring
  function loadStockMonitoring(supplier_id, issuedate) {
    // debug console: lihat apa yang dikirim
    console.log('AJAX stock_monitoring -> supplier_id:', supplier_id, 'issuedate:', issuedate);

    $.post('app/ajax/search_stock_monitoring.php', {
      suppliar_id: supplier_id,
      issuedate: issuedate
    }, function(data) {
      $("#stock_monitoring_res").html(data);
    }).fail(function(xhr, status, err) {
      $("#stock_monitoring_res").html('<tr><td colspan="8" class="no-data">Error loading data: '+err+'</td></tr>');
      console.error('AJAX error', status, err);
    });
  }

  // klik tombol Cari
  $(document).on('click', '#btnSearchStock', function() {
    var supplier_id = $("#supplier_id").val();
    var issuedate = $("#issuedate").val();

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

    loadStockMonitoring(supplier_id, issuedate);
  });

  // optionally: load default on page load if supplier selected (auto load for current user)
  $(document).ready(function(){
    // auto-load if a supplier is preselected (useful for distributor with hidden select)
    var supplier_id = $("#supplier_id").val();
    if (supplier_id) {
      loadStockMonitoring(supplier_id, $("#issuedate").val());
    }
  });
</script>

