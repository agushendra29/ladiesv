<div>
  <!-- Header -->
  <section class="content">
    <div class="container-fluid mt-5">
      <div>
        <h2 style="margin-bottom: 24px; font-size: 20px; font-weight: 600; color: #333;">📝 Form Pemesanan Produk</h2>
        
        <div class="alert alert-danger purchaseOrderError-area" style="display:none;">
          <span id="purchaseOrderError"></span>
        </div>

        <form id="purchaseOrderForm">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="product_id" style="font-weight: 500;">Produk *</label>
              <select name="product_id" id="product_id" class="form-control select2" required style="border-radius: 8px;">
                <option disabled selected>Pilih produk</option>
                <?php 
                  $products = $obj->all('products');
                  foreach ($products as $product) {
                    echo "<option value='{$product->id}' data-price='{$product->sell_price_hd}'>{$product->product_name}</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="quantity" style="font-weight: 500;">Kuantitas</label>
              <input type="number" name="quantity" id="quantity" class="form-control" min="1" required style="border-radius: 8px;">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="distributor" style="font-weight: 500;">Distributor *</label>
              <input type="text" name="distributor" id="distributor" class="form-control" value="Head Officer" readonly style="border-radius: 8px;">
            </div>
            <div class="col-md-6 mb-3">
              <label for="total_payment" style="font-weight: 500;">Total Pembayaran</label>
              <input type="number" name="total_payment" id="total_payment" class="form-control" min="1" readonly style="border-radius: 8px;">
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-6 offset-md-3 text-center">
              <button type="reset" style="
                background-color: #f44336;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 10px 20px;
                font-weight: 600;
                font-size: 14px;
                margin-right: 10px;
                transition: background-color 0.3s ease;"
                onmouseover="this.style.backgroundColor='#d32f2f'"
                onmouseout="this.style.backgroundColor='#f44336'"
              >Reset</button>

              <button type="submit" style="
                background-color: #0073ea;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 10px 20px;
                font-weight: 600;
                font-size: 14px;
                transition: background-color 0.3s ease;"
                onmouseover="this.style.backgroundColor='#005bb5'"
                onmouseout="this.style.backgroundColor='#0073ea'"
              >Submit PO</button>
            </div>
          </div>
        </form>
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
