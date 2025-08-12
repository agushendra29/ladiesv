<!-- CSS daterangepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
  /* Style tabel mengikuti style sales_report */
  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.07);
    background: #fff;
    font-size: 13px !important;
    min-width: 900px;
  }

  table thead {
    background-color: #2563eb;
    color: #fff;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.05em;
    font-size: 11px;
  }

  table thead th {
    padding: 16px 20px;
    border-bottom: none;
  }

  table thead th:first-child {
    border-top-left-radius: 12px;
  }

  table thead th:last-child {
    border-top-right-radius: 12px;
  }

  table tbody tr {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  table tbody tr:hover {
    background-color: #eff6ff;
    box-shadow: 0 6px 20px rgba(0, 115, 234, 0.15);
  }

  table tbody tr td {
    padding: 14px 22px;
    vertical-align: middle;
    font-weight: 500;
    color: #374151;
  }

  .no-data {
    text-align: center;
    color: #9ca3af;
    font-style: italic;
    padding: 30px 0;
  }
</style>

<div class="container mt-5">
  <h3 class="mb-3 mt-4">Stock Monitoring</h3>
  
  <div class="row mb-3">
    <div class="col-md-4">
      <label>Periode</label>
      <input type="text" id="issuedate" class="form-control" readonly />
    </div>

    <?php if ($_SESSION['role_id']  == 1): ?>
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
    <?php else: ?>
      <!-- untuk non admin, supplier_id diset hidden -->
      <input type="hidden" id="supplier_id" value="<?= htmlspecialchars($distributor_id) ?>">
    <?php endif; ?>

    <div class="col-md-2 align-self-end">
      <button id="btnSearchStock" class="btn btn-primary">Cari</button>
    </div>
  </div>

  <div class="table-responsive">
    <table id="stockMonitoringTable" class="table text-center">
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

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
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
  function loadStockMonitoring(supplier_id, issuedate) {
    console.log('AJAX stock_monitoring -> supplier_id:', supplier_id, 'issuedate:', issuedate);

    $.ajax({
      url: 'app/ajax/search_stock_monitoring.php',
      method: 'POST',
      data: {
        suppliar_id: supplier_id,
        issuedate: issuedate
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

</script>
