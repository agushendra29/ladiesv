<!-- Content Wrapper. Contains page content  -->
<div class="mb-3 mt-3">
  <section class="content">
    <div style="padding:40px 20px; max-width: 900px; margin:auto;">
      <?php 
        if (isset($_GET['edit_id'])) {
          $edit_id = $_GET['edit_id'];
          $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
          $stmt->execute([$edit_id]);
          $data = $stmt->fetch(PDO::FETCH_OBJ);

          if ($data) {
      ?>

      <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 24px; text-align:center;">
        📰 Edit News: <?=htmlspecialchars($data->title)?>
      </h2>

      <div class="newsFormError-area" 
        style="display:none; margin-bottom: 20px; font-weight: 600; color: #b91c1c; background-color: #fee2e2; border-radius: 8px; padding: 12px 16px;">
        <span id="newsFormError"></span>
      </div>

      <form id="editNewsForm" enctype="multipart/form-data" class="responsive-form">
        <input type="hidden" name="id" value="<?= htmlspecialchars($edit_id); ?>">

        <div class="form-group full">
          <label for="title">Judul Berita <span style="color:#ef4444;">*</span></label>
          <input type="text" id="title" name="title" value="<?= htmlspecialchars($data->title); ?>" required>
        </div>

        <div class="form-group full">
          <label for="content">Isi Berita <span style="color:#ef4444;">*</span></label>
          <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($data->content); ?></textarea>
        </div>

        <div class="form-group">
          <label for="category">Kategori</label>
          <input type="text" id="category" name="category" value="<?= htmlspecialchars($data->category); ?>">
        </div>

        <div class="form-group">
          <label for="publish_date">Tanggal Publikasi</label>
          <input type="date" id="publish_date" name="publish_date" value="<?= htmlspecialchars($data->publish_date); ?>">
        </div>

        <div class="form-actions">
          <button type="reset" class="btn-red">Reset</button>
          <button type="submit" class="btn-blue">Update Berita</button>
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

<style>
  /* Grid responsive */
  .responsive-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }
  .responsive-form .form-group {
    display: flex;
    flex-direction: column;
  }
  .responsive-form .form-group.full {
    grid-column: 1 / -1;
  }
  .responsive-form label {
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
    font-size: 15px;
  }
  .responsive-form input,
  .responsive-form textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 15px;
    transition: border-color 0.2s ease;
  }
  .responsive-form input:focus,
  .responsive-form textarea:focus {
    border-color: #EEA0A0;
    outline: none;
  }

  /* Tombol */
  .form-actions {
    grid-column: 1 / -1;
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
    flex-wrap: wrap;
  }
  .btn-red,
  .btn-blue {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    border: none;
    transition: transform 0.2s ease;
  }
  .btn-red { background-color: #ef4444; color: #fff; }
  .btn-blue { background-color: #EEA0A0; color: #fff; }
  .btn-red:hover,
  .btn-blue:hover { transform: translateY(-2px); }
  @media(max-width: 600px){
    h2 { font-size: 20px; }
    .btn-red, .btn-blue { width: 100%; }
    .form-actions { flex-direction: column; }
  }
</style>

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
        window.location.href = "index.php?page=news";
      }else{
        document.querySelector(".newsFormError-area").style.display="block";
        document.getElementById("newsFormError").innerText = res;
         alert("Berita berhasil diperbarui!");
        window.location.href = "index.php?page=news";
      }
    })
    .catch(err => {
      document.querySelector(".newsFormError-area").style.display="block";
      document.getElementById("newsFormError").innerText = "Terjadi error: " + err;
    });
  });
</script>
