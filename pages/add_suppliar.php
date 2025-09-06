<div>
  <!-- Form Tambah Anggota -->
  <section class="content">
    <div class="member-form-container">
      <h2>➕ Tambah Anggota Baru</h2>

      <div class="memberFormError-area" style="display:none;">
        <span id="memberFormError"></span>
      </div>

      <form id="adsuppliarForm">
        <div class="form-group">
          <label for="sup_name">Nama Lengkap <span class="required">*</span></label>
          <input type="text" id="sup_name" name="sup_name" placeholder="Nama lengkap" required>
        </div>

        <div class="form-group">
          <label for="sup_nik">NIK (ID Number)</label>
          <input type="text" id="sup_nik" name="sup_nik" placeholder="Nomor Kependudukan">
        </div>

        <div class="form-group">
          <label for="sup_rekening">No Rekening</label>
          <input type="number" id="sup_rekening" name="sup_rekening" placeholder="No Rekening">
        </div>

        <div class="form-group">
          <label for="sup_bank">Nama Bank</label>
          <input type="text" id="sup_bank" name="sup_bank" placeholder="Nama Bank">
        </div>

        <div class="form-group">
          <label for="sup_contact">No Kontak</label>
          <input type="text" id="sup_contact" name="sup_contact" placeholder="Nomor kontak">
        </div>

        <div class="form-group">
          <label for="birth_date">Tanggal Lahir</label>
          <input type="text" id="birth_date" name="birth_date">
        </div>

        <div class="form-group">
          <label for="sup_email">Email</label>
          <input type="email" id="sup_email" name="sup_email" placeholder="Email (optional)">
        </div>

        <div class="form-group">
          <label for="sup_role">Level Anggota <span class="required">*</span></label>
          <select id="sup_role" name="sup_role" required>
            <option value="" disabled selected>-- Pilih Level Anggota --</option>
            <option value="2">Head Distributor</option>
            <option value="3">Distributor</option>
            <option value="4">Agen</option>
            <option value="5">Reseller</option>
          </select>
        </div>

        <div class="form-group">
          <label for="sup_provinsi">Provinsi <span class="required">*</span></label>
          <select id="sup_provinsi" name="sup_provinsi" required>
            <option value="" disabled selected>-- Pilih Provinsi --</option>
            <option value="Aceh">Aceh</option>
            <option value="Sumatera Utara">Sumatera Utara</option>
            <option value="Sumatera Barat">Sumatera Barat</option>
            <option value="Riau">Riau</option>
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
          </select>
        </div>

        <div class="form-group full-width">
          <label for="supaddressktp">Alamat (Diisi Sesuai KTP)</label>
          <textarea id="supaddressktp" name="supaddressktp" rows="3" placeholder="Alamat sesuai KTP"></textarea>
        </div>

        <div class="form-group full-width">
          <label for="supaddress">Alamat Pengiriman</label>
          <textarea id="supaddress" name="supaddress" rows="3" placeholder="Alamat sesuai tempat domisili"></textarea>
        </div>

        <div class="form-actions full-width">
          <button type="reset" class="btn-reset">Reset</button>
          <button type="submit" class="btn-submit">Submit Anggota</button>
        </div>
      </form>
    </div>
  </section>

  <style>
    .member-form-container {
      padding: 40px 24px;
      max-width: 900px;
      margin: 0 auto;
    }

    .member-form-container h2 {
      font-size: 28px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 32px;
      user-select: none;
    }

    .memberFormError-area {
      display: none;
      margin-bottom: 20px;
      font-weight: 600;
      color: #b91c1c;
      background-color: #fee2e2;
      border-radius: 8px;
      padding: 12px 16px;
    }

    form#adsuppliarForm {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 24px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      font-weight: 600;
      color: #334155;
      margin-bottom: 6px;
    }

    .form-group .required {
      color: #ef4444;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px 16px;
      border: 1.8px solid #cbd5e1;
      border-radius: 12px;
      font-size: 16px;
      transition: border-color 0.3s;
      outline: none;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: #2563eb;
    }

    .form-group.full-width {
      grid-column: 1 / -1;
    }

    .form-actions {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
      grid-column: 1 / -1;
      flex-wrap: wrap;
    }

    .btn-reset {
      background-color: #ef4444;
      color: white;
      border: none;
      border-radius: 12px;
      padding: 14px 32px;
      font-weight: 700;
      font-size: 16px;
      box-shadow: 0 4px 12px rgb(239 68 68 / 0.4);
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn-reset:hover {
      background-color: #b91c1c;
    }

    .btn-submit {
      background-color: #2563eb;
      color: white;
      border: none;
      border-radius: 12px;
      padding: 14px 32px;
      font-weight: 700;
      font-size: 16px;
      box-shadow: 0 4px 12px rgb(37 99 235 / 0.5);
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
      background-color: #1d4ed8;
    }

    /* Responsif */
    @media (max-width: 768px) {
      .member-form-container {
        padding: 24px 16px;
      }
      form#adsuppliarForm {
        grid-template-columns: 1fr;
        gap: 16px;
      }
      .form-actions {
        flex-direction: column;
        gap: 12px;
      }
    }
  </style>
</div>
