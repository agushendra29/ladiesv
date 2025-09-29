<!-- Content Wrapper -->
<div style="margin-bottom:75px;margin-top:52px;">
  <section class="content">
    <div class="container-fluid section-card-body">

<?php 
if (isset($_GET['edit_id'])) {
  $edit_id = $_GET['edit_id'];
  $stmt = $pdo->prepare("
    SELECT s.*
    FROM suppliar s
    LEFT JOIN user u ON s.id = u.suppliar_id
    WHERE s.id = ?
  ");
  $stmt->execute([$edit_id]);
  $data = $stmt->fetch(PDO::FETCH_OBJ);

  if ($data) {
    // referral code
    $referralCode = null;
    $parentName   = null;
    $refStmt = $pdo->prepare("
        SELECT p.suppliar_code AS code, p.name AS name
        FROM suppliar s
        LEFT JOIN suppliar p ON p.id = s.parent_id
        WHERE s.id = ?
        LIMIT 1
    ");
    $refStmt->execute([$edit_id]);
    $refRow = $refStmt->fetch(PDO::FETCH_ASSOC);
    if ($refRow && $refRow['code']) {
        $referralCode = $refRow['code'];
        $parentName   = $refRow['name'];
    }
?>

    <div class="section-title mb-4">
      ✏️ Edit Anggota: <?=htmlspecialchars($data->suppliar_code)?>
    </div>

    <div class="alert alert-danger memberFormError-area"
         style="display:none;margin-bottom:20px;font-weight:600;color:#b91c1c;
                background-color:#fee2e2;border-radius:8px;padding:12px 16px;">
      <span id="memberFormError"></span>
    </div>

    <form id="editSuppliarForm" method="POST"
          style="max-width:1000px;margin:auto;">

      <input type="hidden" name="id" value="<?= htmlspecialchars($edit_id); ?>">

      <!-- === Data Pribadi === -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
          <h5 class="mb-0 fw-bold">🧍‍♂️ Data Pribadi</h5>
        </div>
        <div class="card-body row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Nama Lengkap *</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($data->name); ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">NIK *</label>
            <input type="text" name="nik" class="form-control"
                   value="<?= htmlspecialchars($data->nik); ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Tanggal Lahir</label>
            <input type="text" name="date_of_birth" id="date_of_birth"
                   class="form-control"
                   value="<?= $data->date_of_birth ? date('d-m-Y', strtotime($data->date_of_birth)) : '' ?>"
                   placeholder="dd-mm-yyyy">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">NPWP</label>
            <input type="text" name="npwp" class="form-control"
                   value="<?= htmlspecialchars($data->npwp); ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">No. HP / WA *</label>
            <input type="text" name="contact" class="form-control"
                   value="<?= htmlspecialchars($data->con_num); ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($data->email); ?>">
          </div>
        </div>
      </div>

      <!-- === Alamat === -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
          <h5 class="mb-0 fw-bold">🏠 Alamat</h5>
        </div>
        <div class="card-body row">
          <div class="col-md-12 mb-3">
            <label class="form-label fw-bold">Alamat KTP *</label>
            <textarea name="address_ktp" class="form-control" rows="3" required><?= htmlspecialchars($data->address_ktp); ?></textarea>
          </div>
          <div class="col-md-12 mb-3">
            <label class="form-label fw-bold">Alamat Domisili / Pengiriman *</label>
            <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($data->address); ?></textarea>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label fw-bold">Provinsi *</label>
            <select id="provinsi" name="provinsi" class="form-control" required>
              <option value="">-- Pilih Provinsi --</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label fw-bold">Kota/Kabupaten *</label>
            <select id="kota" name="kota" class="form-control" required>
              <option value="">-- Pilih Kota / Kabupaten --</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label fw-bold">Kecamatan *</label>
            <select id="kecamatan" name="kecamatan" class="form-control" required>
              <option value="">-- Pilih Kecamatan --</option>
            </select>
          </div>
        </div>
      </div>

      <!-- === Pendaftaran === -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
          <h5 class="mb-0 fw-bold">📝 Pendaftaran</h5>
        </div>
        <div class="card-body row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Pendaftaran *</label>
            <select id="role" name="role" class="form-control" <?= ($_SESSION['role_id'] != 10) ? 'disabled' : 'required' ?>>
              <option value="" disabled <?= empty($data->role_id) ? 'selected' : '' ?>>-- Pilih Pendaftaran --</option>
              <option value="10" <?= $data->role_id == 10 ? 'selected' : '' ?>>Super Admin</option>
              <option value="1" <?= $data->role_id == 1 ? 'selected' : '' ?>>Head Officer</option>
              <option value="2" <?= $data->role_id == 2 ? 'selected' : '' ?>>Head Distributor</option>
              <option value="3" <?= $data->role_id == 3 ? 'selected' : '' ?>>Distributor</option>
              <option value="4" <?= $data->role_id == 4 ? 'selected' : '' ?>>Agen</option>
              <option value="5" <?= $data->role_id == 5 ? 'selected' : '' ?>>Reseller</option>
            </select>
            <?php if ($_SESSION['role_id'] != 10): ?>
              <input type="hidden" name="role" value="<?= $data->role_id ?>">
            <?php endif; ?>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">User ID Referral</label>
            <input type="text" class="form-control" readonly
                   value="<?= htmlspecialchars($referralCode ?: 'Tidak ada referral') . ($parentName ? ' - '.htmlspecialchars($parentName) : '') ?>">
          </div>
        </div>
      </div>

      <!-- === Informasi Bank === -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
          <h5 class="mb-0 fw-bold">🏦 Informasi Bank</h5>
        </div>
        <div class="card-body row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Nama Bank *</label>
            <input type="text" name="bank" class="form-control"
                   value="<?= htmlspecialchars($data->bank); ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Nama pada Rek Bank *</label>
            <input type="text" name="sup_name_bank" class="form-control"
                   value="<?= htmlspecialchars($data->nama_rekening); ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">No. Rekening *</label>
            <input type="number" name="rekening" class="form-control"
                   value="<?= htmlspecialchars($data->rekening); ?>" required>
          </div>
        </div>
      </div>

      <!-- === Password === -->
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
          <h5 class="mb-0 fw-bold">🔑 Password Baru</h5>
        </div>
        <div class="card-body row">
          <div class="col-md-6 mb-3 position-relative">
            <label class="form-label fw-bold">Password Baru</label>
            <input type="password" id="password" name="password" class="form-control">
          </div>
          <div class="col-md-6 mb-3 position-relative">
            <label class="form-label fw-bold">Konfirmasi Password Baru</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
          </div>
        </div>
      </div>

      <!-- Tombol -->
      <div class="text-center mt-4">
        <button type="reset" class="btn btn-danger px-4 me-2" style="border-radius:8px;font-weight:600;">
          Reset
        </button>
        <button type="submit" class="btn btn-primary px-4"
                style="border-radius:8px;font-weight:600;background-color:#EB4F7D;border:none;">
          Update Data
        </button>
      </div>

    </form>

<?php
  } else { header("location:index.php?page=error_page"); exit; }
} else { header("location:index.php?page=error_page"); exit; }
?>
    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const bi = document.getElementById('date_of_birth');
bi.addEventListener('input', function() {
  let v=this.value.replace(/\D/g,'');
  if(v.length>2&&v.length<=4){v=v.slice(0,2)+'-'+v.slice(2);}
  else if(v.length>4){v=v.slice(0,2)+'-'+v.slice(2,4)+'-'+v.slice(4,8);}
  this.value=v;
});

document.addEventListener("DOMContentLoaded", function () {
  const provinsiSelect = document.getElementById("provinsi");
  const kotaSelect = document.getElementById("kota");
  const kecamatanSelect = document.getElementById("kecamatan");

  const selectedProvinsi = "<?= htmlspecialchars($data->provinsi ?? '') ?>";
  const selectedKota = "<?= htmlspecialchars($data->kota ?? '') ?>";
  const selectedKecamatan = "<?= htmlspecialchars($data->kecamatan ?? '') ?>";

  fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
    .then(res => res.json())
    .then(data => {
      data.forEach(prov => {
        let option = document.createElement("option");
        option.value = prov.id;
        option.textContent = prov.name;
        if (prov.id == selectedProvinsi) option.selected = true;
        provinsiSelect.appendChild(option);
      });
      if(selectedProvinsi) loadKota(selectedProvinsi, selectedKota);
    });

  provinsiSelect.addEventListener("change", function(){
    loadKota(this.value, null);
  });

  kotaSelect.addEventListener("change", function(){
    loadKecamatan(this.value, selectedKecamatan);
  });

  function loadKota(provinsiId, selected=null){
    kotaSelect.innerHTML = '<option value="">-- Pilih Kota / Kabupaten --</option>';
    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    if(!provinsiId) return;
    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinsiId}.json`)
      .then(res => res.json())
      .then(data => {
        data.forEach(kota => {
          let option = document.createElement("option");
          option.value = kota.id;
          option.textContent = kota.name;
          if(kota.id == selected) option.selected = true;
          kotaSelect.appendChild(option);
        });
        if(selected) loadKecamatan(selected, selectedKecamatan);
      });
  }

  function loadKecamatan(kotaId, selected=null){
    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    if(!kotaId) return;
    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kotaId}.json`)
      .then(res => res.json())
      .then(data => {
        data.forEach(kec => {
          let option = document.createElement("option");
          option.value = kec.id;
          option.textContent = kec.name;
          if(kec.id == selected) option.selected = true;
          kecamatanSelect.appendChild(option);
        });
      });
  }
});
</script>
