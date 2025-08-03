<div class="content-wrapper">
  <!-- Purchase Order Page -->
  <section class="content">
    <div class="container-fluid mt-5">
      <div class="card mt-5">
        <div class="card-header">
          <div class="row">
            <h4><b>List Order</b></h4>
          </div>
            <a href="index.php?page=add_purchase_order" class="btn btn-success btn-sm float-right" style="margin:8px; border-radius:6px;">
              <i class="fas fa-plus"></i> Tambah Pemesanan
            </a>
          
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table id="purchaseOrderTable" class="display dataTable text-center">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Distributor</th>
                  <th>Total Amount</th>
                  <th>Status</th>
                  <th>Item</th>
                  <th>Created At</th>
                  <th>Approved At</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>