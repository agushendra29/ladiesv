<?php 
$distributor_id = $_SESSION['distributor_id'];
$products = $obj->all('products');
$disablePribadi = ($role_id == 1 || $role_id == 10) ? 'disabled' : '';
function getStockProduct($pid) {
    global $obj, $distributor_id;
    $role_id = $_SESSION['role_id'] ?? null;

    if ($role_id == 10 || $role_id == 1) {
        $stockData = $obj->allCondition('distributor_stocks', "product_id = ? AND suppliar_id = ?", [$pid, 1]);
    } else {
        $stockData = $obj->allCondition('distributor_stocks', "product_id = ? AND suppliar_id = ?", [$pid, $distributor_id]);
    }

    if (!empty($stockData)) {
        return is_array($stockData[0]) ? $stockData[0]['stock'] : $stockData[0]->stock;
    }
    return 0;
}

// Ambil daftar anggota untuk autocomplete
$role_labels = [1 => 'HO', 2 => 'HD', 3 => 'D', 4 => 'A', 5 => 'R'];
$role_id = $_SESSION['role_id'];
if ($role_id == 10) {
    $all_supplier = $obj->allCondition('suppliar',  'role_id != 10 AND role_id != 1 AND id != ?', [$distributor_id]);
} else if($role_id == 2) {
    $all_supplier = $obj->allCondition('suppliar', 'role_id > 3 AND role_id != 10 AND id != ?', [$distributor_id]);   
} else {
    $all_supplier = $obj->allCondition('suppliar', 'role_id > ? AND role_id != 10 AND id != ?', [$role_id, $distributor_id]);
}

$members = [];
foreach ($all_supplier as $customer) {
    $role_text = $role_labels[$customer->role_id] ?? $customer->role_id;
    $members[] = [
        'id' => $customer->id,
        'name' => $customer->name,
        'code' => $customer->suppliar_code,
        'role' => $customer->role_id,
        'label' => "{$customer->name} - {$customer->suppliar_code} - {$role_text}"
    ];
}
$membersJson = json_encode($members);

// Tambahkan stock ke setiap produk
$productsData = [];
foreach ($products as $p) {
    $p->pStock = getStockProduct($p->id);
    if($p->pStock > 0) {
      $productsData[] = $p;
    }
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="m-3 container-fluid" style="min-height:100vh;padding-bottom:72px;">
  <section class="content">
    <div class="mt-5">
      <h2 class="mb-4" style="font-size: 20px; font-weight: 600; color: #333;">📦 Form Penjualan</h2>
      <div id="saleErrorArea"
        style="display:none; color: #b91c1c; margin-bottom: 16px; background-color: #fee2e2; padding: 12px; border-radius: 8px;">
      </div>

      <form id="salesForm">
        <!-- Jenis Penjualan -->
        <div class="row gx-4 gy-3 mb-4">
          <div class="col-md-12">
            <label style="font-weight: 500; display: block; margin-bottom: 8px;">Jenis Penjualan</label>
            <div style="display:flex; gap:20px; align-items:center;">
              <label><input type="radio" name="sale_type" value="anggota" checked> Penjualan Anggota</label>
              <label><input type="radio" name="sale_type" value="pribadi" <?= $disablePribadi; ?>> Penjualan Pribadi</label>
            </div>
          </div>
        </div>

        <!-- Anggota -->
        <div class="row gx-4 gy-3 mb-4" style="position:prelative;">
          <div class="col-md-6">
            <label for="customer_name" style="font-weight: 500; display: block; margin-bottom: 8px;">Anggota</label>
            <input type="text" id="customer_name" placeholder="Ketik nama anggota..." autocomplete="off"
              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; background:white;">
            <div id="customerList" style="position:absolute; background:white; border:1px solid #ccc; border-radius:6px; max-height:150px; overflow:auto; display:none; z-index:99;"></div>
          </div>
          <div class="col-md-6">
            <input type="hidden" id="buyer" name="buyer" readonly>
            <input type="hidden" id="buyer_id" name="buyer_id">
          </div>
        </div>

        <a id="addProductBtn" style="background-color: #EB4F7D; color: #fff; border-radius: 12px; padding: 8px 20px;
          font-weight: 600; font-size: 12px; border: none; box-shadow: 0 4px 12px rgba(0,115,234,0.4);
          display: inline-flex; align-items: center; gap: 8px; cursor: pointer; text-decoration:none;">+ Tambah Produk</a>

        <!-- Tabel Produk -->
        <div id="productRows" style="margin: 20px 0; overflow-x:auto;">
          <table class="product-table">
            <thead>
              <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="productRowsBody"></tbody>
          </table>
        </div>

        <div class="row">
          <div class="col-md-6">
            <label for="total_payment" style="font-weight: 500; display: block; margin-bottom: 8px;">Total Pembayaran</label>
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
            style="background-color: #ef4444; color: white; padding: 12px 24px; border: none; border-radius: 10px; cursor: pointer; font-weight: 500;">
            Submit Penjualan
          </button>
        </div>
      </form>
    </div>
  </section>
</div>

<style>
.product-table { width: 100%; border-collapse: collapse; font-size: 14px; min-width: 800px; }
.product-table thead { background: #f3f4f6; }
.product-table th, .product-table td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: center; }

@media (max-width: 768px) {
  .product-table { min-width: unset; }
  .product-table thead { display: none; }
  .product-table tr { display: block; margin-bottom: 15px; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; background: #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
  .product-table td { display: flex; justify-content: space-between; align-items: center; padding: 8px; border: none; border-bottom: 1px solid #f3f4f6; font-size: 13px; text-align: left; }
  .product-table td:last-child { border-bottom: none; }
  .product-table td:before { content: attr(data-label); font-weight: 600; color: #374151; }
}

/* List autocomplete anggota */#customerList {
  position: absolute;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  max-height: 200px;
  overflow-y: auto;
  width: 100%;
  z-index: 999;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  display: none;
}

#customerList div {
  padding: 10px 12px;
  cursor: pointer;
  font-size: 14px;
  border-bottom: 1px solid #e5e7eb;
}

#customerList div:last-child { border-bottom: none; }

#customerList div:hover {
  background-color: #f3f4f6;
}
</style>

<script>
const productsData = <?php echo json_encode($productsData); ?>;
const members = <?php echo $membersJson; ?>;

const customerInput = document.getElementById('customer_name');
const customerList = document.getElementById('customerList');
const buyerManual = document.getElementById('buyer');
const buyerId = document.getElementById('buyer_id');
const totalPaymentInput = document.getElementById('total_payment');
const productRowsBody = document.getElementById('productRowsBody');

// Autocomplete anggota
customerInput.addEventListener('input', function() {
  const val = this.value.trim().toLowerCase();
  customerList.innerHTML = '';
  if (!val) { customerList.style.display = 'none'; return; }

  // Hanya munculkan yang **sama persis** dengan input (name, code, atau label)
  const matches = members.filter(m => {
    return m.name.toLowerCase() === val ||
           m.code.toLowerCase() === val ||
           m.label.toLowerCase() === val;
  });

  matches.forEach(m => {
    const div = document.createElement('div');
    div.textContent = m.label;
    div.dataset.id = m.id;
    div.dataset.name = m.name;
    div.dataset.role = m.role;
    div.dataset.code = m.code;

    div.addEventListener('click', () => {
      customerInput.value = m.label;
      buyerManual.value = m.name;
      buyerId.value = m.id;
      customerList.style.display = 'none';
      updateAllRows();
      updateTotalPayment();
    });

    customerList.appendChild(div);
  });

  customerList.style.display = matches.length ? 'block' : 'none';
});

// Hide list saat klik di luar
document.addEventListener('click', function(e){
  if (!customerInput.contains(e.target) && !customerList.contains(e.target)){
    customerList.style.display = 'none';
  }
});

// ===== Produk =====
function createProductRow() {
  const row = document.createElement('tr');
  row.classList.add('product-row');

  let options = '<option disabled selected value="">Pilih produk</option>';
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
    <td data-label="Produk">
      <select name="product_id[]" class="product-select" required style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;">
        ${options}
      </select>
    </td>
    <td data-label="Qty">
      <input type="number" name="quantity[]" class="quantity-input" min="1" value="1" required style="width:70px; padding:6px; border-radius:8px; border:1px solid #ccc; text-align:center;">
    </td>
    <td data-label="Harga"><span class="price-label">0</span></td>
    <td data-label="Subtotal"><span class="subtotal-label">0</span></td>
    <td data-label="Aksi">
      <button type="button" class="remove-row" style="background:#ef4444; color:white; border:none; border-radius:6px; padding:6px 10px; cursor:pointer;">Hapus</button>
    </td>
  `;

  row.querySelector('.remove-row').addEventListener('click', () => {
    row.remove();
    updateTotalPayment();
  });

  row.querySelector('.product-select').addEventListener('change', function () {
    const selectedValue = this.value;
    let duplicate = false;
    document.querySelectorAll('.product-select').forEach(select => {
      if (select !== this && select.value === selectedValue && selectedValue !== "") duplicate = true;
    });
    if (duplicate) { alert('⚠️ Produk ini sudah dipilih di baris lain.'); this.selectedIndex = 0; }
    updateRowSubtotal(row);
    updateTotalPayment();
  });

  row.querySelector('.quantity-input').addEventListener('input', () => {
    updateRowSubtotal(row);
    updateTotalPayment();
  });

  productRowsBody.appendChild(row);
}

function getPriceByRole(option, roleId) {
  switch (parseInt(roleId)) {
    case 1: case 2: return parseFloat(option.getAttribute('data-price-hd')) || 0;
    case 3: return parseFloat(option.getAttribute('data-price-d')) || 0;
    case 4: return parseFloat(option.getAttribute('data-price-a')) || 0;
    case 5: return parseFloat(option.getAttribute('data-price-r')) || 0;
    default: return 0;
  }
}

function updateRowSubtotal(row) {
  const prodSelect = row.querySelector('.product-select');
  const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
  const priceLabel = row.querySelector('.price-label');
  const subtotalLabel = row.querySelector('.subtotal-label');
  const roleId = buyerId.value && buyerId.value !== "0" ? members.find(m=>m.id==buyerId.value)?.role : null;
  const isPrivateSale = buyerId.value === "0";

  if (isPrivateSale) {
    priceLabel.textContent = "-";
    subtotalLabel.textContent = "-";
  } else {
    let price = 0;
    if (prodSelect && prodSelect.value) price = getPriceByRole(prodSelect.options[prodSelect.selectedIndex], roleId);
    priceLabel.textContent = price.toLocaleString();
    subtotalLabel.textContent = (price * qty).toLocaleString();
  }
}

function updateAllRows() {
  document.querySelectorAll('.product-row').forEach(row => updateRowSubtotal(row));
}

function updateTotalPayment() {
  const isPrivateSale = buyerId.value === "0";
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

// Tambah produk
document.getElementById('addProductBtn').addEventListener('click', function (e) {
  e.preventDefault();
  createProductRow();
});

// Radio jenis penjualan
document.querySelectorAll('input[name="sale_type"]').forEach(radio => {
  radio.addEventListener('change', function () {
    if (this.value === "pribadi") {
      customerInput.value = "Penjualan Pribadi";
      buyerManual.value = "Penjualan Pribadi";
      buyerId.value = "0";
      customerInput.disabled = true;
    } else {
      customerInput.value = "";
      buyerManual.value = "";
      buyerId.value = "";
      customerInput.disabled = false;
    }
    updateAllRows();
    updateTotalPayment();
  });
});

// Baris pertama otomatis
createProductRow();

// Submit form
$("#salesForm").submit(function (e) {
    e.preventDefault();

    if (!buyerId.value || (!buyerManual.value && buyerId.value!=="0")) {
        $("#saleErrorArea").show().html("Silakan pilih pembeli dari daftar ATAU ketik nama pembeli secara manual.");
        return;
    }

    let products = [];
    let totalAll = 0;
    let buyerData = members.find(m=>m.id==buyerId.value);
    let isValid = true; 

    $(".product-row").each(function () {
        const prodSelect = $(this).find(".product-select")[0];
        const productId = prodSelect.value;
        const productName = prodSelect.options[prodSelect.selectedIndex].text;
        const qty = parseInt($(this).find(".quantity-input").val()) || 0;
        const price = buyerId.value==="0" ? 0 : getPriceByRole(prodSelect.options[prodSelect.selectedIndex], buyerData.role);
        const subtotal = price * qty;

        if (!productId) { $("#saleErrorArea").show().html("Produk wajib dipilih.");  return false; }
        if (qty<=0) { $("#saleErrorArea").show().html("Jumlah wajib lebih dari 0."); return false; }

        products.push({ product_id: productId, name: productName, price, quantity: qty, subtotal });
        totalAll += subtotal;
    });

    let formData = {
        buyer: buyerManual.value,
        buyerId: buyerId.value,
        buyerCode: buyerId.value==="0"? "-" : buyerData.code,
        products: products,
        total_payment: totalAll
    };

    // HTML konfirmasi
    let confirmHtml = `
      <div style="text-align:left; font-size:14px;">
        <p><b>Pembeli:</b> ${formData.buyer} <br><b>ID Anggota:</b> ${formData.buyerCode}</p>
        <hr style="margin:10px 0;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <thead>
            <tr style="background:#f3f4f6; text-align:left;">
              <th style="padding:6px; border-bottom:1px solid #ddd;">Produk</th>
              <th style="padding:6px; border-bottom:1px solid #ddd;">Qty</th>
              <th style="padding:6px; border-bottom:1px solid #ddd;">Harga</th>
              <th style="padding:6px; border-bottom:1px solid #ddd;">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            ${products.map(p => `
              <tr>
                <td style="padding:6px; border-bottom:1px solid #eee;">${p.name}</td>
                <td style="padding:6px; border-bottom:1px solid #eee;">${p.quantity}</td>
                <td style="padding:6px; border-bottom:1px solid #eee;">Rp ${p.price.toLocaleString()}</td>
                <td style="padding:6px; border-bottom:1px solid #eee;">Rp ${p.subtotal.toLocaleString()}</td>
              </tr>
            `).join("")}
          </tbody>
        </table>
        <hr style="margin:10px 0;">
        <p style="font-size:15px; font-weight:bold; margin-bottom:8px;">Total: Rp ${totalAll.toLocaleString()}</p>
        <p style="color:#b91c1c; font-size:13px; font-weight:600; line-height:1.4;">
          ⚠️ Penjualan yang sudah diproses <u>tidak dapat dibatalkan</u>.<br>
          Pastikan data sudah benar sebelum dilanjutkan.
        </p>
      </div>
    `;

    Swal.fire({
        title: "Konfirmasi Penjualan",
        html: confirmHtml,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Submit",
        cancelButtonText: "Batal",
        width: 600
    }).then((result) => {
      console.log(formData);
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "app/action/add_sell_order.php",
                data: { data: JSON.stringify(formData) },
                success: function(res) {
                    if ($.trim(res) === "yes") {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: "✅ Penjualan berhasil disubmit!",
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => { window.location.href = "index.php?page=sell_order"; });
                    } else {
                        $("#saleErrorArea").show().html(res);
                    }
                },
                error: function() { $("#saleErrorArea").show().html("Terjadi kesalahan saat mengirim data."); }
            });
        }
    });
});
</script>

