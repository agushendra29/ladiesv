<!-- Content Wrapper. Contains page content  -->
<div class="">
  <section class="content">
    <div style="padding:75px; max-width: 900px; margin:auto;">
      <?php 
        if (isset($_GET['edit_id'])) {
          $edit_id = $_GET['edit_id'];
          $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
          $stmt->execute([$edit_id]);
          $data = $stmt->fetch(PDO::FETCH_OBJ);

          if ($data) {
      ?>

      <h2 style="font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 32px; user-select:none;">
        📰 Edit News: <?=htmlspecialchars($data->title)?>
      </h2>

      <div class="alert alert-danger newsFormError-area"
        style="display:none; margin-bottom: 20px; font-weight: 600; color: #b91c1c; background-color: #fee2e2; border-radius: 8px; padding: 12px 16px;">
        <span id="newsFormError"></span>
      </div>

      <form id="editNewsForm"
        enctype="multipart/form-data"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
        <input type="hidden" name="id" value="<?= htmlspecialchars($edit_id); ?>">

        <div style="grid-column: 1 / -1;">
          <label for="title" style="font-weight: 600; color: #334155;">Judul Berita <span
              style="color:#ef4444;">*</span></label>
          <input type="text" id="title" name="title" value="<?= htmlspecialchars($data->title); ?>" required
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div style="grid-column: 1 / -1;">
          <label for="content" style="font-weight: 600; color: #334155;">Isi Berita <span
              style="color:#ef4444;">*</span></label>
          <textarea id="content" name="content" rows="6" required
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;"><?= htmlspecialchars($data->content); ?></textarea>
        </div>

        <div>
          <label for="category" style="font-weight: 600; color: #334155;">Kategori</label>
          <input type="text" id="category" name="category" value="<?= htmlspecialchars($data->category); ?>"
            style="width: 100%; padding: 12px 16px; border: 1.8px solid #cbd5e1; border-radius: 12px; font-size: 16px;">
        </div>

        <div>
          <label for="date" style="font-weight: 600; color: #334155;">Tanggal Publikasi</label>
          <input type="date" id="publish_date" name="publish_date" value="<?= htmlspecialchars($data->publish_date); ?>"
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
            Update Berita
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

<script>
  document.getElementById("editNewsForm").addEventListener("submit", function(e){
    e.preventDefault();
    let formData = new FormData(this);
    fetch("app/action/edit_news.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(res => {
      if(res.trim() === "yes"){
        alert("Berita berhasil diperbarui!");
        window.location.href = "index.php?page=news_list";
      }else{
        document.querySelector(".newsFormError-area").style.display="block";
        document.getElementById("newsFormError").innerText = res;
      }
    })
    .catch(err => {
      document.querySelector(".newsFormError-area").style.display="block";
      document.getElementById("newsFormError").innerText = "Terjadi error: " + err;
    });
  });
</script>
