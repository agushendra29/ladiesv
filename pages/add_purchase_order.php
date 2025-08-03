<div class="content-wrapper">
  <!-- Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <h1 class="m-0 text-dark">Purchase Order</h1>
        </div>
        <div class="col-md-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Purchase Order</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><b>Form Pemesanan</b></h3>
        </div>
        <div class="card-body">
          <div class="alert alert-danger purchaseOrderError-area" style="display:none;">
            <span id="purchaseOrderError"></span>
          </div>
          <form id="purchaseOrderForm">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="product_id">Pilih Produk *:</label>
                  <select name="product_id" id="product_id" class="form-control select2" required>
                    <option disabled selected>Pilih produk</option>
                    <?php 
                      $products = $obj->all('products');
                      foreach ($products as $product) {
                        echo "<option value='{$product->id}' data-price='{$product->sell_price}'>{$product->product_name}</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="quantity">Kuantitas</label>
                  <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="product_id">Pilih Distributor *:</label>
                  <select name="distributor_id" id="distributor_id" class="form-control select2" required>
                    <option disabled selected>Pilih produk</option>
                    <?php 
                      $products = $obj->all('suppliar');
                      foreach ($products as $product) {
                        echo "<option value='{$product->suppliar_id}'>{$product->name}</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="total_payment">Jumlah Pembayaran</label>
                    <input type="number" name="total_payment" id="total_payment" class="form-control" min="1" required>
                  </div>
                </div>
            </div>
            <!-- Submit -->
            <div class="row text-center mt-4">
              <div class="col-md-6 offset-md-3">
                <button type="reset" class="btn btn-danger rounded-0 px-4">Reset</button>
                <button type="submit" class="btn btn-primary rounded-0 px-4">Submit PO</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  const productSelect = document.getElementById('product_id');
  const quantityInput = document.getElementById('quantity');
  const paymentInput = document.getElementById('total_payment');

  function updateTotalPayment() {
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    const quantity = parseInt(quantityInput.value) || 0;

    const total = price * quantity;
    paymentInput.value = total.toFixed(2);
  }

  productSelect.addEventListener('change', updateTotalPayment);
  quantityInput.addEventListener('input', updateTotalPayment);
</script>
