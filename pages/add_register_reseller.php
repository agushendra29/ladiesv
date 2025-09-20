<?php
$distributor_id = $_SESSION['distributor_id'] ?? 0;
$products       = $obj->all('products');
$role_id = $_SESSION['role_id'];

/* --- fungsi stok --- */
function getStockProduct($pid) {
     global $obj, $distributor_id;
    $usingStock = $_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10 ? 1 : $distributor_id;
 
    $stockData = $obj->allCondition(
        'distributor_stocks',
        "product_id = ? AND suppliar_id = ?",
        [$pid, $usingStock]
    );
    return !empty($stockData) ? $stockData[0]->stock : 0;
}

$productsData = [];
foreach ($products as $p) {
    $p->pStock = getStockProduct($p->id);
    $productsData[] = $p;
}
?>
<div class="p-3 container-fluid">
  <section class="content">
    <div class="mt-5 mb-5">
      <div class="section-title">Form Register Reseller</div>

      <div id="formErrorArea" style="display:none;"></div>

      <form id="combinedForm" method="post" action="your_submit_action.php">
        <div class="section-card-body mt-2">
          <div style="font-size:18px;font-weight:500;">Data Produk</div>
          <a id="addProductBtn">+ Tambah Produk</a>

          <div id="productRows">
            <table>
              <thead>
                <tr>
                  <th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th><th>Aksi</th>
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
        </div>

        <hr>
        <div class="section-card-body">
          <div style="font-size:18px;font-weight:500;" class="mb-3">Data Reseller</div>

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
            <div class="form-col">
              <label for="kode_referal">User ID Referal</label>
              <div style="display:flex;gap:8px;align-items:center;">
                <input type="text" id="kode_referal" name="kode_referal" style="flex:1;">
                <button type="button" id="btnCheckReferal" class="btn-custom">Cek Referal</button>
              </div>
              <!-- pesan validasi di sini -->
              <small id="kode_referal_msg" style="color:red;display:block;margin-top:4px;"></small>
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
        </div>

        <div class="form-actions">
          <button type="reset" class="btn-reset">Reset</button>
          <button type="submit" class="btn-submit">Submit Penjualan & Register</button>
        </div>
      </form>
    </div>
  </section>
</div>

<style>
body,input,select,textarea,button{font-size:14px;color:#374151;line-height:1.5;margin:0;padding:0}
label{font-size:14px;font-weight:500;margin-bottom:8px;display:block}
input,select,textarea{width:100%;padding:14px 16px;border:1px solid #d1d5db;border-radius:12px;font-size:14px;box-sizing:border-box;margin-bottom:16px}
textarea{resize:vertical}
a#addProductBtn{background:#EB4F7D;color:#fff;border-radius:10px;padding:10px 18px;display:inline-block;margin:12px 0;cursor:pointer;text-decoration:none;font-weight:500}
#productRows table{width:100%;border-collapse:collapse;margin-top:12px;font-size:14px}
#productRows thead{background:#EEA0A0;color:#fff;font-size:13px}
#productRows th,#productRows td{padding:12px;border-bottom:1px solid #e5e7eb;text-align:center}
.form-actions{text-align:center;margin-top:36px}
.btn-reset{background:#ef4444;color:#fff;padding:14px 28px;border:none;border-radius:12px;margin-right:14px;cursor:pointer;font-weight:500}
.btn-submit{background:#ef4444;color:#fff;padding:14px 28px;border:none;border-radius:12px;cursor:pointer;font-weight:500}
.form-row{display:flex;flex-wrap:wrap;gap:16px;margin-bottom:0}
.form-col{flex:1 1 48%;min-width:200px}
.form-col-full{flex:1 1 100%}
@media (max-width:768px){
  .form-row{flex-direction:column;gap:12px}
  .form-col,.form-col-full{flex:1 1 100%}
  #productRows table,#productRows thead,#productRows tbody,#productRows th,#productRows td,#productRows tr{display:block;width:100%}
  #productRows thead{display:none}
  #productRows tr{margin-bottom:18px;border:1px solid #e5e7eb;border-radius:12px;padding:14px;background:#f9fafb}
  #productRows td{text-align:left;padding:10px 8px;border:none;display:flex;justify-content:space-between;flex-wrap:wrap}
  #productRows td::before{content:attr(data-label);font-weight:600;color:#374151;margin-bottom:4px}
  .quantity-input{width:90px!important}
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const productsData = <?php echo json_encode($productsData); ?>;
const totalPaymentInput = document.getElementById('total_payment');
const productRowsBody  = document.getElementById('productRowsBody');

function createProductRow(){
  const row=document.createElement('tr');row.classList.add('product-row');
  let options='<option disabled selected>Pilih produk</option>';
  productsData.forEach(p=>{
     options+=`<option value="${p.id}" data-price="${p.sell_price_r}">${p.product_name} - Stok: ${p.pStock}</option>`;
  });
  row.innerHTML=`
    <td data-label="Produk"><select name="product_id[]" class="product-select" required>${options}</select></td>
    <td data-label="Qty"><input type="number" name="quantity[]" class="quantity-input" min="1" value="1" required></td>
    <td data-label="Harga" style="text-align:right;"><span class="price-label" data-raw="0">0</span></td>
    <td data-label="Subtotal" style="text-align:right;"><span class="subtotal-label" data-raw="0">0</span></td>
    <td data-label="Aksi" style="text-align:center;"><button type="button" class="remove-row" style="background:#ef4444;color:white;border:none;border-radius:6px;padding:6px 10px;cursor:pointer;">Hapus</button></td>`;
  row.querySelector('.remove-row').addEventListener('click',()=>{row.remove();updateTotalPayment();});
  row.querySelector('.product-select').addEventListener('change',()=>updateRowSubtotal(row));
  row.querySelector('.quantity-input').addEventListener('input',()=>updateRowSubtotal(row));
  productRowsBody.appendChild(row);
}
function updateRowSubtotal(row){
  const prodSelect=row.querySelector('.product-select');
  const qty=parseInt(row.querySelector('.quantity-input').value)||0;
  const priceLabel=row.querySelector('.price-label');
  const subtotalLabel=row.querySelector('.subtotal-label');
  let price=0;
  if(prodSelect&&prodSelect.value){
     price=parseFloat(prodSelect.options[prodSelect.selectedIndex].dataset.price)||0;
  }
  const subtotal=price*qty;
  priceLabel.dataset.raw=price;
  subtotalLabel.dataset.raw=subtotal;
  priceLabel.textContent=price.toLocaleString('id-ID');
  subtotalLabel.textContent=subtotal.toLocaleString('id-ID');
  updateTotalPayment();
}
function updateTotalPayment(){
  let total=0;
  document.querySelectorAll('.product-row .subtotal-label').forEach(l=>{total+=parseFloat(l.dataset.raw)||0;});
  totalPaymentInput.value=total.toLocaleString('id-ID');
}
document.getElementById('addProductBtn').addEventListener('click',e=>{e.preventDefault();createProductRow();});
createProductRow();

/* --- format tanggal --- */
document.getElementById('dob').addEventListener('input',function(){
  let v=this.value.replace(/\D/g,'');
  if(v.length>2&&v.length<=4){v=v.slice(0,2)+'-'+v.slice(2);}
  else if(v.length>4){v=v.slice(0,2)+'-'+v.slice(2,4)+'-'+v.slice(4,8);}
  this.value=v;
});

/* --- lokasi --- */
const prov=document.getElementById("provinsi"),
      kota=document.getElementById("kota"),
      kec=document.getElementById("kecamatan");
fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
 .then(r=>r.json()).then(provinsi=>{
    prov.innerHTML="<option value='' disabled selected>-- Pilih Provinsi --</option>";
    provinsi.forEach(p=>{let o=document.createElement("option");o.value=p.id;o.textContent=p.name;prov.appendChild(o);});
 });
prov.addEventListener("change",function(){
   kota.innerHTML="<option value='' disabled selected>-- Pilih Kota/Kabupaten --</option>";
   kec.innerHTML="<option value='' disabled selected>-- Pilih Kecamatan --</option>";
   if(this.value){
     fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.value}.json`)
       .then(r=>r.json()).then(data=>{
          data.forEach(k=>{let o=document.createElement("option");o.value=k.id;o.textContent=k.name;kota.appendChild(o);});
       });
   }
});
kota.addEventListener("change",function(){
   kec.innerHTML="<option value='' disabled selected>-- Pilih Kecamatan --</option>";
   if(this.value){
     fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.value}.json`)
       .then(r=>r.json()).then(data=>{
          data.forEach(k=>{let o=document.createElement("option");o.value=k.id;o.textContent=k.name;kec.appendChild(o);});
       });
   }
});

/* --- cek referal --- */
let referalValid=false;
$('#btnCheckReferal').on('click',function(){
   const kode=$('#kode_referal').val().trim();
   const msg=$('#kode_referal_msg');
   msg.text(''); // reset pesan
   if(!kode){
     msg.text('Silakan isi kode referal terlebih dahulu.');
     referalValid=false;
     return;
   }
   $.getJSON('app/action/check_referal.php',{kode:kode})
     .done(function(res){
        if(res.ok){
          referalValid=true;
          msg.css('color','green').text('Kode valid. Pemilik: '+(res.data?.name||''));
        }else{
          referalValid=false;
          msg.css('color','red').text(res.message);
        }
     })
     .fail(function(){
        referalValid=false;
        msg.css('color','red').text('Terjadi kesalahan saat mengecek kode.');
     });
});
$('#combinedForm').on('submit',function(e){
   const kode=$('#kode_referal').val().trim();
   if(kode && !referalValid){
       e.preventDefault();
       $('#kode_referal_msg').css('color','red').text('Klik "Cek Referal" terlebih dahulu.');
   }
});
</script>
