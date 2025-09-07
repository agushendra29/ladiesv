<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<div class="mt-5">
  <section class="content">
    <div class="container-fluid">
      <div>
         <h2 style="margin-bottom: 24px; font-size: 20px; font-weight: 600; color: #333;">📦 Form Tambah Stok</h2>
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
              <input type="number" id="stock_quantity" name="stock_quantity" placeholder="Jumlah Stok" style="width: 100%; border: 1px solid #ccc; border-radius: 12px; padding: 12px;" />
            </div>

            <!-- Suppliar -->
            <div class="col-md-12" style="margin-bottom: 24px;">
              <label for="p_suppliar" style="font-weight: 600; display: block; margin-bottom: 8px;">Distributor / Agen *</label>
              <select name="p_suppliar" id="p_suppliar" style="width: 100%; border: 1px solid #ccc; border-radius: 12px; padding: 12px;">
               <?php 
      $all_sup = $obj->all('suppliar');
      foreach ($all_sup as $catagory) {
        // filter hanya role_id 1,2,3,4,5
        if (in_array($catagory->role_id, [1,2,3,4])) {
          // role_id 1 → hanya suppliar_code 000001
          if ($catagory->role_id == 1 && $catagory->suppliar_code !== '000001') {
            continue;
          }

          $roleName = ($catagory->role_id == 3) ? 'Distributor' :
                      (($catagory->role_id == 4) ? 'Agen' :
                      (($catagory->role_id == 2) ? 'Head Distributor' : 'Head Office'));

          echo "<option value='{$catagory->id}' data-role-id='{$catagory->role_id}'>{$roleName} - {$catagory->name} ({$catagory->suppliar_code})</option>";
        }
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
<script>
 document.addEventListener("DOMContentLoaded", function() {
  new Choices("#p_suppliar", {
    searchEnabled: true,   // aktifkan fitur search
    itemSelectText: '',    // hilangkan tulisan "Press to select"
    shouldSort: false,     // biar urutan option asli tidak berubah
    placeholderValue: "Pilih Distributor/Agen"
  });
});
</script>