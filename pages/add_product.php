<div class="content-wrapper" style="margin-top:75px; margin-bottom:75px; background-color: #f6f7fb;">
  <section class="content">
    <div class="container-fluid" style="margin-top: 40px;">
      <div style="
        background: white;
        border-radius: 16px;
        box-shadow: 0px 4px 20px rgba(0,0,0,0.05);
        padding: 24px;
      ">
        <h2 style="margin-bottom: 24px; font-size: 20px; font-weight: 600; color: #333;">📦 Form Tambah Produk</h2>

        <!-- Success Message -->
        <div id="productErrorArea" style="
  display: none;
  padding: 10px 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-weight: 500;
  border: 1px solid transparent;
">
          <span id="productErrorMessage"></span>
        </div>

        <form id="addProduct" method="POST" action="add_product.php">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="product_name" style="font-weight: 500;">Nama Produk *</label>
              <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Product name"
                style="border-radius: 8px;" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="p_catagory" style="font-weight: 500;">Kategori Produk *</label>
              <select name="p_catagory" id="p_catagory" class="form-control select2" required
                style="border-radius: 8px;">
                <option disabled selected>Pilih kategori</option>
                <?php 
                  $all_catgory = $obj->all('catagory');
                  foreach ($all_catgory as $catagory) {
                      echo "<option value='{$catagory->id}'>{$catagory->name}</option>";
                  }
                ?>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="sell_price" style="font-weight: 500;">Harga Head Distributor*</label>
              <input type="number" class="form-control" id="sell_price_hd" name="sell_price_hd" placeholder="Harga jual"
                style="border-radius: 8px;" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="sell_price" style="font-weight: 500;">Harga Distributor*</label>
              <input type="number" class="form-control" id="sell_price_d" name="sell_price_d" placeholder="Harga jual"
                style="border-radius: 8px;" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="sell_price" style="font-weight: 500;">Harga Agen*</label>
              <input type="number" class="form-control" id="sell_price_a" name="sell_price_a" placeholder="Harga jual"
                style="border-radius: 8px;" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="sell_price" style="font-weight: 500;">Harga Reseller*</label>
              <input type="number" class="form-control" id="sell_price_r" name="sell_price_r" placeholder="Harga jual"
                style="border-radius: 8px;" required>
            </div>
            <div class="col-md-6 mb-3 d-flex align-items-end">
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".catagoryModal"
                style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-plus"></i> Tambah Kategori
              </button>
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
                transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#d32f2f'"
                onmouseout="this.style.backgroundColor='#f44336'">Reset</button>

              <button type="submit" style="
                background-color: #0073ea;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 10px 20px;
                font-weight: 600;
                font-size: 14px;
                transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#005bb5'"
                onmouseout="this.style.backgroundColor='#0073ea'">Simpan Produk</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>