<!-- Modal Tambah Anggota -->
<div class="modal fade bd-example-modal-xl suppliarModal" id="suppliarModal">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <!-- Header Modal -->
      <div class="modal-header bg-primary text-white">
        <h4 class="modal-title">Tambah Anggota</h4>
        <button type="button" class="close" data-dismiss="modal" style="font-size: 1.5rem; opacity:1;color: black;">&times;</button>
      </div>

      <!-- Body Modal -->
      <div class="modal-body">
        <form id="adsuppliarForm" class="form-grid">

          <!-- Nama & NIK -->
          <div class="form-group">
            <label for="sup_name">Nama Lengkap *</label>
            <input type="text" class="form-control" id="sup_name" name="sup_name" placeholder="Nama Lengkap" required>
          </div>
          <div class="form-group">
            <label for="sup_nik">NIK (ID Number) *</label>
            <input type="number" class="form-control" id="sup_nik" name="sup_nik" placeholder="Nomor Kependudukan" required pattern="\d{16}" maxlength="16" title="NIK harus 16 digit">
          </div>

          <div class="form-group">
            <label for="sup_nik">NPWP</label>
            <input type="number" class="form-control" id="sup_npwp" name="sup_npwp" placeholder="Nomor NPWP" pattern="\d{15}" maxlength="15" title="NPWP harus 15 digit">
          </div>


          <!-- No Rekening & Bank -->
          <div class="form-group">
            <label for="sup_rekening">No Rekening *</label>
            <input type="number" class="form-control" id="sup_rekening" name="sup_rekening" placeholder="No Rekening" required>
          </div>
          <div class="form-group">
            <label for="sup_bank">Nama Bank *</label>
         <input type="text" id="sup_bank" name="sup_bank" placeholder="Nama Bank">
          </div>

          <div class="form-group">
            <label for="sup_name_bank">Nama Pada Rek Bank *</label>
            <input type="text" class="form-control" id="sup_name_bank" name="sup_name_bank" placeholder="Nama Akun Bank" required>
          </div>

          <!-- Kontak & Tanggal Lahir -->
          <div class="form-group">
            <label for="sup_contact">No Kontak</label>
            <input type="number" class="form-control" id="sup_contact" name="sup_contact" placeholder="No Kontak">
          </div>
          <div class="form-group">
            <label for="birth_date">Tanggal Lahir</label>
            <input type="text" class="form-control" id="birth_date" name="birth_date" placeholder="dd-mm-yyyy" maxlength="10">
          </div>

          <!-- Email & Role -->
          <div class="form-group">
            <label for="sup_email">Email</label>
            <input type="email" class="form-control" id="sup_email" name="sup_email" placeholder="Email">
          </div>
          <!-- Provinsi, Kota & Kecamatan -->
             <div class="form-group">
            <label for="supaddressktp">Alamat KTP</label>
            <textarea id="supaddressktp" name="supaddressktp" placeholder="Alamat sesuai KTP"></textarea>
          </div>
          <div class="form-group">
            <label for="supaddress">Alamat Domisili / Pengiriman</label>
            <textarea id="supaddress" name="supaddress" placeholder="Alamat pengiriman"></textarea>
          </div>

          <div class="form-group">
            <label for="sup_provinsi">Provinsi *</label>
            <select id="sup_provinsi" name="sup_provinsi" required class="form-control">
              <option value="" disabled selected>-- Pilih Provinsi --</option>
            </select>
          </div>
          <div class="form-group">
            <label for="sup_kota">Kota/Kabupaten *</label>
            <select id="sup_kota" name="sup_kota" required class="form-control">
              <option value="" disabled selected>-- Pilih Kota/Kabupaten --</option>
            </select>
          </div>
          <div class="form-group">
            <label for="sup_kecamatan">Kecamatan *</label>
            <select id="sup_kecamatan" name="sup_kecamatan" required class="form-control">
              <option value="" disabled selected>-- Pilih Kecamatan --</option>
            </select>
          </div>

          <!-- Alamat berdampingan -->
          <div class="form-group">
            <label for="sup_role">Level Anggota *</label>
            <select class="form-control" id="sup_role" name="sup_role" required>
              <option value="">-- Pilih Level Anggota --</option>
              <?php if ($_SESSION['role_id'] == 10): ?>
              <option value="10">Super Admin</option>
              <option value="1">Head Office</option>
              <?php endif; ?>
              <option value="2">Head Distributor</option>
              <option value="3">Distributor</option>
              <option value="4">Agen</option>
            </select>
          </div>
          <!-- Tombol -->
          <div class="form-actions">
            <button type="reset" class="btn btn-reset">Reset</button>
            <button type="submit" class="btn btn-submit">Tambah Anggota</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<style>
/* Form grid utama */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

/* Label & input */
.form-group label {
  font-weight: 600;
  margin-bottom: 6px;
}
.form-group input,
.form-group select,
.form-group textarea {
  padding: 10px 14px;
  border-radius: 8px;
  border: 1.5px solid #cbd5e1;
  font-size: 14px;
  outline: none;
  width: 100%;
}
.form-group textarea {
  min-height: 120px;
}

/* Tombol action */
.form-actions {
  grid-column: 1 / -1;
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
}
.btn-reset {
  background-color: #ef4444;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 12px 28px;
  font-weight: 600;
  box-shadow: 0 4px 10px rgba(239,68,68,0.4);
  cursor: pointer;
  transition: background-color 0.3s ease;
}
.btn-reset:hover {
  background-color: #dc2626;
}
.btn-submit {
  background-color: #EEA0A0;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 12px 28px;
  font-weight: 600;
  box-shadow: 0 4px 10px rgba(37,99,235,0.5);
  cursor: pointer;
  transition: background-color 0.3s ease;
}
.btn-submit:hover {
  background-color: #1d4ed8;
}

/* Responsif mobile */
@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  .form-actions {
    flex-direction: column;
    gap: 12px;
  }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const birthInput = document.getElementById('birth_date');

// Auto-format tanggal dd-mm-yyyy saat mengetik
birthInput.addEventListener('input', function() {
    let val = this.value.replace(/\D/g, ''); // hapus non-angka
    if(val.length > 2 && val.length <= 4) {
        val = val.slice(0,2) + '-' + val.slice(2);
    } else if(val.length > 4) {
        val = val.slice(0,2) + '-' + val.slice(2,4) + '-' + val.slice(4,8);
    }
    this.value = val;
});

// Validasi tanggal saat submit
document.getElementById('adsuppliarForm').addEventListener('submit', function(e) {
    const dateInput = birthInput.value.trim();
    const datePattern = /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/;
    if(dateInput && !datePattern.test(dateInput)) {
        e.preventDefault();
        Swal.fire('Error','Format tanggal salah! Gunakan dd-mm-yyyy','error');
    }
});

// Load daftar provinsi
fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
  .then(res => res.json())
  .then(provinces => {
    const provSelect = document.getElementById("sup_provinsi");
    provinces.forEach(prov => {
      const opt = document.createElement("option");
      opt.value = prov.id;
      opt.textContent = prov.name;
      provSelect.appendChild(opt);
    });
  });

// Load kabupaten/kota saat provinsi dipilih
document.getElementById("sup_provinsi").addEventListener("change", function() {
    const provID = this.value;
    const kotaSelect = document.getElementById("sup_kota");
    kotaSelect.innerHTML = "<option value='' disabled selected>-- Pilih Kota/Kabupaten --</option>";
    kotaSelect.disabled = true;
    const kecSelect = document.getElementById("sup_kecamatan");
    kecSelect.innerHTML = "<option value='' disabled selected>-- Pilih Kecamatan --</option>";
    kecSelect.disabled = true;

    if(provID) {
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provID}.json`)
          .then(res => res.json())
          .then(regencies => {
              regencies.forEach(kab => {
                  const opt = document.createElement("option");
                  opt.value = kab.id;
                  opt.textContent = kab.name;
                  kotaSelect.appendChild(opt);
              });
              kotaSelect.disabled = false;
          });
    }
});

// Load kecamatan saat kota dipilih
document.getElementById("sup_kota").addEventListener("change", function() {
    const kotaID = this.value;
    const kecSelect = document.getElementById("sup_kecamatan");
    kecSelect.innerHTML = "<option value='' disabled selected>-- Pilih Kecamatan --</option>";
    kecSelect.disabled = true;

    if(kotaID) {
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kotaID}.json`)
          .then(res => res.json())
          .then(districts => {
              districts.forEach(kec => {
                  const opt = document.createElement("option");
                  opt.value = kec.id;
                  opt.textContent = kec.name;
                  kecSelect.appendChild(opt);
              });
              kecSelect.disabled = false;
          });
    }
});
</script>
