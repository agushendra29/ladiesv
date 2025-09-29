<div style="margin-bottom:75px;margin-top:52px;">
  <section class="content">
    <div class="container-fluid section-card-body">
      <div class="section-title mb-4">
        Form Tambah Anggota
      </div>

      <!-- Pesan error/sukses -->
      <div id="suppliarErrorArea"
           style="display:none;padding:10px 15px;border-radius:8px;margin-bottom:20px;
                  font-weight:500;border:1px solid transparent;">
        <span id="suppliarErrorMessage"></span>
      </div>

      <form id="adsuppliarForm" method="POST" action="add_suppliar.php">

        <!-- === Data Pribadi === -->
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-light">
            <h5 class="mb-0" style="font-weight:600;">🧍‍♂️ Data Pribadi</h5>
          </div>
          <div class="card-body row">
            <div class="col-md-6 mb-3">
              <label for="sup_name" class="form-label fw-bold">Nama Lengkap *</label>
              <input type="text" class="form-control" id="sup_name" name="sup_name" placeholder="Nama Lengkap" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="sup_nik" class="form-label fw-bold">NIK *</label>
              <input type="number" class="form-control" id="sup_nik" name="sup_nik"
                     placeholder="16 digit NIK" required pattern="\d{16}" maxlength="16">
            </div>
            <div class="col-md-6 mb-3">
              <label for="birth_date" class="form-label fw-bold">Tanggal Lahir</label>
              <input type="text" class="form-control" id="birth_date" name="birth_date"
                     placeholder="dd-mm-yyyy" maxlength="10">
            </div>
            <div class="col-md-6 mb-3">
              <label for="sup_npwp" class="form-label fw-bold">NPWP</label>
              <input type="number" class="form-control" id="sup_npwp" name="sup_npwp"
                     placeholder="15 digit NPWP" pattern="\d{15}" maxlength="15">
            </div>
            <div class="col-md-6 mb-3">
              <label for="sup_contact" class="form-label fw-bold">No. HP / WA</label>
              <input type="number" class="form-control" id="sup_contact" name="sup_contact" placeholder="No. Kontak">
            </div>
            <div class="col-md-6 mb-3">
              <label for="sup_email" class="form-label fw-bold">Email</label>
              <input type="email" class="form-control" id="sup_email" name="sup_email" placeholder="Alamat Email">
            </div>
          </div>
        </div>

        <!-- === Alamat === -->
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-light">
            <h5 class="mb-0" style="font-weight:600;">🏠 Alamat</h5>
          </div>
          <div class="card-body row">
            <div class="col-md-12 mb-3">
              <label for="supaddressktp" class="form-label fw-bold">Alamat KTP</label>
              <textarea id="supaddressktp" name="supaddressktp" class="form-control"
                        placeholder="Alamat sesuai KTP" style="min-height:100px;"></textarea>
            </div>
            <div class="col-md-12 mb-3">
              <label for="supaddress" class="form-label fw-bold">Alamat Domisili / Pengiriman</label>
              <textarea id="supaddress" name="supaddress" class="form-control"
                        placeholder="Alamat pengiriman" style="min-height:100px;"></textarea>
            </div>
            <div class="col-md-4 mb-3">
              <label for="sup_provinsi" class="form-label fw-bold">Provinsi *</label>
              <select id="sup_provinsi" name="sup_provinsi" class="form-control" required>
                <option value="" disabled selected>-- Pilih Provinsi --</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="sup_kota" class="form-label fw-bold">Kota/Kabupaten *</label>
              <select id="sup_kota" name="sup_kota" class="form-control" required>
                <option value="" disabled selected>-- Pilih Kota/Kabupaten --</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="sup_kecamatan" class="form-label fw-bold">Kecamatan *</label>
              <select id="sup_kecamatan" name="sup_kecamatan" class="form-control" required>
                <option value="" disabled selected>-- Pilih Kecamatan --</option>
              </select>
            </div>
          </div>
        </div>

        <!-- === Pendaftaran === -->
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-light">
            <h5 class="mb-0" style="font-weight:600;">📝 Pendaftaran</h5>
          </div>
          <div class="card-body">
            <div class="col-md-6 mb-3">
              <label for="sup_role" class="form-label fw-bold">Pendaftaran *</label>
              <select class="form-control" id="sup_role" name="sup_role" required>
                <option value="">-- Pilih Pendaftaran --</option>
                <?php if ($_SESSION['role_id'] == 10): ?>
                <option value="10">Super Admin</option>
                <option value="1">Head Office</option>
                <?php endif; ?>
                <option value="2">Head Distributor</option>
                <option value="3">Distributor</option>
                <option value="4">Agen</option>
              </select>
            </div>
          </div>
        </div>

        <!-- === Informasi Bank === -->
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-light">
            <h5 class="mb-0" style="font-weight:600;">🏦 Informasi Bank</h5>
          </div>
          <div class="card-body row">
            <div class="col-md-6 mb-3">
              <label for="sup_bank" class="form-label fw-bold">Nama Bank *</label>
              <input type="text" class="form-control" id="sup_bank" name="sup_bank"
                     placeholder="Nama Bank" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="sup_name_bank" class="form-label fw-bold">Nama pada Rek Bank *</label>
              <input type="text" class="form-control" id="sup_name_bank" name="sup_name_bank"
                     placeholder="Nama Pemilik Rekening" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="sup_rekening" class="form-label fw-bold">No. Rekening *</label>
              <input type="number" class="form-control" id="sup_rekening" name="sup_rekening"
                     placeholder="Nomor Rekening" required>
            </div>
          </div>
        </div>

        <!-- Tombol -->
        <div class="text-center mt-4">
          <button type="reset" class="btn btn-danger px-4 me-2"
                  style="border-radius:8px;font-weight:600;">Reset</button>
          <button type="submit" class="btn btn-primary px-4"
                  style="border-radius:8px;font-weight:600;background-color:#EB4F7D;border:none;">
            Tambah Anggota
          </button>
        </div>
      </form>
    </div>
  </section>
</div>

<!-- Validasi & load data wilayah -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const birthInput=document.getElementById('birth_date');
birthInput.addEventListener('input',function(){
  let v=this.value.replace(/\D/g,'');
  if(v.length>2&&v.length<=4){v=v.slice(0,2)+'-'+v.slice(2);}
  else if(v.length>4){v=v.slice(0,2)+'-'+v.slice(2,4)+'-'+v.slice(4,8);}
  this.value=v;
});
document.getElementById('adsuppliarForm').addEventListener('submit',function(e){
  const d=birthInput.value.trim();
  const p=/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/;
  if(d && !p.test(d)){e.preventDefault();Swal.fire('Error','Format tanggal salah! (dd-mm-yyyy)','error');}
});
fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
  .then(r=>r.json()).then(prov=>{
    const s=document.getElementById("sup_provinsi");
    prov.forEach(p=>{
      const o=document.createElement("option");
      o.value=p.id;o.textContent=p.name;s.appendChild(o);
    });
});
document.getElementById("sup_provinsi").addEventListener("change",function(){
  const id=this.value,k=document.getElementById("sup_kota"),c=document.getElementById("sup_kecamatan");
  k.innerHTML="<option value='' disabled selected>-- Pilih Kota/Kabupaten --</option>";k.disabled=true;
  c.innerHTML="<option value='' disabled selected>-- Pilih Kecamatan --</option>";c.disabled=true;
  if(id){
    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${id}.json`)
      .then(r=>r.json()).then(rg=>{
        rg.forEach(a=>{const o=document.createElement("option");o.value=a.id;o.textContent=a.name;k.appendChild(o);});
        k.disabled=false;
    });
  }
});
document.getElementById("sup_kota").addEventListener("change",function(){
  const id=this.value,c=document.getElementById("sup_kecamatan");
  c.innerHTML="<option value='' disabled selected>-- Pilih Kecamatan --</option>";c.disabled=true;
  if(id){
    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${id}.json`)
      .then(r=>r.json()).then(dc=>{
        dc.forEach(d=>{const o=document.createElement("option");o.value=d.id;o.textContent=d.name;c.appendChild(o);});
        c.disabled=false;
    });
  }
});
</script>
