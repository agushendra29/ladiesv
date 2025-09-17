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
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.07);
    background: #fff;
    font-size: 13px !important;
    min-width: 900px;
  }

  table thead {
    background-color: #EEA0A0;
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

<div style="padding-top:52px;">
  <!-- Header -->
   <div class="section-card-body" style="margin: 10px;">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="page-custom-header">
          <div class="section-title">Sales Report</div> 
        </div>
       
      </div>
    </div>

  <!-- Content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Filter -->
      <div>
        <div>
          <div class="row g-3 align-items-end">
            <div class="col-md-5">
              <label style="font-weight:500">Start Date - End Date</label>
              <div id="reportrange" class="form-control" style="cursor: pointer;">
                <i class="fa fa-calendar"></i> &nbsp;
                <span id="search_date" style="font-weight:500;font-size:14px;"></span>
                <i class="fa fa-caret-down float-end"></i>
              </div>
            </div>

            <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
            <div class="col-md-5">
              <label class="fw-bold">Select Member</label>
              <div>
                <select name="customer" id="customer" class="form-select custom-select-lg">
                  <option value="all">- All -</option>
                  <?php 
                    $all_customer = $obj->all('suppliar');
                    $roleMap = [1 => 'HO', 2 => 'HD', 3 => 'D', 4 => 'A', 5 => 'R', 10 => 'SA'];
                    foreach ($all_customer as $customer) {
                      $roleLabel = isset($roleMap[$customer->role_id]) ? $roleMap[$customer->role_id] : $customer->role_id;
                      echo '<option value="'.$customer->id.'">'.$customer->name.' - '.$roleLabel.'-'.$customer->suppliar_code.'</option>';
                    }
                  ?>
                </select>
              </div>
            </div>
            <?php endif; ?>
            <div class="col-md-3 mt-3">
              <label class="fw-bold">Tipe Transaksi</label>
              <select id="typeFilter" class="form-select custom-select-lg">
                <option value="all">- All -</option>
                <option value="penjualan">Penjualan</option>
                <option value="pembelian">Pembelian</option>
                <option value="refund">Refund</option>
              </select>
            </div>

            <div class="col-md-3 mt-3">
              <label class="fw-bold">Produk</label>
              <select id="productFilter" class="form-select custom-select-lg">
                <option value="all">- All -</option>
                <!-- <?php 
      $all_products = $obj->all('products');
      foreach ($all_products as $p) {
        echo '<option value="'.$p->id.'">'.$p->product_name.'</option>';
      }
    ?> -->
              </select>
            </div>

            <div class="col-md-2">
              <button id="search_sales_report" class="btn-custom">
                <i class="fas fa-search"></i> Filter
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Sales Report Table -->
      <div class="mt-4">
        <div class="p-0">
          <div class="table-responsive" style="overflow-x:auto;">
            <table id="salesReportTable" class="dataTable custom-table">
              <thead>
                <tr>
                  <th>Sales Date</th>
                  <th>Invoice Number</th>
                  <th>Tipe</th>
                  <th>Kepada</th>
                  <th>Kuantitas</th>
                  <th>Produk</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody id="search_sales_report_res">
                <tr>
                  <td colspan="6" class="no-data">No data available. Please use filter above.</td>
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
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    new Choices("#customer", {
      searchEnabled: true, // aktifkan fitur search
      itemSelectText: '', // hilangkan tulisan "Press to select"
      shouldSort: false, // biar urutan option asli tidak berubah
      placeholderValue: "Pilih Distributor/Agen"
    });
  });
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
    }, function (data) {
      $("#stock_monitoring_res").html(data);
    });
  }

  // Search sales report + stock monitoring
  $(document).on('click', '#search_sales_report', function (event) {
    event.preventDefault();
    let issuedate = $.trim($("#search_date").text());
    let customer = $("#customer").val();
    let type = $("#typeFilter").val(); // filter tipe
    let product_id = $("#productFilter").val(); // filter produk

    console.log(product_id)
    $.post('app/ajax/search_sales_report.php', {
      suppliar_id: customer,
      issuedate: issuedate,
      type: type,
      product_id: product_id
    }, function (data) {

      $("#search_sales_report_res").html(data);
    });
    loadStockMonitoring(customer);
  });

  $(function () {
    let currentPage = 1;

    function loadSalesReport(page = 1) {
      currentPage = page;
      let issuedate = $.trim($("#search_date").text());
      let customer = $("#customer").val();
      let type = $("#typeFilter").val(); // filter tipe
      let product_id = $("#productFilter").val(); // filter produk
      console.log(type);
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

    // Search button click
    $(document).on('click', '#search_sales_report', function (e) {
      e.preventDefault();
      loadSalesReport(1);
    });

    // Pagination link click
    $(document).on('click', '.pagination .page-link', function (e) {
      e.preventDefault();
      const page = $(this).data('page');
      if (page && page !== currentPage) {
        loadSalesReport(page);
      }
    });

    // Optional: load default page on first load
    loadSalesReport(1);
  });
</script>