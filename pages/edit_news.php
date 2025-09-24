<?php
if (!isset($_GET['edit_id'])) {
    header("location:index.php?page=error_page");
    exit;
}

$edit_id = $_GET['edit_id'];
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$edit_id]);
$data = $stmt->fetch(PDO::FETCH_OBJ);
if (!$data) {
    header("location:index.php?page=error_page");
    exit;
}

// ambil roles yg tersimpan, misal disimpan sebagai "1,3,4"
$savedRoles = array_filter(array_map('trim', explode(',', $data->role ?? '')));
?>

<div style="padding-top:62px; padding-bottom:72px;">
  <section class="content">
    <div class="container-fluid section-card-body" style="padding-top: 52px; padding-bottom:52px;">
      <div>
        <h2 style="margin-bottom: 24px; font-size: 20px; font-weight: 600; color: #333;">
          ✏️ Edit Berita
        </h2>

        <!-- Error Message -->
        <div id="newsErrorArea" style="
          display: none;
          padding: 10px 15px;
          border-radius: 8px;
          margin-bottom: 20px;
          font-weight: 500;
          border: 1px solid transparent;">
          <span id="newsErrorMessage"></span>
        </div>

        <form id="editNews" method="POST" action="app/action/edit_news.php">
          <input type="hidden" name="id" value="<?= htmlspecialchars($edit_id) ?>">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="title" style="font-weight: 500;">Judul Berita *</label>
              <input type="text" class="form-control" id="title" name="title"
                     value="<?= htmlspecialchars($data->title) ?>"
                     placeholder="Judul berita" style="border-radius: 8px;" required>
            </div>

            <div class="col-md-6 mb-3">
              <label for="category" style="font-weight: 500;">Kategori Berita *</label>
              <input type="text" class="form-control" id="category" name="category"
                     value="<?= htmlspecialchars($data->category) ?>"
                     placeholder="Kategori berita" style="border-radius: 8px;" required>
            </div>
          </div>

          <!-- ✅ Multiple Checkbox untuk Role -->
          <div class="row">
            <div class="col-md-12 mb-3">
              <label style="font-weight: 500;">Tampilkan Untuk Role</label>
              <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 6px;">
                <?php
                $rolesList = [
                  1 => 'Head Office',
                  2 => 'Head Distributor',
                  3 => 'Distributor',
                  4 => 'Agen',
                  5 => 'Reseller'
                ];
                foreach ($rolesList as $val => $label) {
                  $checked = in_array((string)$val, $savedRoles) ? 'checked' : '';
                  echo '<label><input type="checkbox" name="roles[]" value="'.$val.'" '.$checked.'> '.$label.'</label>';
                }
                ?>
                <small style="display:block;width:100%;margin-top:8px;">
                  Kosongkan bila berita bisa dilihat semua role.
                </small>
              </div>
            </div>
          </div>
          <!-- End Checkbox -->

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="publish_date" style="font-weight: 500;">Tanggal Publikasi *</label>
              <input type="date" class="form-control" id="publish_date" name="publish_date"
                     value="<?= htmlspecialchars($data->publish_date) ?>"
                     style="border-radius: 8px;" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="content" style="font-weight: 500;">Isi Berita *</label>
              <textarea class="form-control" id="content" name="content" rows="6"
                        style="border-radius: 8px;" required><?= htmlspecialchars($data->content) ?></textarea>
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
                background-color: #EB4F7D;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 10px 20px;
                font-weight: 600;
                font-size: 14px;
                transition: background-color 0.3s ease;"
                onmouseover="this.style.backgroundColor='#005bb5'"
                onmouseout="this.style.backgroundColor='#0073ea'">
                Update Berita
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script>
document.getElementById("editNews").addEventListener("submit", function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch("app/action/edit_news.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.text())
  .then(res => {
    if (res.trim() === "yes") {
      alert("Berita berhasil diperbarui!");
      window.location.href = "index.php?page=news";
    } else {
      document.getElementById("newsErrorArea").style.display = "block";
      document.getElementById("newsErrorMessage").innerText = res;
    }
  })
  .catch(err => {
    document.getElementById("newsErrorArea").style.display = "block";
    document.getElementById("newsErrorMessage").innerText = "Terjadi error: " + err;
  });
});
</script>
