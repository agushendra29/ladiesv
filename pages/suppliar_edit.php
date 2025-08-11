<!-- Content Wrapper. Contains page content  -->
<div class="content-wrapper">
  <section class="content">
    <div style="padding:75px; max-width: 900px; margin:auto;">
      <?php 
        if (isset($_GET['edit_id'])) {
          $edit_id = $_GET['edit_id'];
          $stmt = $pdo->prepare("
            SELECT s.*, u.role_id
            FROM suppliar s
            LEFT JOIN user u ON s.id = u.suppliar_id
            WHERE s.id = ?
          ");
          $stmt->execute([$edit_id]);
          $data = $stmt->fetch(PDO::FETCH_OBJ);

          if ($data) {
      ?>

      <h2 style="font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 32px; user-select:none;">
        ✏️ Edit Anggota: <?=htmlspecialchars($data->suppliar_code)?>
      </h2>

      <div class="alert alert-danger memberFormError-area"
        style="display:none; margin-bottom: 20px; font-weight: 600; color: #b91c1c; background-color: #fee2e2; border-radius: 8px; padding: 12px 16px;">
        <span id="memberFormError"></span>
      </div>

      <form id="editSuppliarForm"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
        <input type="hidden" name="id" value="<?= htmlspecialchars($edit_id); ?>">

        <div>
          <label for="name" style="font-weight: 600; color: #334155;">Nama Lengkap <span
              style="color:#ef4444;">*</span></label>
          <input type="text" id="name" name="name" value="<?= htmlspecialchars($data->name); ?>" required
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="nik" style="font-weight: 600; color: #334155;">NIK (ID Number) <span
              style="color:#ef4444;">*</span></label>
          <input type="text" id="nik" name="nik" value="<?= htmlspecialchars($data->nik); ?>" required
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="rekening" style="font-weight: 600; color: #334155;">No Rekening <span
              style="color:#ef4444;">*</span></label>
          <input type="number" id="rekening" name="rekening" value="<?= htmlspecialchars($data->rekening); ?>" required
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="bank" style="font-weight: 600; color: #334155;">Nama Bank <span
              style="color:#ef4444;">*</span></label>
          <input type="text" id="bank" name="bank" value="<?= htmlspecialchars($data->bank); ?>" required
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="contact" style="font-weight: 600; color: #334155;">No Kontak <span
              style="color:#ef4444;">*</span></label>
          <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($data->con_num); ?>" required
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="date_of_birth" style="font-weight: 600; color: #334155;">Tanggal Lahir</label>
          <input type="date" id="date_of_birth" name="date_of_birth"
            value="<?= htmlspecialchars($data->date_of_birth); ?>"
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="email" style="font-weight: 600; color: #334155;">Email</label>
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($data->email); ?>"
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="role" style="font-weight: 600; color: #334155;">Level Anggota <span
              style="color:#ef4444;">*</span></label>
          <select id="role" name="role" required
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px; background-color: white; cursor: pointer;">
            <option value="" disabled>-- Pilih Level Anggota --</option>
            <option value="1" <?= $data->role_id == 1 ? 'selected' : '' ?>>Head Officer</option>
            <option value="2" <?= $data->role_id == 2 ? 'selected' : '' ?>>Head Distributor</option>
            <option value="3" <?= $data->role_id == 3 ? 'selected' : '' ?>>Distributor</option>
            <option value="4" <?= $data->role_id == 4 ? 'selected' : '' ?>>Agen</option>
            <option value="5" <?= $data->role_id == 5 ? 'selected' : '' ?>>Reseller</option>
          </select>
        </div>

        <div style="grid-column: 1 / -1;">
          <label for="address_ktp" style="font-weight: 600; color: #334155;">Alamat (Diisi Sesuai KTP) <span
              style="color:#ef4444;">*</span></label>
          <textarea id="address_ktp" name="address_ktp" rows="3" required
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;"><?= htmlspecialchars($data->address_ktp); ?></textarea>
        </div>

        <div style="grid-column: 1 / -1;">
          <label for="address" style="font-weight: 600; color: #334155;">Alamat Pengiriman <span
              style="color:#ef4444;">*</span></label>
          <textarea id="address" name="address" rows="3" required
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;"><?= htmlspecialchars($data->address); ?></textarea>
        </div>

        <div>
          <label for="password" style="font-weight: 600; color: #334155;">Password Baru</label>
          <input type="password" id="password" name="password"
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="old_password" style="font-weight: 600; color: #334155;">Password Lama</label>
          <input type="password" id="old_password" name="old_password"
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
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
            Update Data
          </button>
        </div>
      </form>

      <?php
          } else {
            header("location:index.php?page=error_page");
            exit;
          }
        } else {
          header("location:index.php?page=error_page");
          exit;
        }
      ?>
    </div>
  </section>
</div>