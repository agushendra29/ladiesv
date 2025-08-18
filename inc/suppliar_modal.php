<!-- The Modal for add new form -->
<div class="modal fade bd-example-modal-xl suppliarModal" id="suppliarModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary">
        <h4 class="modal-title">Tambah Anggota</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="alert alert-primary alert-dismissible fade show memberFormError" role="alert" style="display:none;">
          <span id="cuppliarFormError"></span>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form id="adsuppliarForm">
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
                <label for="sup_rekening">No Rekening (Rekening) *</label>
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
                <label for="sup_bank">Nama Akun Bank *</label>
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
                <input type="email" class="form-control" id="sup_email" placeholder="Email optional" name="sup_email" required>
              </div>
            </div>

            <div class="col-md-6">
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
                  <option value="5">Reseller</option>
                </select>
              </div>
            </div>

            <div class="col-md-12 col-lg-12">
              <div class="form-group">
                <label for="supaddressktp">Alamat (Diisi Sesuai KTP):</label>
                <textarea rows="3" class="form-control" placeholder="Alamat diisi sesuai ktp" id="supaddressktp"
                  name="supaddressktp"></textarea>
              </div>
            </div>

            <div class="col-md-12 col-lg-12">
              <div class="form-group">
                <label for="supaddress">Alamat Pengiriman:</label>
                <textarea rows="3" class="form-control" placeholder="Alamat diisi sesuai tempat domisili" id="supaddress"
                  name="supaddress"></textarea>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block rounded-0">Tambah Anggota</button>
        </form>
      </div>
    </div>
  </div>
</div>

