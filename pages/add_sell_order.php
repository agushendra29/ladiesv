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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div>
  <!-- Main -->
  <section class="content">
    <div class="mt-5">
      <h2 class="mb-4" style="font-size: 20px; font-weight: 600; color: #333;">📦 Form Penjualan</h2>

      <div id="saleErrorArea"
        style="display:none; color: #b91c1c; margin-bottom: 16px; background-color: #fee2e2; padding: 12px; border-radius: 8px;">
      </div>

      <form id="salesForm">
        <!-- Anggota -->
        <div class="row gx-4 gy-3 mb-4" style="margin-bottom:20px;row-gap:25px;">
          <div class="col-md-6">
            <label for="customer_name" style="font-weight: 500; display: block; margin-bottom: 8px;">Anggota</label>
            <select id="customer_name" name="customer_name" required
              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
              <option value="">Pilih Anggota</option>
              <?php if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 10): ?>
              <option value="0" data-name="Penjualan Pribadi" data-role="0">Penjualan Pribadi</option>
              <?php endif; ?>
              <?php 
                $role_labels = [1 => 'HO', 2 => 'HD', 3 => 'D', 4 => 'A', 5 => 'R'];
                $distributor_id = $_SESSION['distributor_id'];
                $role_id = $_SESSION['role_id'];
                if ($role_id == 10) {
    // role 10 -> tampilkan semua suppliar kecuali dirinya sendiri
                  $all_supplier = $obj->allCondition('suppliar',  'role_id != 10 AND role_id != 1 AND id != ?', [$distributor_id]);
                } else if($role_id == 2) {
                  $all_supplier = $obj->allCondition('suppliar', 'role_id > 3 AND role_id != 10 AND id != ?', [$distributor_id]);   
                }
                  else {
    // selain role 10 -> pakai filter biasa
                  $all_supplier = $obj->allCondition('suppliar', 'role_id > ? AND role_id != 10 AND id != ?', [$role_id, $distributor_id]);
                }
                foreach ($all_supplier as $customer) {
                    $role_text = $role_labels[$customer->role_id] ?? $customer->role_id;
                    echo "<option value='{$customer->id}' 
                              data-name='{$customer->name}' 
                              data-role='{$customer->role_id}'>
                              {$customer->name} - {$customer->suppliar_code} - {$role_text}
                          </option>";
                }
              ?>
            </select>
          </div>

          <div class="col-md-6">
            <input type="hidden" id="buyer" name="buyer" placeholder="Nama Customer" readonly
              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
          </div>
        </div>

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
const productsData = <?php echo json_encode(array_map(function($p){
    $p->pStock = getStockProduct($p->id);
    return $p;
}, $products)); ?>;

const buyerDropdown = document.getElementById('customer_name');
const buyerManual = document.getElementById('buyer');
const totalPaymentInput = document.getElementById('total_payment');
const productRowsBody = document.getElementById('productRowsBody');

function createProductRow() {
  const row = document.createElement('tr');
  row.classList.add('product-row');

  let options = '<option disabled selected>Pilih produk</option>';
  productsData.forEach(p => {
    options += `<option value="${p.id}" 
      data-price-hd="${p.sell_price_hd}" 
      data-price-d="${p.sell_price_d}" 
      data-price-a="${p.sell_price_a}" 
      data-price-r="${p.sell_price_r}">
      ${p.product_name} - Stok: ${p.pStock}
    </option>`;
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

  // Hapus baris
  row.querySelector('.remove-row').addEventListener('click', () => {
    row.remove();
    updateTotalPayment();
  });

  // Cegah produk duplikat
  row.querySelector('.product-select').addEventListener('change', function () {
    const selectedValue = this.value;
    let duplicate = false;
    document.querySelectorAll('.product-select').forEach(select => {
      if (select !== this && select.value === selectedValue && selectedValue !== "") {
        duplicate = true;
      }
    });
    if (duplicate) {
      alert('⚠️ Produk ini sudah dipilih di baris lain.');
      this.selectedIndex = 0;
    }
    updateRowSubtotal(row);
    updateTotalPayment();
  });

  // Hitung ulang saat qty berubah
  row.querySelector('.quantity-input').addEventListener('input', () => {
    updateRowSubtotal(row);
    updateTotalPayment();
  });

  productRowsBody.appendChild(row);
}

function getPriceByRole(option, roleId) {
  switch (parseInt(roleId)) {
    case 1:
    case 2:
      return parseFloat(option.getAttribute('data-price-hd')) || 0;
    case 3:
      return parseFloat(option.getAttribute('data-price-d')) || 0;
    case 4:
      return parseFloat(option.getAttribute('data-price-a')) || 0;
    case 5:
      return parseFloat(option.getAttribute('data-price-r')) || 0;
    default:
      return 0;
  }
}

function updateRowSubtotal(row) {
  const prodSelect = row.querySelector('.product-select');
  const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
  const priceLabel = row.querySelector('.price-label');
  const subtotalLabel = row.querySelector('.subtotal-label');

  const selectedBuyer = buyerDropdown.options[buyerDropdown.selectedIndex];
  const roleId = selectedBuyer ? selectedBuyer.getAttribute('data-role') : null;

  const isPrivateSale = buyerDropdown.value === "0";

  if (isPrivateSale) {
    priceLabel.textContent = "-";
    subtotalLabel.textContent = "-";
  } else {
    let price = 0;
    if (prodSelect && prodSelect.value) {
      price = getPriceByRole(prodSelect.options[prodSelect.selectedIndex], roleId);
    }
    priceLabel.textContent = price.toLocaleString();
    subtotalLabel.textContent = (price * qty).toLocaleString();
  }
}

function updateTotalPayment() {
  const isPrivateSale = buyerDropdown.value === "0";
  let total = 0;

  if (!isPrivateSale) {
    document.querySelectorAll('.product-row').forEach(row => {
      const subtotalLabel = row.querySelector('.subtotal-label');
      total += parseFloat(subtotalLabel.textContent.replace(/,/g, '')) || 0;
    });
    totalPaymentInput.value = total.toLocaleString();
  } else {
    totalPaymentInput.value = "-";
  }
}

document.getElementById('addProductBtn').addEventListener('click', function (e) {
  e.preventDefault();
  createProductRow();
});

buyerDropdown.addEventListener('change', function () {
  const selected = this.options[this.selectedIndex];
  const isPrivateSale = this.value === "0";

  buyerManual.value = isPrivateSale ? 'Penjualan Pribadi' : (selected.getAttribute('data-name') || '');
  document.querySelectorAll('.product-row').forEach(row => updateRowSubtotal(row));
  updateTotalPayment();
});

// Buat baris pertama otomatis
createProductRow();
</script>