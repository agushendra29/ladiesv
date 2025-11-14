<style>
  /* ====== Global Font ====== */
  body, .form-select, .form-control, .btn-custom, .btn-export, label, table, th, td {
    font-size: 12px;
  }

  /* ====== Layout Wrapper untuk Filter ====== */
  .filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: flex-end;
    margin-top: 10px;
  }

  .filter-group {
    flex: 1 1 180px;
    min-width: 160px;
    display: flex;
    flex-direction: column;
  }

  .filter-actions {
    display: flex;
    align-items: flex-end;
    gap: 6px;
  }

  /* ====== Input dan Select ====== */
  .filter-select,
  .filter-input,
  #reportrange.form-control {
    width: 100%;
    height: 28px;
    font-size: 12px;
    padding: 4px 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    color: #333;
    background-color: #fff;
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
  }

  .filter-select:focus,
  #reportrange:focus {
    border-color: #ff5ca2;
    box-shadow: 0 0 0 2px rgba(255, 92, 162, 0.25);
    outline: none;
  }

  /* ====== Tombol ====== */
  .filter-btn {
    border: none;
    border-radius: 6px;
    padding: 4px 12px;
    height: 28px;
    font-size: 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
    color: white;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
  }

  /* Tombol Filter (pink Monday style) */
  .btn-filter {
    background-color: #ff5ca2;
  }

  .btn-filter:hover {
    background-color: #e14e90;
  }

  /* Tombol Export (green) */
  .btn-export {
    background-color: #28a745;
  }

  .btn-export:hover {
    background-color: #1e7e34;
  }

  /* ====== Label ====== */
  label {
    font-weight: 500;
    margin-bottom: 2px;
  }

  /* ====== Table ====== */
  .no-data {
    text-align: center;
    color: #9ca3af;
    font-style: italic;
    padding: 20px 0;
  }

  .custom-table th {
    font-size:10px !important;
  }

  .custom-table td {
    font-size:10px !important;
  }
</style>

<div style="padding-top:52px;">
  <div class="section-card-body" style="margin: 10px;">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="page-custom-header">
          <div class="section-title">Sales Report</div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">

        <!-- 🔍 FILTER BAR -->
        <div class="filter-row">
          <div class="filter-group">
            <label>Start Date - End Date</label>
            <div id="reportrange" class="form-control filter-input">
              <i class="fa fa-calendar"></i>
              <span id="search_date" style="flex:1; text-align:center;">Select Date</span>
              <i class="fa fa-caret-down"></i>
            </div>
          </div>

          <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
          <div class="filter-group">
            <label>Select Member</label>
            <select name="customer" id="customer" class="filter-select">
              <option value="all">- All -</option>
              <?php 
              $all_customer = $obj->all('suppliar');
              $roleMap = [1 => 'HO', 2 => 'HD', 3 => 'D', 4 => 'A', 5 => 'R', 10 => 'SA'];
              foreach ($all_customer as $customer) {
                if (in_array((int)$customer->role_id, [1,10])) {
                  if ($customer->suppliar_code === '000001') {
                    echo '<option value="'.$customer->id.'">Head Office</option>';
                  }
                  continue;
                }
                $roleLabel = $roleMap[$customer->role_id] ?? $customer->role_id;
                echo '<option value="'.$customer->id.'">'
                      .htmlspecialchars($customer->name.' - '.$roleLabel.' - '.$customer->suppliar_code).
                    '</option>';
              }
              ?>
            </select>
          </div>
          <?php endif; ?>

          <div class="filter-group">
            <label>Tipe Transaksi</label>
            <select id="typeFilter" class="filter-select">
              <option value="all">- All -</option>
              <option value="penjualan">Penjualan</option>
              <option value="pembelian">Pembelian</option>
            </select>
          </div>

          <div class="filter-group">
            <label>Produk</label>
            <select id="productFilter" class="filter-select">
              <option value="all">- All -</option>
            </select>
          </div>

          <div class="filter-actions">
            <button id="search_sales_report" class="filter-btn btn-filter">
              <i class="fas fa-search"></i> Filter
            </button>
            <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
            <button id="export_excel" class="filter-btn btn-export">
              <i class="fas fa-file-excel"></i> Export
            </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- 📊 TABLE -->
        <div class="mt-4">
          <div class="table-responsive">
            <table id="salesReportTable" class="display dataTable text-center custom-table">
              <thead>
                <tr>
                  <th>Sales Date</th>
                  <th>Invoice Number</th>
                  <th>Dari</th>
                  <th>Tipe</th>
                  <th>Kepada</th>
                  <th>Kuantitas</th>
                  <th>Produk</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody id="search_sales_report_res">
                <tr><td colspan="7" class="no-data">No data available. Please use filter above.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>


<!-- JS -->
<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
  // Daterange setup
  var start = moment().subtract(29, 'days');
  var end = moment();
  function cb(start, end) {
    $('#reportrange span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
    $('#search_date').text(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
  }
  $('#reportrange').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month': [moment().startOf('month'), moment().endOf('month')],
      'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
  }, cb);
  cb(start, end);

  // AJAX load report
  $(function () {
    let currentPage = 1;
    function loadSalesReport(page = 1) {
      currentPage = page;
      let issuedate = $.trim($("#search_date").text());
      let customer = $("#customer").val();
      let type = $("#typeFilter").val();
      let product_id = $("#productFilter").val();
      $.post('app/ajax/search_sales_report.php', {
        suppliar_id: customer,
        issuedate: issuedate,
        type: type,
        product_id: product_id,
        page: page
      }, function (data) {
        $("#search_sales_report_res").html(data);
      });
    }

    $(document).on('click', '#search_sales_report', function (e) {
      e.preventDefault();
      loadSalesReport(1);
    });

    $(document).on('click', '.pagination .page-link', function (e) {
      e.preventDefault();
      const page = $(this).data('page');
      if (page && page !== currentPage) {
        loadSalesReport(page);
      }
    });

    $(document).on('click', '#export_excel', function (e) {
      e.preventDefault();
      let issuedate = $.trim($("#search_date").text());
      let customer = $("#customer").val();
      let type = $("#typeFilter").val();
      let product_id = $("#productFilter").val();

      if (!issuedate) {
        alert("Pilih rentang tanggal terlebih dahulu!");
        return;
      }
      const dates = issuedate.split(" - ");
      if (dates.length !== 2) {
        alert("Format tanggal tidak valid!");
        return;
      }

      const start = moment(dates[0], "MM/DD/YYYY").format("YYYY-MM-DD");
      const end = moment(dates[1], "MM/DD/YYYY").format("YYYY-MM-DD");
      window.location.href = `app/export/export_sales_report.php?start=${start}&end=${end}&suppliar_id=${customer}&type=${type}&product_id=${product_id}`;
    });

    // Load pertama
    loadSalesReport(1);
  });
</script>
