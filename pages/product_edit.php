<div style="margin-bottom:75px;">
  <section class="content">
    <div class="container-fluid" style="margin-top: 50px;">
      <div>
        <h2 style="margin-bottom: 24px; font-size: 20px; font-weight: 600; color: #333;">✏️ Form Edit Produk</h2>

        <!-- Error Message -->
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

        <?php 
          if (isset($_GET['edit_id'])):
            $edit_id = $_GET['edit_id'];
            $data = $obj->find('products', 'id', $edit_id);
            if ($data):
        ?>
        <form id="editProduct" method="POST" action="edit_product.php">
          <input type="hidden" name="id" value="<?= htmlspecialchars($edit_id) ?>">
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="product_name" style="font-weight: 500;">Nama Produk *</label>
              <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Product name"
                style="border-radius: 8px;" required value="<?= htmlspecialchars($data->product_name) ?>">
            </div>

            <div class="col-md-6 mb-3">
              <label for="p_catagory" style="font-weight: 500;">Kategori Produk *</label>
              <select name="p_catagory" id="p_catagory" class="form-control select2" required style="border-radius: 8px;">
                <option disabled>Pilih kategori</option>
                <?php 
                  $all_catgory = $obj->all('catagory');
                  foreach ($all_catgory as $catagory) {
                    $selected = ($data->catagory_id == $catagory->id) ? 'selected' : '';
                    echo "<option value='{$catagory->id}' {$selected}>{$catagory->name}</option>";
                  }
                ?>
              </select>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="sell_price_hd" style="font-weight: 500;">Harga Head Distributor *</label>
              <input type="number" class="form-control" id="sell_price_hd" name="sell_price_hd" placeholder="Harga jual"
                style="border-radius: 8px;" required value="<?= htmlspecialchars($data->sell_price_hd) ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="sell_price_d" style="font-weight: 500;">Harga Distributor *</label>
              <input type="number" class="form-control" id="sell_price_d" name="sell_price_d" placeholder="Harga jual"
                style="border-radius: 8px;" required value="<?= htmlspecialchars($data->sell_price_d) ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="sell_price_a" style="font-weight: 500;">Harga Agen *</label>
              <input type="number" class="form-control" id="sell_price_a" name="sell_price_a" placeholder="Harga jual"
                style="border-radius: 8px;" required value="<?= htmlspecialchars($data->sell_price_a) ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="sell_price_r" style="font-weight: 500;">Harga Reseller *</label>
              <input type="number" class="form-control" id="sell_price_r" name="sell_price_r" placeholder="Harga jual"
                style="border-radius: 8px;" required value="<?= htmlspecialchars($data->sell_price_r) ?>">
            </div>
          </div>
           <div class="col-md-6 mb-3 d-flex align-items-end">
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".catagoryModal"
                style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-plus"></i> Tambah Kategori
              </button>
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
                onmouseout="this.style.backgroundColor='#0073ea'">Update Produk</button>
            </div>
          </div>
        </form>

        <?php 
            else:
              header("Location: index.php?page=error_page");
              exit;
            endif;
          else:
            header("Location: index.php?page=error_page");
            exit;
          endif;
        ?>
      </div>
    </div>
  </section>
</div>
