<style>
  /* Biar ukuran mirip input besar */
  .custom-select-lg {
    width: 100%;
    height: 40px;
    font-size: 1rem;
    padding: 0 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
    background-color: #fff;
    appearance: none;
    /* Hilangkan default arrow */
    background-image: url('data:image/svg+xml;utf8,<svg fill="%23777" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
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
</style>
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid mt-5">
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

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">

      <!-- Filter Card -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filter Laporan</h5>
        </div>
        <div class="card-body">
          <div class="row g-3 align-items-end">

            <!-- Date Picker -->
            <div class="col-md-5">
              <label class="fw-bold">Start Date - End Date</label>
              <div id="reportrange" class="form-control" style="cursor: pointer;">
                <i class="fa fa-calendar"></i> &nbsp;
                <span id="search_date"></span>
                <i class="fa fa-caret-down float-end"></i>
              </div>
            </div>

            <!-- Customer Select -->
            <?php if ($_SESSION['role_id'] == 1): ?>
            <div class="col-md-5">
              <label class="fw-bold">Select Member</label>
              <div>
                <select name="customer" id="customer" class="form-select custom-select-lg">
                  <option value="all">- All -</option>
                  <?php 
        $all_customer = $obj->all('suppliar');
        foreach ($all_customer as $customer) {
            echo '<option value="'.$customer->id.'">'.$customer->name.' - '.$customer->suppliar_code.' - '.$customer->role_id.'</option>';
        }
      ?>
                </select>
              </div>
            </div>
            <?php else: ?>
            <!-- Distributor tidak lihat dropdown, tapi tetap kirim ID sendiri -->
            <input type="hidden" name="customer" id="customer" value="<?= $_SESSION['distributor_id'] ?>">
            <?php endif; ?>

            <!-- Submit Button -->
            <div class="col-md-2">
              <button id="search_sales_report" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> Show
              </button>
            </div>

          </div>
        </div>
      </div>

      <!-- Result Table -->
      <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="mb-0"><i class="fas fa-list mr-2"></i>Data Transaksi</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Invoice Number</th>
                  <th>Sales Date</th>
                  <th>Distributor</th>
                  <th>Customer</th>
                  <th>Jumlah Stock</th>
                </tr>
              </thead>
              <tbody id="search_sales_report_res">
                <tr>
                  <td colspan="8" class="text-center text-muted py-3">
                    <em>No data available. Please use filter above.</em>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<!-- .content-wrapper -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script type="text/javascript">
  var start = moment().subtract(29, 'days');
  var end = moment();

  function cb(start, end) {
    $('#reportrange span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
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
</script>

<script type="text/javascript">
  var start = moment().subtract(29, 'days');
  var end = moment();

  function cb(start, end) {
    $('#reportrangeEnd span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
  }

  $('#reportrangeEnd').daterangepicker({
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
</script>
<script>
  $(document).on('click', '#search_sales_report', function (event) {
    event.preventDefault();

    issuedate = $.trim($("#search_date").text());
    var customer = $("#customer option:selected").val();

    $.post('app/ajax/search_sales_report.php', {
      suppliar_id: customer,
      issuedate: issuedate
    }, function (data) {
      $("#search_sales_report_res").html(data);
    });



  });
</script>