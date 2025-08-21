<?php 
$distributor_id = $_SESSION['distributor_id'];
$products = $obj->all('products');

function getStockProduct($pid) {
    global $obj, $distributor_id; 
    $stockData = $obj->allCondition(
        'distributor_stocks', 
        "product_id = ? AND suppliar_id = ?", 
        [$pid, $distributor_id]
    );

    if (!empty($stockData)) {
        return $stockData[0]->stock; 
    }
    return 0; 
}
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div>
  <section class="content">
    <div class="mt-5">
      <h2 class="mb-4" style="font-size: 20px; font-weight: 600; color: #333;">📦 Form Penjualan</h2>

      <div id="saleErrorArea"
        style="display:none; color: #b91c1c; margin-bottom: 16px; background-color: #fee2e2; padding: 12px; border-radius: 8px;">
      </div>

      <!-- Form Penjualan -->
      <form id="salesForm">
        <!-- Tombol Tambah Produk -->
        <a id="addProductBtn" style="background-color: #0073ea; color: #fff; border-radius: 12px; padding: 8px 20px;
          font-weight: 600; font-size: 12px; border: none; box-shadow: 0 4px 12px rgba(0,115,234,0.4);
          transition: background-color 0.3s ease, box-shadow 0.3s ease; display: inline-flex; align-items: center;
          gap: 8px; cursor: pointer; text-decoration:none;">
          + Tambah Produk
        </a>

        <!-- Tabel Produk -->
        <div id="productRows" style="margin: 20px 0;">
          <table style="width:100%; border-collapse: collapse; font-size:14px;">
            <thead>
              <tr style="background:#f3f4f6; text-align:left;">
                <th style="padding:10px; border-bottom:1px solid #e5e7eb;text-align:center">Produk</th>
                <th style="padding:10px; border-bottom:1px solid #e5e7eb;text-align:center">Qty</th>
                <th style="padding:10px; border-bottom:1px solid #e5e7eb;text-align:center">Harga</th>
                <th style="padding:10px; border-bottom:1px solid #e5e7eb;text-align:center">Subtotal</th>
                <th style="padding:10px; border-bottom:1px solid #e5e7eb;text-align:center">Aksi</th>
              </tr>
            </thead>
            <tbody id="productRowsBody"></tbody>
          </table>
        </div>

        <div class="row">
          <div class="col-md-6">
            <label for="total_payment" style="font-weight: 500; display: block; margin-bottom: 8px;">Total
              Pembayaran</label>
            <input type="text" id="total_payment" name="total_payment" readonly placeholder="Otomatis terisi"
              style="width: 100%; padding: 12px; background-color: #f9fafb; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; text-align:right;">
          </div>
        </div>

        <div style="text-align: center; margin-top: 32px;">
          <button type="reset"
            style="background-color: #ef4444; color: white; padding: 12px 24px; border: none; border-radius: 10px; margin-right: 12px; cursor: pointer; font-weight: 500;">
            Reset
          </button>
          <button type="submit"
            style="background-color: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 10px; cursor: pointer; font-weight: 500;">
            Submit Penjualan
          </button>
        </div>
      </form>

      <hr style="margin: 40px 0;">

      <!-- Form Register User / Anggota -->
      <form id="registerUserForm">
        <h4 style="margin-bottom: 20px;">📝 Form Register Anggota</h4>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="reg_username">Username *</label>
              <input type="text" class="form-control" id="reg_username" name="reg_username" placeholder="Username" required>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="reg_email">Email *</label>
              <input type="email" class="form-control" id="reg_email" name="reg_email" placeholder="Email" required>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="reg_password">Password *</label>
              <input type="password" class="form-control" id="reg_password" name="reg_password" placeholder="Password" required>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="reg_role">Level Anggota *</label>
              <select class="form-control" id="reg_role" name="reg_role" required>
                <option value="">-- Pilih Level Anggota --</option>
                <?php if ($_SESSION['role_id'] == 10): ?>
                  <option value="10">Super Admin</option>
                  <option value="1">Head Office</option>
                <?php endif; ?>
                <option value="2">Head Distributor</option>
                <option value="3">Distributor</option>
                <option value="4">Agen</option>
                <option value="5">Reseller</option>
              </select>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="reg_address">Alamat:</label>
              <textarea class="form-control" id="reg_address" name="reg_address" rows="3" placeholder="Alamat lengkap"></textarea>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-success btn-block rounded-0 mt-3">Register Anggota</button>
      </form>

    </div>
  </section>
</div>

<?php
$productsData = [];
foreach ($products as $p) {
    $p->pStock = getStockProduct($p->id);
    $productsData[] = $p;
}
?>

<script>
const productsData = <?php echo json_encode($productsData); ?>;
const totalPaymentInput = document.getElementById('total_payment');
const productRowsBody = document.getElementById('productRowsBody');

function createProductRow() {
  const row = document.createElement('tr');
  row.classList.add('product-row');

  let options = '<option disabled selected>Pilih produk</option>';
  productsData.forEach(p => {
    options += `<option value="${p.id}" data-price="${p.sell_price_r}">${p.product_name} - Stok: ${p.pStock}</option>`;
  });

  row.innerHTML = `
    <td style="padding:8px;">
      <select name="product_id[]" class="product-select" required 
        style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;">
        ${options}
      </select>
    </td>
    <td style="padding:8px; text-align:center;">
      <input type="number" name="quantity[]" class="quantity-input" min="1" value="1" required
        style="width:70px; padding:6px; border-radius:8px; border:1px solid #ccc; text-align:center;">
    </td>
    <td style="padding:8px; text-align:right;">
      <span class="price-label">0</span>
    </td>
    <td style="padding:8px; text-align:right;">
      <span class="subtotal-label">0</span>
    </td>
    <td style="padding:8px; text-align:center;">
      <button type="button" class="remove-row" 
        style="background:#ef4444; color:white; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">
        Hapus
      </button>
    </td>
  `;

  row.querySelector('.remove-row').addEventListener('click', () => {
    row.remove();
    updateTotalPayment();
  });

  row.querySelector('.product-select').addEventListener('change', () => updateRowSubtotal(row));
  row.querySelector('.quantity-input').addEventListener('input', () => updateRowSubtotal(row));

  productRowsBody.appendChild(row);
}

function updateRowSubtotal(row) {
  const prodSelect = row.querySelector('.product-select');
  const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
  const priceLabel = row.querySelector('.price-label');
  const subtotalLabel = row.querySelector('.subtotal-label');

  let price = 0;
  if (prodSelect && prodSelect.value) {
    price = parseFloat(prodSelect.options[prodSelect.selectedIndex].getAttribute('data-price')) || 0;
  }

  priceLabel.textContent = price.toLocaleString();
  subtotalLabel.textContent = (price * qty).toLocaleString();
  updateTotalPayment();
}

function updateTotalPayment() {
  let total = 0;
  document.querySelectorAll('.product-row').forEach(row => {
    const subtotalLabel = row.querySelector('.subtotal-label');
    total += parseFloat(subtotalLabel.textContent.replace(/,/g, '')) || 0;
  });
  totalPaymentInput.value = total.toLocaleString();
}

document.getElementById('addProductBtn').addEventListener('click', function (e) {
  e.preventDefault();
  createProductRow();
});

// Buat baris pertama otomatis
createProductRow();
</script>
