<?php
// Ambil produk dari database
$products = $obj->all('products');
?>
<div class="mb-5">
  <section class="content">
    <div class="mt-5">

      <!-- Accordion Produk -->
      <div id="accordionHeaderProduk" style="cursor:pointer; display:flex; justify-content:space-between; background:#eee; padding:10px;">
        <strong>Pilih Produk</strong> <span id="accordionIconProduk">−</span>
      </div>
      <div id="accordionBodyProduk" style="display:block; padding:10px;">
        <form id="resellerSalesForm">
          <div id="productRows"></div>
          <div style="margin-top:10px;">
            <button type="button" class="btn btn-secondary" id="addProductRowBtn">Tambah Produk</button>
          </div>
          <div class="mt-3">
            <label>Total Pembayaran</label>
            <input type="number" id="total_payment" class="form-control" readonly>
          </div>
          <div style="margin-top:15px;">
            <button type="submit" class="btn btn-primary">Next</button>
          </div>
        </form>
      </div>

      <!-- Accordion Reseller -->
      <div id="accordionHeaderSupplier" style="cursor:pointer; display:flex; justify-content:space-between; background:#eee; padding:10px; margin-top:15px;">
        <strong>Data Reseller</strong> <span id="accordionIconSupplier">+</span>
      </div>
      <div id="accordionBodySupplier" style="display:none; padding:10px;">
        <div id="resellerFormWrapper" style="opacity:0.5; pointer-events:none;">
          <form id="resellerRegisterForm">
            <div class="form-group">
              <label>Nama Reseller</label>
              <input type="text" id="sup_name" class="form-control" required>
            </div>
            <div class="form-group">
              <label>NIK</label>
              <input type="text" id="sup_nik" class="form-control">
            </div>
            <div class="form-group">
              <label>No Rekening</label>
              <input type="text" id="sup_rekening" class="form-control">
            </div>
            <div class="form-group">
              <label>Bank</label>
              <input type="text" id="sup_bank" class="form-control">
            </div>
            <div class="form-group">
              <label>No HP</label>
              <input type="text" id="sup_contact" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" id="sup_email" class="form-control">
            </div>
            <div class="form-group">
              <label>Role</label>
              <select id="sup_role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <option value="reseller">Reseller</option>
                <option value="distributor">Distributor</option>
              </select>
            </div>
            <div class="form-group">
              <label>Alamat Domisili</label>
              <textarea id="supaddress" class="form-control"></textarea>
            </div>
            <div class="form-group">
              <label>Alamat KTP</label>
              <textarea id="supaddressktp" class="form-control"></textarea>
            </div>
            <div class="form-group">
              <label>Tanggal Lahir</label>
              <input type="date" id="birth_date" class="form-control">
            </div>
            <div class="mt-3">
              <button type="button" class="btn btn-secondary" id="prevToProduk">Previous</button>
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
          </form>
          <div id="saleErrorArea" style="margin-top:10px; display:none;"></div>
        </div>
      </div>

    </div>
  </section>
</div>

<script>
const productsData = <?php echo json_encode($products); ?>;
const totalPaymentInput = document.getElementById('total_payment');
const productRowsContainer = document.getElementById('productRows');
const resellerFormWrapper = document.getElementById('resellerFormWrapper');
const accordionHeaderProduk = document.getElementById('accordionHeaderProduk');
const accordionIconProduk = document.getElementById('accordionIconProduk');
const saleErrorArea = document.getElementById('saleErrorArea');

// Accordion toggle
accordionHeaderProduk.addEventListener('click', function(){
  const body = document.getElementById('accordionBodyProduk');
  body.style.display = (body.style.display === 'none' || body.style.display === '') ? 'block' : 'none';
  accordionIconProduk.textContent = (body.style.display === 'block') ? '−' : '+';
});
document.getElementById('accordionHeaderSupplier').addEventListener('click', function(){
  const body = document.getElementById('accordionBodySupplier');
  const icon = document.getElementById('accordionIconSupplier');
  body.style.display = (body.style.display === 'none' || body.style.display === '') ? 'block' : 'none';
  icon.textContent = (body.style.display === 'block') ? '−' : '+';
});

function updateTotalPayment() {
  let total = 0;
  document.querySelectorAll('.product-row').forEach(row => {
    const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
    const price = parseFloat(row.querySelector('.product-select').selectedOptions[0]?.dataset.priceA || 0);
    total += qty * price;
  });
  totalPaymentInput.value = total;
}

function createProductRow() {
  const row = document.createElement('div');
  row.classList.add('row', 'gx-4', 'gy-3', 'product-row');
  row.style.marginBottom = '15px';

  let options = '<option disabled selected>Pilih produk</option>';
  productsData.forEach(p => {
    options += `<option value="${p.id}" data-price-a="${p.sell_price_a}">${p.product_name}</option>`;
  });

  row.innerHTML = `
    <div class="col-md-6">
      <select name="product_id[]" class="product-select" required style="width:100%; padding:12px; border-radius:10px; border:1px solid #ccc;">
        ${options}
      </select>
    </div>
    <div class="col-md-4">
      <input type="number" name="quantity[]" class="quantity-input" min="1" required style="width:100%; padding:12px; border-radius:10px; border:1px solid #ccc;">
    </div>
    <div class="col-md-2" style="display:flex;align-items:end;">
      <button type="button" class="btn btn-danger removeProductBtn">Hapus</button>
    </div>
  `;
  productRowsContainer.appendChild(row);

  row.querySelector('.quantity-input').addEventListener('input', updateTotalPayment);
  row.querySelector('.product-select').addEventListener('change', updateTotalPayment);
  row.querySelector('.removeProductBtn').addEventListener('click', () => {
    row.remove();
    updateTotalPayment();
  });
}

// Tambah produk
document.getElementById('addProductRowBtn').addEventListener('click', createProductRow);

// NEXT button
$('#resellerSalesForm').on('submit', function(e){
  e.preventDefault();
  resellerFormWrapper.style.opacity = '1';
  resellerFormWrapper.style.pointerEvents = 'auto';
  document.getElementById('accordionBodySupplier').style.display = 'block';
  document.getElementById('accordionIconSupplier').textContent = '−';
  accordionHeaderProduk.style.pointerEvents = 'none';
  accordionHeaderProduk.style.opacity = '0.6';
  document.getElementById('accordionBodyProduk').style.display = 'none';
  accordionIconProduk.textContent = '+';
});

// Previous button
$('#prevToProduk').on('click', function(){
  accordionHeaderProduk.style.pointerEvents = 'auto';
  accordionHeaderProduk.style.opacity = '1';
  document.getElementById('accordionBodyProduk').style.display = 'block';
  accordionIconProduk.textContent = '−';
  resellerFormWrapper.style.opacity = '0.5';
  resellerFormWrapper.style.pointerEvents = 'none';
});

async function submitResellerAndSale() {
  saleErrorArea.style.display = 'none';

  const products = [];
  let errorMsg = "";
  document.querySelectorAll('.product-row').forEach(row => {
    const productId = row.querySelector('[name="product_id[]"]').value;
    const qty = parseInt(row.querySelector('[name="quantity[]"]').value) || 0;
    if (!productId) errorMsg = "Produk wajib dipilih.";
    if (qty <= 0) errorMsg = "Jumlah wajib lebih dari 0.";
    products.push({ product_id: productId, quantity: qty });
  });

  if(errorMsg) {
    saleErrorArea.style.display = 'block';
    saleErrorArea.style.color = '#b91c1c';
    saleErrorArea.innerText = errorMsg;
    return;
  }

  const resellerData = {
    sup_name: $('#sup_name').val().trim(),
    sup_nik: $('#sup_nik').val().trim(),
    sup_rekening: $('#sup_rekening').val().trim(),
    sup_bank: $('#sup_bank').val().trim(),
    sup_contact: $('#sup_contact').val().trim(),
    sup_email: $('#sup_email').val().trim(),
    sup_role: $('#sup_role').val(),
    supaddress: $('#supaddress').val().trim(),
    supaddressktp: $('#supaddressktp').val().trim(),
    birth_date: $('#birth_date').val().trim()
  };

  if (!resellerData.sup_name || !resellerData.sup_contact || !resellerData.sup_role) {
    saleErrorArea.style.display = "block";
    saleErrorArea.style.color = "#b91c1c";
    saleErrorArea.innerText = "Silakan lengkapi data reseller terlebih dahulu.";
    return;
  }

  try {
    const res1 = await fetch('app/action/add_suppliar.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(resellerData)
    });

    const textRes1 = (await res1.text()).trim();
    if (!textRes1.startsWith("yes")) throw new Error(textRes1 || "Gagal menambahkan reseller.");

    const parts = textRes1.split("|");
    const resellerId = parts[1];
    const resellerName = parts[2] || resellerData.sup_name;

    const formSale = {
      buyer: resellerId,
      buyerName: resellerName,
      products: products,
      total_payment: parseFloat(totalPaymentInput.value)
    };

    const res2 = await fetch('app/action/add_sell_order.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formSale)
    });

    const textRes2 = (await res2.text()).trim();
    if(textRes2 !== "yes") throw new Error(textRes2 || "Gagal menambahkan penjualan.");

    saleErrorArea.style.display = "block";
    saleErrorArea.style.color = "#16a34a";
    saleErrorArea.innerText = "✅ Reseller dan penjualan berhasil ditambahkan.";

    $('#resellerRegisterForm')[0].reset();
    $('#resellerSalesForm')[0].reset();
    productRowsContainer.innerHTML = '';
    createProductRow();
    $('#prevToProduk').click();

  } catch (err) {
    saleErrorArea.style.display = "block";
    saleErrorArea.style.color = "#b91c1c";
    saleErrorArea.innerText = "❌ Terjadi kesalahan: " + err.message;
    console.error(err);
  }
}

// Delegated submit agar pasti terpanggil
$(document).on('submit', '#resellerRegisterForm', function(e){
  e.preventDefault();
  submitResellerAndSale();
});

// Initial row
createProductRow();
</script>
