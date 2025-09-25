<!-- Content Wrapper -->
<div class="mb-5 mt-5">
  <section class="content">
    <div style="background:white; padding: 20px; margin: auto; border-radius:5px;">
      <?php 
       $login_user = $_SESSION['distributor_id'];
       $user_info = $obj->find('user','id',$login_user);
       if ($user_info) {
         $edit_id = $_SESSION['distributor_id'];
         $stmt = $pdo->prepare("
            SELECT s.*, u.role_id 
            FROM suppliar s
            LEFT JOIN user u ON s.id = u.suppliar_id
            WHERE s.id = ?
          ");
         $stmt->execute([$edit_id]);
         $data = $stmt->fetch(PDO::FETCH_OBJ);

         if ($data) {

          // -------- fungsi ambil nama wilayah dari API --------
          function getWilayahName($url, $id) {
            $json = @file_get_contents($url);
            if ($json) {
              $list = json_decode($json, true);
              foreach ($list as $item) {
                if ($item['id'] == $id) {
                  return $item['name'];
                }
              }
            }
            return null;
          }

         function getReferralCode(PDO $pdo, int $suppliarId): string
{
    $sql = "
        SELECT p.suppliar_code AS code, p.name AS name
        FROM suppliar s
        LEFT JOIN suppliar p ON p.id = s.parent_id
        WHERE s.id = ?
        LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$suppliarId]);

    // ambil seluruh row sebagai array asosiatif
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['code']) {
        // kembalikan gabungan kode + nama parent
        return $row['code'] . ' - ' . $row['name'];
    }

    return "Tidak ada referal Code";
}

          // default nilai
          $provinsiName = $data->provinsi;
          $kotaName = $data->kota;
          $kecamatanName = $data->kecamatan;

          // ambil nama dari API jika ada
          if (!empty($data->provinsi)) {
            $provinsiName = getWilayahName("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json", $data->provinsi) ?? $data->provinsi;
          }
          if (!empty($data->kota)) {
            $kotaName = getWilayahName("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$data->provinsi}.json", $data->kota) ?? $data->kota;
          }
          if (!empty($data->kecamatan)) {
            $kecamatanName = getWilayahName("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$data->kota}.json", $data->kecamatan) ?? $data->kecamatan;
          }
      ?>

      <!-- Judul -->
      <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 24px; user-select:none;">
        Profil Anggota: <?=htmlspecialchars($data->suppliar_code)?>
      </h2>

      <!-- Informasi User Readonly -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; font-size: 15px; line-height: 1.6;">
        <div><label style="font-weight: bold;">Nama Lengkap:</label><br><?= htmlspecialchars($data->name) ?></div>
        <div><label style="font-weight: bold;">NIK:</label><br><?= htmlspecialchars($data->nik) ?></div>
        <div><label style="font-weight: bold;">No Rekening:</label><br><?= htmlspecialchars($data->rekening) ?></div>
        <div><label style="font-weight: bold;">Nama Rekening:</label><br><?= htmlspecialchars($data->nama_rekening) ?></div>
        <div><label style="font-weight: bold;">Bank:</label><br><?= htmlspecialchars($data->bank) ?></div>
        <div><label style="font-weight: bold;">No Kontak:</label><br><?= htmlspecialchars($data->con_num) ?></div>
        <div><label style="font-weight: bold;">Tanggal Lahir:</label><br><?= $data->date_of_birth ? date('d-m-Y', strtotime($data->date_of_birth)) : '-' ?></div>
        <div><label style="font-weight: bold;">Email:</label><br><?= htmlspecialchars($data->email) ?></div>
        <div><label style="font-weight: bold;">Level Anggota:</label><br>
          <?php 
            $roles = [10=>'Super Admin',1=>'Head Officer',2=>'Head Distributor',3=>'Distributor',4=>'Agen',5=>'Reseller'];
            echo $roles[$data->role_id] ?? 'Unknown';
          ?>
        </div>
        <div style="grid-column: 1 / -1;"><label style="font-weight: bold;">Alamat KTP:</label><br><?= nl2br(htmlspecialchars($data->address_ktp)) ?></div>
        <div style="grid-column: 1 / -1;"><label style="font-weight: bold;">Alamat Pengiriman:</label><br><?= nl2br(htmlspecialchars($data->address)) ?></div>
        <div><label style="font-weight: bold;">Provinsi:</label><br><?= htmlspecialchars($provinsiName) ?></div>
        <div><label style="font-weight: bold;">Kota:</label><br><?= htmlspecialchars($kotaName) ?></div>
        <div><label style="font-weight: bold;">Kecamatan:</label><br><?= htmlspecialchars($kecamatanName) ?></div>
        <?php if($_SESSION['role_id'] == 5): ?><div><label style="font-weight: bold;">ID Pengundang:</label><br><?= htmlspecialchars(getReferralCode($pdo, $data->parent_id ? $data->parent_id : 0)) ?></div><?php endif ?>
      </div>

      <!-- Form Ganti Password -->
      <form id="update_userForm" style="margin-top: 20px;">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($data->suppliar_code); ?>">

        <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(280px,1fr)); gap:20px;">
          <div style="position: relative;">
            <label for="password" style="font-weight: bold; color: #334155; display:block; margin-bottom:6px;">Password Baru</label>
            <input type="password" id="password" name="password"
              style="width: 100%; padding: 12px 45px 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 15px;">
            <span onclick="togglePassword('password', this)" style="position: absolute; right: 12px; top:50%; transform: translateY(-50%); cursor: pointer; font-size: 14px; color: #475569;">
              👁
            </span>
          </div>

          <div style="position: relative;">
            <label for="confirm_password" style="font-weight: bold; color: #334155; display:block; margin-bottom:6px;">Konfirmasi Password Baru</label>
            <input type="password" id="c_password" name="c_password"
              style="width: 100%; padding: 12px 45px 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 15px;">
            <span onclick="togglePassword('c_password', this)" style="position: absolute; right: 12px; top:50%; transform: translateY(-50%); cursor: pointer; font-size: 14px; color: #475569;">
              👁
            </span>
          </div>
        </div>

        <!-- Buttons -->
        <div style="margin-top: 30px; display: flex; justify-content: center; gap: 20px;">
          <button type="reset" style="
            background-color: #ef4444;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;">Reset</button>

          <button type="submit" style="
            background-color: #EEA0A0;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;">Update Password</button>
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

<script>
function togglePassword(fieldId, el) {
  const input = document.getElementById(fieldId);
  if(input.type === "password") {
    input.type = "text"; el.textContent = "🙈";
  } else {
    input.type = "password"; el.textContent = "👁";
  }
}
</script>
