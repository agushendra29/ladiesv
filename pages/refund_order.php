<style>
    /* ====== Filter Section Styling ====== */
    .filter-section {
        background: #fff;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .filter-section label {
        font-weight: 600;
        font-size: 12px;
        margin-bottom: 6px;
        display: block;
        color: #374151;
    }

    .filter-section .form-control,
    .filter-section .form-select {
        font-size: 12px;
        border-radius: 8px;
        width: 100%; /* biar dropdown panjang penuh */
    }

    #reportrange {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12px;
        font-weight: 500;
        padding: 8px 10px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
    }

    #search_refund {
        width: 100%;
        font-size: 12px;
        font-weight: 600;
        height: 36px;
        border-radius: 8px;
        padding: 6px 10px;
    }

    /* Responsive filter layout */
    @media (max-width: 768px) {
        .filter-section .row {
            gap: 12px;
        }
        #search_refund {
            margin-top: 4px;
        }
    }

    /* ====== Table Styling ====== */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.07);
        background: #fff;
        font-size: 11px !important; /* kecil */
        min-width: 900px;
    }

    table thead {
        background-color: #2563eb;
        color: #fff;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.04em;
        font-size: 10px !important;
    }

    table thead th {
        padding: 10px 14px;
        border-bottom: none;
    }

    table tbody tr {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    table tbody tr:hover {
        background-color: #eff6ff;
        box-shadow: 0 4px 12px rgba(0, 115, 234, 0.12);
    }

    table tbody tr td {
        padding: 10px 14px;
        vertical-align: middle;
        font-weight: 500;
        color: #374151;
        font-size: 11px;
    }

    .no-data {
        text-align: center;
        color: #9ca3af;
        font-style: italic;
        padding: 25px 0;
        font-size: 11px;
    }
</style>

<div>
    <!-- Header -->
    <div class="content-header">
        <div class="container-fluid mt-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="m-0 font-weight-bold text-dark" style="font-size:18px;">Cancel Invoice</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0" style="font-size:12px;">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Cancel Invoice</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Filter -->
            <div class="filter-section">
                <div class="card-body p-0">
                    <div class="row">
                        <!-- Date Range -->
                        <div class="col-md-4">
                            <label>Start Date - End Date</label>
                            <div id="reportrange" class="form-control">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span id="search_date"></span>
                                <i class="fa fa-caret-down float-end"></i>
                            </div>
                        </div>

                        <!-- Select Member -->
                        <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
                        <div class="col-md-4">
                            <label>Select Member</label>
                            <select name="customer" id="customer" class="form-select">
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
                        <?php else: ?>
                        <input type="hidden" name="customer" id="customer" value="<?= $_SESSION['distributor_id'] ?>">
                        <?php endif; ?>

                        <!-- Type Filter -->
                        <div class="col-md-3">
                            <label>Tipe Transaksi</label>
                            <select id="typeFilter" class="form-select">
                                <option value="all">- All -</option>
                                <option value="penjualan">Penjualan</option>
                                <option value="pembelian">Pembelian</option>
                                <option value="refund">Refund</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button id="search_refund" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Report Table -->
            <div class="mt-3">
                <div class="table-responsive">
                    <table id="salesReportTable" class="text-center">
                        <thead>
                            <tr>
                                <th>Sales Date</th>
                                <th>Invoice Number</th>
                                <th>Tipe</th>
                                <th>Kepada</th>
                                <th>Produk</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="refund_order_data_res">
                            <tr>
                                <td colspan="7" class="no-data">No data available. Please use filter above.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (document.querySelector("#customer")) {
            new Choices("#customer", {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                placeholderValue: "Pilih Distributor/Agen"
            });
        }
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

    // Search sales report + stock monitoring
    $(document).on('click', '#search_refund', function (event) {
        event.preventDefault();
        let issuedate = $.trim($("#search_date").text());
        let customer = $("#customer").val();
        let type = $("#typeFilter").val();
        $.post('app/ajax/refund_order_data.php', {
            suppliar_id: customer,
            issuedate: issuedate,
            type: type
        }, function (data) {
            $("#refund_order_data_res").html(data);
        });
        loadSalesReport(1);
    });

    // Pagination
    $(function () {
        let currentPage = 1;

        function loadSalesReport(page = 1) {
            currentPage = page;
            let issuedate = $.trim($("#search_date").text());
            let customer = $("#customer").val();
            let type = $("#typeFilter").val();
            $.post('app/ajax/refund_order_data.php', {
                suppliar_id: customer,
                issuedate: issuedate,
                type: type,
                page: page
            }, function (data) {
                $("#refund_order_data_res").html(data);
            });
        }

        $(document).on('click', '.pagination .page-link', function (e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page && page !== currentPage) {
                loadSalesReport(page);
            }
        });

        // Load default
        loadSalesReport(1);
    });
</script>
