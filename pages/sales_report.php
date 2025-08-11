<style>
  /* ====== Style umum select filter ====== */
  .custom-select-lg {
    width: 100%;
    height: 40px;
    font-size: 1rem;
    padding: 0 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
    background-color: #fff;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%23777" height="24" viewBox="0 0 24 24" width="24" xmlns="https://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
  }
  .custom-select-lg:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    outline: none;
  }

  /* ====== Style untuk tabel ====== */
  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0,0,0,0.07);
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
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }
  table tbody tr:hover {
    background-color: #eff6ff;
    box-shadow: 0 6px 20px rgba(0,115,234,0.15);
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

<div>
  <!-- Header -->
  <div class="content-header">
    <div class="container-fluid mt-3">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1 class="m-0 font-weight-bold text-dark">Sales Report</h1>
        </div>
        <div class="col-md-6">
          <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Sales Report</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Filter -->
      <div class="">
        <div class="card-body">
          <div class="row g-3 align-items-end">
            <div class="col-md-5">
              <label style="font-weight:500">Start Date - End Date</label>
              <div id="reportrange" class="form-control" style="cursor: pointer;">
                <i class="fa fa-calendar"></i> &nbsp;
                <span id="search_date" style="font-weight:500;font-size:14px;"></span>
                <i class="fa fa-caret-down float-end"></i>
              </div>
            </div>

            <?php if ($_SESSION['role_id'] == 1): ?>
            <div class="col-md-5">
              <label class="fw-bold">Select Member</label>
              <div>
                <select name="customer" id="customer" class="form-select custom-select-lg">
                  <option value="all">- All -</option>
                  <?php 
                    $all_customer = $obj->all('suppliar');
                    $roleMap = [1 => 'HO', 2 => 'HD', 3 => 'D', 4 => 'A', 5 => 'R'];
                    foreach ($all_customer as $customer) {
                      $roleLabel = isset($roleMap[$customer->role_id]) ? $roleMap[$customer->role_id] : $customer->role_id;
                      echo '<option value="'.$customer->id.'">'.$customer->name.' - '.$roleLabel.''.$customer->id.'</option>';
                    }
                  ?>
                </select>
              </div>
            </div>
            <?php else: ?>
            <input type="hidden" name="customer" id="customer" value="<?= $_SESSION['distributor_id'] ?>">
            <?php endif; ?>

            <div class="col-md-2">
              <button id="search_sales_report" class="btn btn-primary" style="font-size:12px;height:38px;padding: 0px 20px;">
                <i class="fas fa-search"></i> Search
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Sales Report Table -->
      <div class="mt-4">
        <div class="p-0">
          <div class="table-responsive" style="overflow-x:auto;">
            <table id="salesReportTable" class="text-center">
              <thead>
                <tr>
                  <th>Sales Date</th>
                  <th>Invoice Number</th>
                  <th>Tipe</th>
                  <th>Kepada</th>
                  <th>Kuantitas</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody id="search_sales_report_res">
                <tr>
                  <td colspan="6" class="no-data">
                    No data available. Please use filter above.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Stock Monitoring Table -->
     

    </div>
  </section>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
  // Date range picker
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

  // Load stock monitoring
  function loadStockMonitoring(suppliar_id) {
    $.post('app/ajax/search_sales_report.php', {
      suppliar_id: suppliar_id
    }, function(data) {
      $("#stock_monitoring_res").html(data);
    });
  }

  // Search sales report + stock monitoring
  $(document).on('click', '#search_sales_report', function(event) {
    event.preventDefault();
    let issuedate = $.trim($("#search_date").text());
    let customer = $("#customer").val();
    $.post('app/ajax/search_sales_report.php', {
      suppliar_id: customer,
      issuedate: issuedate
    }, function(data) {
      $("#search_sales_report_res").html(data);
    });
    loadStockMonitoring(customer);
  });
</script>
