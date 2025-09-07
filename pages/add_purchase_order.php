<?php 
$products = $obj->all('products'); 
?>

<div class="pb-5">
  <!-- Header -->
  <section class="content">
    <div class="container-fluid mt-5">
      <div>
        <h2 style="margin-bottom: 24px; font-size: 20px; font-weight: 600; color: #333;">📝 Form Pemesanan Produk</h2>
        
        <div class="alert alert-danger purchaseOrderError-area" style="display:none;">
          <span id="purchaseOrderError"></span>
        </div>

        <form id="purchaseOrderForm" method="POST" action="app/ajax/purchase_order_action.php">
          <!-- Wrapper produk -->
          <div id="productWrapper">
            <div class="row product-row mb-3">
              <div class="col-md-6">
                <label style="font-weight:500;">Produk *</label>
                <select name="product_id[]" class="form-control select2 product-select" required style="border-radius: 8px;">
                  <option disabled selected>Pilih produk</option>
                  <?php foreach ($products as $product): ?>
                    <?php
                      if ($product->is_active != 1) continue;
    $role_id = $_SESSION['role_id'];
    $price = 0;

    if ($role_id == 2) {
        $price = $product->sell_price_hd;
    } elseif ($role_id == 3) {
        $price = $product->sell_price_d;
    } elseif ($role_id == 4) {
        $price = $product->sell_price_a;
    } elseif ($role_id == 5) {
        $price = $product->sell_price_r;
    }
  ?>
                    <option value="<?= $product->id ?>" data-price="<?= $price ?>">
                      <?= $product->product_name ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3">
                <label style="font-weight:500;">Kuantitas</label>
                <input type="number" name="quantity[]" class="form-control quantity-input" min="1" required style="border-radius: 8px;">
              </div>
              <div class="col-md-3">
                <label style="font-weight:500;">Total</label>
                <input type="number" name="total_payment[]" class="form-control total-input" readonly style="border-radius: 8px;">
              </div>
            </div>
          </div>

          <!-- Tombol tambah produk -->
          <div class="mb-4">
            <button type="button" id="addProductRow" style="
              background-color:#EB4F7D;
              color:white;
              border:none;
              border-radius:8px;
              padding:6px 14px;
              font-weight:600;
              font-size:13px;
              transition:background-color 0.3s ease;">
              ➕ Tambah Produk
            </button>
          </div>

          <!-- Distributor & Total Keseluruhan -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="distributor" style="font-weight: 500;">Distributor *</label>
              <input type="text" name="distributor" id="distributor" class="form-control" value="Head Officer" readonly style="border-radius: 8px;">
            </div>
            <div class="col-md-6 mb-3">
              <label style="font-weight:500;">Total Keseluruhan</label>
              <input type="number" id="grandTotal" class="form-control" readonly style="border-radius: 8px;">
            </div>
          </div>

          <!-- Action -->
          <div class="row mt-4">
            <div class="col-md-6 offset-md-3 text-center">
              <button type="reset" style="background-color:#f44336;color:white;border:none;border-radius:8px;padding:10px 20px;font-weight:600;font-size:14px;margin-right:10px;transition:background-color 0.3s ease;" 
                onmouseover="this.style.backgroundColor='#d32f2f'" 
                onmouseout="this.style.backgroundColor='#f44336'">
                Reset
              </button>

              <button type="submit" style="background-color:#EB4F7D;color:white;border:none;border-radius:8px;padding:10px 20px;font-weight:600;font-size:14px;transition:background-color 0.3s ease;" 
                onmouseover="this.style.backgroundColor='#005bb5'" 
                onmouseout="this.style.backgroundColor='#0073ea'">
                Submit PO
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<!-- Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  function initSelect2() {
    $('.select2').select2({
      width: '100%',
    });
  }

  function updateRowTotal(row) {
    const productSelect = row.querySelector(".product-select");
    const qtyInput = row.querySelector(".quantity-input");
    const totalInput = row.querySelector(".total-input");

    const price = parseFloat(productSelect?.selectedOptions[0]?.dataset.price || 0);
    const qty   = parseInt(qtyInput.value) || 0;
    const total = price * qty;

    totalInput.value = total.toFixed(2);
    updateGrandTotal();
  }

  function updateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll(".total-input").forEach(inp => {
      grandTotal += parseFloat(inp.value) || 0;
    });
    document.getElementById("grandTotal").value = grandTotal.toFixed(2);
  }

  // Event listener untuk row pertama
  document.addEventListener("input", function(e) {
    if (e.target.classList.contains("quantity-input") || e.target.classList.contains("product-select")) {
      const row = e.target.closest(".product-row");
      updateRowTotal(row);
    }
  });

  // Tombol tambah row produk
  document.getElementById("addProductRow").addEventListener("click", function() {
    const wrapper = document.getElementById("productWrapper");
    const firstRow = wrapper.querySelector(".product-row");
    const newRow = firstRow.cloneNode(true);

    // Reset value
    newRow.querySelector(".product-select").selectedIndex = 0;
    newRow.querySelector(".quantity-input").value = "";
    newRow.querySelector(".total-input").value = "";

    // Hapus ID select2 lama supaya bisa di-init ulang
    $(newRow).find('.select2').removeClass("select2-hidden-accessible").next(".select2-container").remove();

    wrapper.appendChild(newRow);

    // Re-init select2 hanya untuk select di row baru
    initSelect2();
  });

  // Inisialisasi awal
  initSelect2();
</script>

<style>
/* Wrapper container select2 */
.select2-container--default .select2-selection--single {
  background-color: #fff;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  height: 42px;
  display: flex;
  align-items: center;
  padding: 6px 12px;
  font-size: 14px;
  font-weight: 500;
  color: #333;
  box-shadow: none;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

/* Hover + focus efek */
.select2-container--default .select2-selection--single:hover {
  border-color: #EB4F7D;
}

.select2-container--default.select2-container--open .select2-selection--single {
  border-color: #EB4F7D;
  box-shadow: 0 0 0 2px rgba(0,115,234,0.2);
}

/* Panah dropdown */
.select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 100%;
  right: 10px;
}

/* Placeholder text */
.select2-container--default .select2-selection--single .select2-selection__placeholder {
  color: #9ca3af;
  font-weight: 400;
}

/* Dropdown menu */
.select2-dropdown {
  border: 1px solid #d1d5db;
  border-radius: 8px;
  padding: 4px 0;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Option item */
.select2-container--default .select2-results__option {
  padding: 10px 14px;
  font-size: 14px;
  font-weight: 500;
  color: #333;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

/* Hover option */
.select2-container--default .select2-results__option--highlighted[aria-selected] {
  background-color: #EB4F7D;
  color: white;
  border-radius: 6px;
}

@media (max-width: 768px) {
  .product-row .col-md-6,
  .product-row .col-md-3 {
    width: 100% !important;
    max-width: 100%;
    flex: 0 0 100%;
    margin-bottom: 12px;
  }

  .product-row label {
    font-size: 13px;
    margin-bottom: 4px;
  }

  .product-row input,
  .product-row select,
  .select2-container--default .select2-selection--single {
    font-size: 14px;
    height: 46px !important; /* lebih tinggi agar enak diklik */
  }

  #addProductRow {
    width: 100%;
    margin-bottom: 15px;
  }

  #grandTotal {
    font-size: 16px;
    font-weight: 600;
  }

  .row.mt-4 .col-md-6.offset-md-3.text-center {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .row.mt-4 .col-md-6.offset-md-3.text-center button {
    width: 100%;
    padding: 12px;
    font-size: 15px;
  }
}

/* Extra small screens */
@media (max-width: 480px) {
  h2 {
    font-size: 18px !important;
    text-align: center;
  }

  .container-fluid.mt-5 {
    padding-left: 12px;
    padding-right: 12px;
  }
}
</style>

