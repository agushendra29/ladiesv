<!-- … head dan style tetap sama … -->

<div style="padding-top:52px;">
  <div class="container-fluid">
    <div class="section-card-body">
      <div class="section-title mb-3">Laporan Reseller</div>
      <div class="row mb-3">
        <div class="col-md-4">
          <label>Periode</label>
          <input type="text" id="issuedate" class="form-control" readonly />
        </div>

        <div class="col-md-4">
          <label>Filter Produk (Per Item)</label>
          <select id="filterItem" class="form-control">
            <option value="">Semua Produk</option>
            <!-- isi product … -->
          </select>
        </div>

        <div class="col-md-2 align-self-end">
          <button id="btnSearchStock" class="btn-custom">Cari</button>
        </div>
      </div>

      <div id="reportHeader" style="margin-top:25px; font-size:16px;">
        Penjualan tim Reseller anda periode: &nbsp;
        <span class="section-title" id="periodeText" style="font-size:16px;">-</span>
      </div>

      <div class="table-responsive">
        <table id="stockMonitoringTable" class="display dataTable text-center custom-table">
          <thead>
            <tr>
              <th>ID Distributor</th>
              <th>Nama Distributor</th>
              <th>Total Per Item</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody id="stock_monitoring_res">
            <tr>
              <td colspan="4" class="no-data">Memuat data…</td>
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
  // === ambil tanggal hari ini ===
  const todayStart = moment();
  const todayEnd   = moment();
  const todayText  = todayStart.format('DD/MM/YYYY') + ' - ' + todayEnd.format('DD/MM/YYYY');

  // set default value ke input dan header
  $('#issuedate').val(todayText);
  $('#periodeText').text(todayText);

  // === date range picker dengan default hari ini ===
  $('#issuedate').daterangepicker({
    autoUpdateInput: true,             // otomatis isi input
    startDate: todayStart,
    endDate: todayEnd,
    locale: { format: 'DD/MM/YYYY' },
    opens: 'left',
    ranges: {
      'Hari Ini': [moment(), moment()],
      '7 Hari Terakhir': [moment().subtract(6,'days'), moment()],
      '30 Hari Terakhir': [moment().subtract(29,'days'), moment()],
      'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
      'Bulan Lalu': [moment().subtract(1,'month').startOf('month'),
                     moment().subtract(1,'month').endOf('month')]
    }
  }, function(start, end){
      const periodeText = start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY');
      $('#issuedate').val(periodeText);
      $('#periodeText').text(periodeText);
  });

  function loadStockMonitoring(issuedate, item_id) {
    $.ajax({
      url: 'app/ajax/search_reseller_stock.php',
      method: 'POST',
      data: {
        suppliar_id: <?= json_encode($_SESSION['distributor_id']) ?>,
        issuedate: issuedate,
        item_id: item_id
      },
      cache: false,
      success: function (data) {
        $("#stock_monitoring_res").html(data);
      },
      error: function (xhr, status, err) {
        $("#stock_monitoring_res").html('<tr><td colspan="4" class="no-data">Error: ' + err + '</td></tr>');
      }
    });
  }

  // === render awal: langsung gunakan periode hari ini ===
  $(document).ready(function(){
    loadStockMonitoring(todayText, '');
  });

  // tombol cari
  $('#btnSearchStock').on('click', function(){
    var issuedate = $('#issuedate').val();
    var item_id   = $('#filterItem').val();
    $('#periodeText').text(issuedate || '-');
    loadStockMonitoring(issuedate, item_id);
  });
</script>

