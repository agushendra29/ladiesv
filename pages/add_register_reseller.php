<?php 
$distributor_id = $_SESSION['distributor_id'];
$products = $obj->all('products');

function getStockProduct($pid) {
    global $obj, $distributor_id; 
    $stockData = $obj->allCondition(
        'distributor_stocks', 
        "product_id = ? AND suppliar_id = ?", 
        [$pid, 1]
    );
    if (!empty($stockData)) {
        return $stockData[0]->stock; 
    }
    return 0; 
}
?>

<div>
  <section class="content">
    <div class="mt-5 mb-5">
      <h2>📦 Form Register Reseller</h2>

      <div id="formErrorArea" style="display:none;"></div>

      <form id="combinedForm">

        <!-- Data Produk -->
        <h4>🛒 Data Produk</h4>
        <a id="addProductBtn">+ Tambah Produk</a>

        <div id="productRows">
          <table>
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

        <div class="form-row mt-3">
          <div class="form-col-full">
            <label for="total_payment">Total Pembayaran</label>
            <input type="text" id="total_payment" name="total_payment" readonly placeholder="Otomatis terisi">
          </div>
        </div>

        <hr>

        <!-- Data Reseller -->
        <h4>📝 Data Reseller</h4>

        <div class="form-row">
          <div class="form-col">
            <label for="sup_name">Nama Lengkap *</label>
            <input type="text" id="sup_name" name="sup_name" required>
          </div>
          <div class="form-col">
            <label for="sup_nik">NIK *</label>
            <input type="number" id="sup_nik" name="sup_nik" required pattern="\d{16}" maxlength="16">
          </div>
        </div>

        <div class="form-row">
          <div class="form-col">
            <label for="sup_rekening">No Rekening *</label>
            <input type="number" id="sup_rekening" name="sup_rekening" required>
          </div>
          <div class="form-col">
            <label for="sup_bank">Nama Bank *</label>
            <input type="text" id="sup_bank" name="sup_bank" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-col">
            <label for="sup_name_bank">Nama pada Rek Bank *</label>
            <input type="text" id="sup_name_bank" name="sup_name_bank" required>
          </div>
          <div class="form-col">
            <label for="sup_contact">No Kontak</label>
            <input type="number" id="sup_contact" name="sup_contact">
          </div>
        </div>

        <div class="form-row">
          <div class="form-col">
            <label for="birth_date">Tanggal Lahir *</label>
            <input type="text" id="dob" name="dob" required placeholder="dd-mm-yyyy">
          </div>
          <div class="form-col">
            <label for="sup_email">Email *</label>
            <input type="email" id="sup_email" name="sup_email" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-col">
            <label for="role">Level Pendaftaran</label>
            <input type="text" id="role" name="role" value="Reseller" disabled>
          </div>
        </div>

        <div class="form-row">
          <div class="form-col-full">
            <label for="supaddressktp">Alamat KTP</label>
            <textarea id="supaddressktp" name="supaddressktp" rows="3"></textarea>
          </div>
        </div>

        <div class="form-row">
          <div class="form-col-full">
            <label for="supaddress">Alamat Domisili</label>
            <textarea id="supaddress" name="supaddress" rows="3"></textarea>
          </div>
        </div>

        <div class="form-row">
          <div class="form-col">
            <label for="provinsi">Provinsi *</label>
            <select id="provinsi" name="provinsi" required>
              <option value="">-- Pilih Provinsi --</option>
            </select>
          </div>
          <div class="form-col">
            <label for="kota">Kota/Kabupaten *</label>
            <select id="kota" name="kota" required>
              <option value="">-- Pilih Kota/Kabupaten --</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-col">
            <label for="kecamatan">Kecamatan *</label>
            <select id="kecamatan" name="kecamatan" required>
              <option value="">-- Pilih Kecamatan --</option>
            </select>
          </div>
        </div>

        <div class="form-actions">
          <button type="reset" class="btn-reset">Reset</button>
          <button type="submit" class="btn-submit">Submit Penjualan & Register</button>
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

<!-- Styles -->
<style>
body, input, select, textarea, button { font-family: 'Segoe UI', Roboto, Arial, sans-serif; font-size: 14px; color: #374151; line-height: 1.5; margin: 0; padding: 0; }
h2 { font-size: 20px; font-weight: 600; color: #1f2937; margin-bottom:20px; }
h4 { font-size: 16px; font-weight: 600; margin-top:25px; margin-bottom:10px; }
label { font-size: 14px; font-weight: 500; margin-bottom: 8px; display:block; }
input, select, textarea { width: 100%; padding: 14px 16px; border: 1px solid #d1d5db; border-radius: 12px; font-size: 14px; box-sizing: border-box; margin-bottom: 16px; }
textarea { resize: vertical; }
#formErrorArea { color: #b91c1c; margin-bottom: 20px; background-color: #fee2e2; padding: 14px; border-radius: 10px; }
a#addProductBtn { background: #EB4F7D; color:#fff; border-radius:10px; padding:10px 18px; display:inline-block; margin:12px 0; cursor:pointer; text-decoration:none; font-weight:500; }
a#addProductBtn:hover { background:#005bb5; }

#productRows table { width: 100%; border-collapse: collapse; margin-top:12px; font-size:14px; }
#productRows thead { background:#EEA0A0; color:#fff; font-size:13px; }
#productRows th, #productRows td { padding:12px; border-bottom:1px solid #e5e7eb; text-align:center; }
#productRows td { background:#fff; }

.form-actions { text-align:center; margin-top:36px; }
.btn-reset { background:#ef4444; color:#fff; padding:14px 28px; border:none; border-radius:12px; margin-right:14px; cursor:pointer; font-weight:500; }
.btn-reset:hover { background:#dc2626; }
.btn-submit { background:#ef4444; color:#fff; padding:14px 28px; border:none; border-radius:12px; cursor:pointer; font-weight:500; }
.btn-submit:hover { background:#EEA0A0; }

.form-row { display: flex; flex-wrap: wrap; gap:16px; margin-bottom:0; }
.form-col { flex: 1 1 48%; min-width: 200px; }
.form-col-full { flex: 1 1 100%; }

@media (max-width:768px) {
  .form-row { flex-direction: column; gap:12px; }
  .form-col, .form-col-full { flex: 1 1 100%; }

  #productRows table, #productRows thead, #productRows tbody, #productRows th, #productRows td, #productRows tr { display:block; width:100%; }
  #productRows thead { display:none; }
  #productRows tr { margin-bottom:18px; border:1px solid #e5e7eb; border-radius:12px; padding:14px; background:#f9fafb; }
  #productRows td { text-align:left; padding:10px 8px; border:none; display:flex; justify-content:space-between; flex-wrap:wrap; }
  #productRows td::before { content: attr(data-label); font-weight:600; color:#374151; margin-bottom:4px; }
  .quantity-input { width:90px !important; }
}

</style>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    <td data-label="Produk"><select name="product_id[]" class="product-select" required>${options}</select></td>
    <td data-label="Qty"><input type="number" name="quantity[]" class="quantity-input" min="1" value="1" required></td>
    <td data-label="Harga" style="text-align:right;"><span class="price-label">0</span></td>
    <td data-label="Subtotal" style="text-align:right;"><span class="subtotal-label">0</span></td>
    <td data-label="Aksi" style="text-align:center;"><button type="button" class="remove-row" style="background:#ef4444;color:white;border:none;border-radius:6px;padding:6px 10px;cursor:pointer;">Hapus</button></td>
  `;

  row.querySelector('.remove-row').addEventListener('click', () => { row.remove(); updateTotalPayment(); });
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
  const subtotal = price * qty;

  priceLabel.dataset.raw = price;
  subtotalLabel.dataset.raw = subtotal;

  priceLabel.textContent = price.toLocaleString('id-ID');
  subtotalLabel.textContent = subtotal.toLocaleString('id-ID');

  updateTotalPayment();
}

function updateTotalPayment() {
  let total = 0;
  document.querySelectorAll('.product-row .subtotal-label').forEach(label => { total += parseFloat(label.dataset.raw) || 0; });
  totalPaymentInput.value = total.toLocaleString('id-ID');
}

document.getElementById('addProductBtn').addEventListener('click', function (e) { e.preventDefault(); createProductRow(); });
createProductRow();
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const binput = document.getElementById('dob');
  binput.addEventListener('input', function() {
    let val = this.value.replace(/\D/g, ''); // hapus non-angka
    if(val.length > 2 && val.length <= 4) {
        val = val.slice(0,2) + '-' + val.slice(2);
    } else if(val.length > 4) {
        val = val.slice(0,2) + '-' + val.slice(2,4) + '-' + val.slice(4,8);
    }
    this.value = val;
});

  const provSelect = document.getElementById("provinsi");
  const kotaSelect = document.getElementById("kota");
  const kecSelect = document.getElementById("kecamatan");

  fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
    .then(res => res.json())
    .then(provinces => {
      provSelect.innerHTML = "<option value='' disabled selected>-- Pilih Provinsi --</option>";
      provinces.forEach(prov => { let opt = document.createElement("option"); opt.value = prov.id; opt.textContent = prov.name; provSelect.appendChild(opt); });
    });

  provSelect.addEventListener("change", function() {
    let provID = this.value;
    kotaSelect.innerHTML = "<option value='' disabled selected>-- Pilih Kota/Kabupaten --</option>";
    kecSelect.innerHTML = "<option value='' disabled selected>-- Pilih Kecamatan --</option>";
    kotaSelect.disabled = true; kecSelect.disabled = true;

    if (provID) {
      fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provID}.json`)
        .then(res => res.json())
        .then(regencies => {
          regencies.forEach(kab => { let opt = document.createElement("option"); opt.value = kab.id; opt.textContent = kab.name; kotaSelect.appendChild(opt); });
          kotaSelect.disabled = false;
        });
    }
  });

  kotaSelect.addEventListener("change", function() {
    let kotaID = this.value;
    kecSelect.innerHTML = "<option value='' disabled selected>-- Pilih Kecamatan --</option>";
    kecSelect.disabled = true;

    if (kotaID) {
      fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kotaID}.json`)
        .then(res => res.json())
        .then(kecs => {
          kecs.forEach(kec => { let opt = document.createElement("option"); opt.value = kec.id; opt.textContent = kec.name; kecSelect.appendChild(opt); });
          kecSelect.disabled = false;
        });
    }
  });
});
</script>
