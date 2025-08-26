<div>
  <!-- Form Tambah Anggota -->
  <section class="content">
    <div style="padding:75px;">
      <h2 style="font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 32px; user-select:none;">
        ➕ Tambah Anggota Baru
      </h2>

      <div class="alert alert-danger memberFormError-area" style="display:none; margin-bottom: 20px; font-weight: 600; color: #b91c1c; background-color: #fee2e2; border-radius: 8px; padding: 12px 16px;">
        <span id="memberFormError"></span>
      </div>

      <form id="adsuppliarForm" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
        <div>
          <label for="sup_name" style="font-weight: 600; color: #334155;">Nama Lengkap <span style="color:#ef4444;">*</span></label>
          <input type="text" id="sup_name" name="sup_name" placeholder="Nama lengkap" required style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px; transition: border-color 0.3s;">
        </div>

        <div>
          <label for="sup_nik" style="font-weight: 600; color: #334155;">NIK (ID Number)</label>
          <input type="text" id="sup_nik" name="sup_nik" placeholder="Nomor Kependudukan" style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="sup_rekening" style="font-weight: 600; color: #334155;">No Rekening</label>
          <input type="number" id="sup_rekening" name="sup_rekening" placeholder="No Rekening" style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="sup_bank" style="font-weight: 600; color: #334155;">Nama Bank</label>
          <input type="text" id="sup_bank" name="sup_bank" placeholder="Nama Bank" style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="sup_contact" style="font-weight: 600; color: #334155;">No Kontak</label>
          <input type="text" id="sup_contact" name="sup_contact" placeholder="Nomor kontak" style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="birth_date" style="font-weight: 600; color: #334155;">Tanggal Lahir</label>
          <input type="date" id="birth_date" name="birth_date" style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="sup_email" style="font-weight: 600; color: #334155;">Email</label>
          <input type="email" id="sup_email" name="sup_email" placeholder="Email (optional)" style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="sup_role" style="font-weight: 600; color: #334155;">Level Anggota <span style="color:#ef4444;">*</span></label>
          <select id="sup_role" name="sup_role" required style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px; background-color: white; cursor: pointer;">
            <option value="" disabled selected>-- Pilih Level Anggota --</option>
            <option value="2">Head Distributor</option>
            <option value="3">Distributor</option>
            <option value="4">Agen</option>
            <option value="5">Reseller</option>
          </select>
        </div>
         <div>
          <label for="sup_provinsi" style="font-weight: 600; color: #334155;">Provinsi <span style="color:#ef4444;">*</span></label>
          <select id="sup_provinsi" name="sup_provinsi" required style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px; background-color: white; cursor: pointer;">
            <option value="" disabled selected>-- Pilih Provinsi --</option>
            <option value="Aceh">Aceh</option>
            <option value="Sumatera Utara">Sumatera Utara</option>
            <option value="Sumatera Barat">Sumatera Barat</option>
            <option value="Riau">Riau</option>
            <option value="Kepulauan Riau">Kepulauan Riau</option>
            <option value="Jambi">Jambi</option>
            <option value="Sumatera Selatan">Sumatera Selatan</option>
            <option value="Bangka Belitung">Bangka Belitung</option>
            <option value="Bengkulu">Bengkulu</option>
            <option value="Lampung">Lampung</option>
            <option value="DKI Jakarta">DKI Jakarta</option>
            <option value="Jawa Barat">Jawa Barat</option>
            <option value="Banten">Banten</option>
            <option value="Jawa Tengah">Jawa Tengah</option>
            <option value="DI Yogyakarta">DI Yogyakarta</option>
            <option value="Jawa Timur">Jawa Timur</option>
            <option value="Bali">Bali</option>
            <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
            <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
            <option value="Kalimantan Barat">Kalimantan Barat</option>
            <option value="Kalimantan Tengah">Kalimantan Tengah</option>
            <option value="Kalimantan Selatan">Kalimantan Selatan</option>
            <option value="Kalimantan Timur">Kalimantan Timur</option>
            <option value="Kalimantan Utara">Kalimantan Utara</option>
            <option value="Sulawesi Utara">Sulawesi Utara</option>
            <option value="Sulawesi Tengah">Sulawesi Tengah</option>
            <option value="Sulawesi Selatan">Sulawesi Selatan</option>
            <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
            <option value="Gorontalo">Gorontalo</option>
            <option value="Sulawesi Barat">Sulawesi Barat</option>
            <option value="Maluku">Maluku</option>
            <option value="Maluku Utara">Maluku Utara</option>
            <option value="Papua">Papua</option>
            <option value="Papua Barat">Papua Barat</option>
            <option value="Papua Barat Daya">Papua Barat Daya</option>
            <option value="Papua Tengah">Papua Tengah</option>
            <option value="Papua Pegunungan">Papua Pegunungan</option>
            <option value="Papua Selatan">Papua Selatan</option>
          </select>
        </div>

        <div style="grid-column: 1 / -1;">
          <label for="supaddressktp" style="font-weight: 600; color: #334155;">Alamat (Diisi Sesuai KTP)</label>
          <textarea id="supaddressktp" name="supaddressktp" rows="3" placeholder="Alamat sesuai KTP" style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;"></textarea>
        </div>

        <div style="grid-column: 1 / -1;">
          <label for="supaddress" style="font-weight: 600; color: #334155;">Alamat Pengiriman</label>
          <textarea id="supaddress" name="supaddress" rows="3" placeholder="Alamat sesuai tempat domisili" style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;"></textarea>
        </div>

        <div style="grid-column: 1 / -1; display: flex; justify-content: center; gap: 20px; margin-top: 30px;">
          <button type="reset" style="
            background-color: #ef4444;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 12px rgb(239 68 68 / 0.4);
            cursor: pointer;
            transition: background-color 0.3s ease;">
            Reset
          </button>

          <button type="submit" style="
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 12px rgb(37 99 235 / 0.5);
            cursor: pointer;
            transition: background-color 0.3s ease;">
            Submit Anggota
          </button>
        </div>
      </form>
    </div>
  </section>
</div>
