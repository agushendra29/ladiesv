<div style="margin-bottom:75px; background-color: #f6f7fb;">
  <section class="content">
    <div class="container-fluid" style="margin-top: 50px;">
      <div>
        <h2 style="margin-bottom: 24px; font-size: 20px; font-weight: 600; color: #333;">📰 Form Tambah Berita</h2>

        <!-- Error Message -->
        <div id="newsErrorArea" style="
          display: none;
          padding: 10px 15px;
          border-radius: 8px;
          margin-bottom: 20px;
          font-weight: 500;
          border: 1px solid transparent;
        ">
          <span id="newsErrorMessage"></span>
        </div>

        <form id="addNews" method="POST" action="add_news.php">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="title" style="font-weight: 500;">Judul Berita *</label>
              <input type="text" class="form-control" id="title" name="title" placeholder="Judul berita"
                style="border-radius: 8px;" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="category" style="font-weight: 500;">Kategori Berita *</label>
              <input type="text" class="form-control" id="category" name="category" placeholder="Kategori berita"
                style="border-radius: 8px;" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="publish_date" style="font-weight: 500;">Tanggal Publikasi *</label>
              <input type="date" class="form-control" id="publish_date" name="publish_date"
                style="border-radius: 8px;" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="content" style="font-weight: 500;">Isi Berita *</label>
              <textarea class="form-control" id="content" name="content" rows="6"
                placeholder="Tulis isi berita di sini..." style="border-radius: 8px;" required></textarea>
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
                Simpan Berita
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
