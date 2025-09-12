<?php

// Ambil event_name & role_id dari URL (bukan event_id, karena pakai nama event)
$event_name = $_GET['event_name'] ?? '';
$role_id    = $_GET['role_id'] ?? 0;

if (empty($event_name) || empty($role_id)) {
    die("Parameter event_name dan role_id diperlukan.");
}

// Ambil item reward
$stmt2 = $pdo->prepare("SELECT * FROM rewards WHERE event_name = ? AND role_id = ?");
$stmt2->execute([$event_name, $role_id]);
$reward_items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Ambil data event
$stmtEvent = $pdo->prepare("
    SELECT event_name, periode_hadiah_dari, periode_hadiah_sampai,
           redeem_start, redeem_end, role_id
    FROM rewards
    WHERE event_name = ? AND role_id = ?
    LIMIT 1
");
$stmtEvent->execute([$event_name, $role_id]);
$event = $stmtEvent->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Event tidak ditemukan.");
}
?>
<div style="margin-bottom:75px; background-color: white;">
  <section class="content">
    <div class="container-fluid" style="margin-top: 50px;">
      <div>
        <h2 style="margin-bottom: 24px; font-size: 20px; font-weight: 600; color: #333;">✏️ Edit Reward</h2>

        <form id="editRewardForm" class="form-grid" method="POST" action="update_reward.php">
          <input type="hidden" name="original_event_name" value="<?= htmlspecialchars($event['event_name']) ?>">
          <input type="hidden" name="original_role_id" value="<?= htmlspecialchars($event['role_id']) ?>">

          <!-- Nama Event -->
          <div class="form-group" style="grid-column: 1/-1;">
            <label for="event_name">Nama Event *</label>
            <input type="text" class="form-control" id="event_name" name="event_name"
                   value="<?= htmlspecialchars($event['event_name']) ?>" required>
          </div>

          <!-- Periode Reward -->
          <div class="form-group">
            <label for="reward_start">Periode Reward Dari *</label>
            <input type="text" class="form-control date-input" id="reward_start" name="reward_start"
                   value="<?= date('d-m-Y', strtotime($event['periode_hadiah_dari'])) ?>" required>
          </div>
          <div class="form-group">
            <label for="reward_end">Periode Reward Sampai *</label>
            <input type="text" class="form-control date-input" id="reward_end" name="reward_end"
                   value="<?= date('d-m-Y', strtotime($event['periode_hadiah_sampai'])) ?>" required>
          </div>

          <!-- Periode Penukaran -->
          <div class="form-group">
            <label for="redeem_start">Periode Penukaran Dari *</label>
            <input type="text" class="form-control date-input" id="redeem_start" name="redeem_start"
                   value="<?= date('d-m-Y', strtotime($event['redeem_start'])) ?>" required>
          </div>
          <div class="form-group">
            <label for="redeem_end">Periode Penukaran Sampai *</label>
            <input type="text" class="form-control date-input" id="redeem_end" name="redeem_end"
                   value="<?= date('d-m-Y', strtotime($event['redeem_end'])) ?>" required>
          </div>

          <!-- Role Global -->
          <div class="form-group" style="grid-column:1/-1;">
            <label for="role_id">Role Anggota *</label>
            <select name="role_id" id="role_id" class="form-control" required>
              <option value="" disabled>-- Pilih Level Anggota --</option>
              <option value="2" <?= $event['role_id']==2?'selected':''; ?>>Head Distributor</option>
              <option value="3" <?= $event['role_id']==3?'selected':''; ?>>Distributor</option>
              <option value="4" <?= $event['role_id']==4?'selected':''; ?>>Agen</option>
              <option value="5" <?= $event['role_id']==5?'selected':''; ?>>Reseller</option>
            </select>
          </div>

          <!-- Tabel Reward Items -->
          <div style="grid-column: 1/-1; margin-top:20px;">
            <h5 style="margin-bottom: 15px;">Daftar Reward</h5>
            <table class="table table-bordered" id="rewardTable">
              <thead style="background:#f1f5f9;">
                <tr>
                  <th>Nama Reward</th>
                  <th>Jumlah Point</th>
                  <th>Maksimal Redeem</th>
                  <th style="width:50px;">#</th>
                </tr>
              </thead>
              <tbody>
                <?php $idx=0; foreach($reward_items as $item): ?>
                <tr>
                  <td>
                    <input type="hidden" name="rewards[<?= $idx ?>][id]" value="<?= $item['id'] ?>">
                    <input type="text" class="form-control" name="rewards[<?= $idx ?>][nama_reward]"
                           value="<?= htmlspecialchars($item['nama_reward']) ?>" required>
                  </td>
                  <td>
                    <input type="number" class="form-control" name="rewards[<?= $idx ?>][jumlah_point]"
                           value="<?= (int)$item['jumlah_point'] ?>" min="0" required>
                  </td>
                  <td>
                    <input type="number" class="form-control" name="rewards[<?= $idx ?>][max_redeem]"
                           value="<?= (int)$item['max_redeem'] ?>" min="0" required>
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-xs remove-reward">✖</button>
                  </td>
                </tr>
                <?php $idx++; endforeach; ?>
              </tbody>
            </table>
            <button type="button" id="addRewardItem" class="btn btn-primary" style="font-size:14px !important;">+ Tambah Reward</button>
          </div>

          <!-- Tombol Submit -->
          <div class="form-actions">
            <button type="submit" class="btn btn-submit">Update Reward</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<!-- CSS -->
<style>
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}
.form-group label { font-weight: 600; margin-bottom: 6px; }
.form-group input, .form-group select {
  padding: 10px 14px;
  border-radius: 8px;
  border: 1.5px solid #cbd5e1;
  font-size: 14px;
  width: 100%;
}
.form-actions {
  grid-column: 1/-1;
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
}
.btn-submit { background:#EB4F7D; color:white; border:none; border-radius:8px; padding:10px 20px; }
.table th, .table td { vertical-align: middle; }
.btn-xs { padding: 2px 6px; font-size: 12px; line-height: 1; border-radius: 4px; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let rewardIndex = <?= $idx ?? 0 ?>;

// Auto-format tanggal (dd-mm-yyyy)
document.addEventListener("input", function(e) {
  if (e.target.classList.contains("date-input")) {
    let val = e.target.value.replace(/\D/g, "");
    if(val.length > 2 && val.length <= 4) {
      val = val.slice(0,2) + "-" + val.slice(2);
    } else if(val.length > 4) {
      val = val.slice(0,2) + "-" + val.slice(2,4) + "-" + val.slice(4,8);
    }
    e.target.value = val;
  }
});

// Validasi tanggal sebelum submit
document.getElementById("editRewardForm").addEventListener("submit", function(e){
  const dates = document.querySelectorAll(".date-input");
  const pattern = /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/;
  for(let input of dates){
    if(input.value && !pattern.test(input.value)){
      e.preventDefault();
      Swal.fire("Error", "Format tanggal salah! Gunakan dd-mm-yyyy", "error");
      return false;
    }
  }
});

// Tambah baris reward baru
document.getElementById("addRewardItem").addEventListener("click", function(){
  let tbody = document.querySelector("#rewardTable tbody");
  let row = document.createElement("tr");
  row.innerHTML = `
    <td><input type="text" class="form-control" name="rewards[${rewardIndex}][nama_reward]" required></td>
    <td><input type="number" class="form-control" name="rewards[${rewardIndex}][jumlah_point]" min="0" required></td>
    <td><input type="number" class="form-control" name="rewards[${rewardIndex}][max_redeem]" min="0" required></td>
    <td class="text-center">
      <button type="button" class="btn btn-outline-danger btn-xs remove-reward">✖</button>
    </td>
  `;
  tbody.appendChild(row);
  rewardIndex++;
});

// Hapus baris reward
document.addEventListener("click", function(e){
  if(e.target.classList.contains("remove-reward")){
    e.target.closest("tr").remove();
  }
});
</script>
