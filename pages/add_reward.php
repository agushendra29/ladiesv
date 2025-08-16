<div style="margin-bottom:75px; background-color: #f6f7fb;">
  <section class="content">
    <div class="container-fluid" style="margin-top: 50px;">
      <div>
        <h2 style="margin-bottom: 24px; font-size: 20px; font-weight: 600; color: #333;">🎁 Form Tambah Reward</h2>

        <!-- Error Message -->
        <div id="rewardErrorArea" style="
          display: none;
          padding: 10px 15px;
          border-radius: 8px;
          margin-bottom: 20px;
          font-weight: 500;
          border: 1px solid transparent;
        ">
          <span id="rewardErrorMessage"></span>
        </div>

        <form id="addReward">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="nama_reward" style="font-weight: 500;">Nama Reward *</label>
              <input type="text" class="form-control" id="nama_reward" name="nama_reward" placeholder="Nama reward"
                style="border-radius: 8px;" required>
            </div>
             <div class="col-md-6 mb-3">
              <label for="role_id" style="font-weight: 500;">Role *</label>
              <select name="role_id" id="role_id" class="form-control" required style="border-radius: 8px;">
                  <option value="" disabled selected>-- Pilih Level Anggota --</option>
            <option value="2">Head Distributor</option>
            <option value="3">Distributor</option>
            <option value="4">Agen</option>
            <option value="5">Reseller</option>
              </select>
            </div>
          </div>
          <div class="row">
  <div class="col-md-6 mb-3">
    <label for="periode_hadiah_dari" style="font-weight: 500;">Periode Hadiah Dari *</label>
    <input type="date" class="form-control" id="periode_hadiah_dari" name="periode_hadiah_dari" style="border-radius: 8px;" required>
  </div>
  <div class="col-md-6 mb-3">
    <label for="periode_hadiah_sampai" style="font-weight: 500;">Periode Hadiah Sampai *</label>
    <input type="date" class="form-control" id="periode_hadiah_sampai" name="periode_hadiah_sampai" style="border-radius: 8px;" required>
  </div>
</div>


          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="jumlah_point" style="font-weight: 500;">Jumlah Point *</label>
              <input type="number" class="form-control" id="jumlah_point" name="jumlah_point" placeholder="Jumlah point"
                style="border-radius: 8px;" required min="0" step="1">
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-6 offset-md-3 text-center">
              <button type="reset" style="
                background-color: #f44336;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 10px 20px;
                font-weight: 600;
                font-size: 14px;
                margin-right: 10px;
                transition: background-color 0.3s ease;"
                onmouseover="this.style.backgroundColor='#d32f2f'"
                onmouseout="this.style.backgroundColor='#f44336'">
                Reset
              </button>

              <button type="submit" style="
                background-color: #0073ea;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 10px 20px;
                font-weight: 600;
                font-size: 14px;
                transition: background-color 0.3s ease;"
                onmouseover="this.style.backgroundColor='#005bb5'"
                onmouseout="this.style.backgroundColor='#0073ea'">
                Simpan Reward
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
