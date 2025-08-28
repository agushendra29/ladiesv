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
      <h2 class="mb-4" style="font-size: 20px; font-weight: 600; color: #333;">📦 Form Register Reseller</h2>

      <div id="formErrorArea"
        style="display:none; color: #b91c1c; margin-bottom: 16px; background-color: #fee2e2; padding: 12px; border-radius: 8px;">
      </div>

      <form id="combinedForm">
        <h4>🛒 Data Produk</h4>
        <a id="addProductBtn" style="background-color: #0073ea; color: #fff; border-radius: 12px; padding: 8px 20px;
          font-weight: 600; font-size: 12px; border: none; box-shadow: 0 4px 12px rgba(0,115,234,0.4);
          display: inline-flex; align-items: center; gap: 8px; cursor: pointer; text-decoration:none;">
          + Tambah Produk
        </a>

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

        <hr style="margin: 40px 0;">

        <h4>📝 Data Reseller</h4>
        <div class="row">
          <div class="col-md-6 col-lg-6">
            <div class="form-group">
              <label for="sup_name">Nama Lengkap *:</label>
              <input type="text" class="form-control" id="sup_name" placeholder="Nama Lengkap" name="sup_name" required>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="sup_nik">NIK (ID Number) *</label>
              <input type="number" class="form-control" id="sup_nik" name="sup_nik" placeholder="Nomor Kependudukan" 
                required pattern="\d{16}" maxlength="16"
                title="NIK harus berupa angka 16 digit">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="sup_rekening">No Rekening *</label>
              <input type="number" class="form-control" id="sup_rekening" name="sup_rekening"
                placeholder="No Rekening" required pattern="\d+" title="No Rekening harus berupa angka">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="sup_bank">Nama Bank *</label>
              <select class="form-control" id="sup_bank" name="sup_bank" required>
                <option value="">-- Pilih Bank --</option>
                <option value="BCA">BCA</option>
                <option value="BRI">BRI</option>
                <option value="BNI">BNI</option>
                <option value="MANDIRI">MANDIRI</option>
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="sup_name_bank">Nama Akun Bank *</label>
             <input type="text" class="form-control" id="sup_name_bank" placeholder="Nama Akun Bank" name="sup_name_bank" required>
            </div>
          </div>

          <div class="col-md-6 col-lg-6">
            <div class="form-group">
              <label for="sup_contact">No Kontak :</label>
              <input type="number" class="form-control" id="sup_contact" placeholder="Contact member" name="sup_contact">
            </div>
          </div>

          <div class="col-md-6 col-lg-6">
            <div class="form-group">
              <label for="birth_date">Tanggal Lahir :</label>
              <input type="date" class="form-control" id="birth_date" name="birth_date" required>
            </div>
          </div>

          <div class="col-md-6 col-lg-6">
            <div class="form-group">
              <label for="sup_email">Email:</label>
              <input type="email" class="form-control" id="sup_email" placeholder="Email" name="sup_email" required>
            </div>
          </div>

          <div class="col-md-6 col-lg-6">
            <div class="form-group">
              <label for="role">Level Pendaftaran:</label>
              <input type="text" class="form-control" id="role" placeholder="Role" name="role" disabled value="Reseller">
            </div>
          </div>

          <div class="col-md-12 col-lg-12">
            <div class="form-group">
              <label for="supaddressktp">Alamat KTP:</label>
              <textarea rows="3" class="form-control" placeholder="Alamat diisi sesuai KTP" id="supaddressktp"
                name="supaddressktp"></textarea>
            </div>
          </div>

          <div class="col-md-12 col-lg-12">
            <div class="form-group">
              <label for="supaddress">Alamat Pengiriman:</label>
              <textarea rows="3" class="form-control" placeholder="Alamat diisi sesuai domisili" id="supaddress"
                name="supaddress"></textarea>
            </div>
          </div>

          <div class="col-md-12 col-lg-12 mb-4">
            <label for="sup_provinsi" style="font-weight: 600; color: #334155;">Provinsi <span
                style="color:#ef4444;">*</span></label>
            <select id="provinsi" name="provinsi" required
              style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px; background-color: white; cursor: pointer;">
              <option value="" disabled>-- Pilih Provinsi --</option>
            </select>
          </div>

          <div class="col-md-12 col-lg-12 mb-4">
            <label for="sup_kota" style="font-weight: 600; color: #334155;">Kota/Kabupaten <span
                style="color:#ef4444;">*</span></label>
            <select id="kota" name="kota" required
              style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px; background-color: white; cursor: pointer;">
              <option value="" disabled selected>-- Pilih Kota/Kabupaten --</option>
            </select>
          </div>
        </div>

        <!-- Tombol Submit Gabungan -->
        <div style="text-align: center; margin-top: 32px;">
          <button type="reset"
            style="background-color: #ef4444; color: white; padding: 12px 24px; border: none; border-radius: 10px; margin-right: 12px; cursor: pointer; font-weight: 500;">
            Reset
          </button>
          <button type="submit"
            style="background-color: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 10px; cursor: pointer; font-weight: 500;">
            Submit Penjualan & Register
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

  const subtotal = price * qty;

  // Simpan angka mentah di data-attribute
  priceLabel.dataset.raw = price;
  subtotalLabel.dataset.raw = subtotal;

  // Tampilkan format rupiah
  priceLabel.textContent = price.toLocaleString('id-ID');
  subtotalLabel.textContent = subtotal.toLocaleString('id-ID');

  updateTotalPayment();
}

function updateTotalPayment() {
  let total = 0;
  document.querySelectorAll('.product-row .subtotal-label').forEach(label => {
    total += parseFloat(label.dataset.raw) || 0;
  });
  totalPaymentInput.value = total.toLocaleString('id-ID');
}

document.getElementById('addProductBtn').addEventListener('click', function (e) {
  e.preventDefault();
  createProductRow();
});

// Buat baris pertama otomatis
createProductRow();
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const provSelect = document.getElementById("provinsi");
  const kotaSelect = document.getElementById("kota");

  // Load daftar provinsi
  fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
    .then(res => res.json())
    .then(provinces => {
      provSelect.innerHTML = "<option value='' disabled selected>-- Pilih Provinsi --</option>";
      provinces.forEach(prov => {
        let opt = document.createElement("option");
        opt.value = prov.id;
        opt.textContent = prov.name;
        provSelect.appendChild(opt);
      });
    })
    .catch(err => console.error("Gagal load provinsi:", err));

  // Event pilih provinsi → load kabupaten/kota
  provSelect.addEventListener("change", function() {
    let provID = this.value;
    kotaSelect.innerHTML = "<option value='' disabled selected>-- Pilih Kota/Kabupaten --</option>";
    kotaSelect.disabled = true;

    if (provID) {
      fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provID}.json`)
        .then(res => res.json())
        .then(regencies => {
          regencies.forEach(kab => {
            let opt = document.createElement("option");
            opt.value = kab.id;
            opt.textContent = kab.name;
            kotaSelect.appendChild(opt);
          });
          kotaSelect.disabled = false;
        })
        .catch(err => console.error("Gagal load kota:", err));
    }
  });
});
</script>
