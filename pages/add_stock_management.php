<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2" style="margin-bottom: 16px;">
        <div class="col-sm-6">
          <h3 style="font-weight: bold;">Tambah Stok</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right" style="background: none; padding: 0; margin: 0;">
            <li class="breadcrumb-item"><a href="#" style="color: #007bff;">Home</a></li>
            <li class="breadcrumb-item active" style="color: #6c757d;">Tambah Produk</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div style="background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 24px; color: #1f2937;">Stok Manajemen</h2>
        <!-- Alert -->
        <div class="alert alert-primary alert-dismissible fade show addStockManagementError-area" role="alert" style="display: none; border-radius: 12px; padding: 16px;">
          <span id="addStockManagementError"></span>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="background: none; border: none; font-size: 20px;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <!-- Form -->
        <form id="addStockManagement">
          <div class="row">
            <!-- Produk -->
            <div class="col-md-6" style="margin-bottom: 24px;">
              <label for="p_product" style="font-weight: 600; display: block; margin-bottom: 8px;">Produk *</label>
              <select name="p_product" id="p_product" style="width: 100%; border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
                <option disabled selected>Pilih Produk</option>
                <?php 
                  $all_catgory = $obj->all('products');
                  foreach ($all_catgory as $catagory) {
                    echo "<option value='{$catagory->id}'>{$catagory->product_name}</option>";
                  }
                ?>
              </select>
            </div>

            <!-- Harga -->
            <div class="col-md-6" style="margin-bottom: 24px;">
              <label for="stock_quantity" style="font-weight: 600; display: block; margin-bottom: 8px;">Jumlah Stok *</label>
              <input type="number" id="stock_quantity" name="stock_quantity" placeholder="Harga Produk" style="width: 100%; border: 1px solid #ccc; border-radius: 12px; padding: 12px;" />
            </div>

            <!-- Suppliar -->
            <div class="col-md-12" style="margin-bottom: 24px;">
              <label for="p_suppliar" style="font-weight: 600; display: block; margin-bottom: 8px;">Distributor / Agen *</label>
              <select name="p_suppliar" id="p_suppliar" style="width: 100%; border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
                <option disabled selected>Pilih Distributor/Agen</option>
                <?php 
                  $all_sup = $obj->all('suppliar');
                  foreach ($all_sup as $catagory) {
                    $roleName = ($catagory->role_id == 3) ? 'Distributor' :
                                (($catagory->role_id == 4) ? 'Agen' :
                                (($catagory->role_id == 2) ? 'Head Distributor' : 'HO'));
                    echo "<option value='{$catagory->id}' data-role-id='{$catagory->role_id}'>{$roleName} - {$catagory->name}</option>";
                  }
                ?>
              </select>
            </div>
          </div>

          <!-- Tombol -->
          <div class="row justify-content-center" style="margin-top: 32px;">
            <button type="reset" style="background-color: #dc3545; color: #fff; border: none; border-radius: 10px; padding: 10px 40px; font-size: 16px; margin-right: 16px; cursor: pointer;">
              Reset
            </button>
            <button type="submit" style="background-color: #007bff; color: #fff; border: none; border-radius: 10px; padding: 10px 40px; font-size: 16px; cursor: pointer;">
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
