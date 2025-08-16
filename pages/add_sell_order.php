<?php 
// Ambil data produk di sini sekali saja
$products = $obj->all('products');
?>

<div>
  <!-- Main -->
  <section class="content">
    <div class="mt-5">
      <h2 class="mb-4" style="font-size: 20px; font-weight: 600; color: #333;">📦 Form Penjualan</h2>

      <div id="saleErrorArea"
        style="display:none; color: #b91c1c; margin-bottom: 16px; background-color: #fee2e2; padding: 12px; border-radius: 8px;">
      </div>

      <form id="salesForm">
        <!-- Anggota -->
        <div class="row gx-4 gy-3 mb-4" style="margin-bottom:20px;row-gap:25px;">
          <div class="col-md-6">
            <label for="customer_name" style="font-weight: 500; display: block; margin-bottom: 8px;">Anggota</label>
            <select id="customer_name" name="customer_name" required
              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
              <option value="">Pilih Anggota</option>
              <option value="0" data-name="Penjualan Pribadi" data-role="0">Penjualan Pribadi</option>
              <?php 
                $role_labels = [1 => 'HO', 2 => 'HD', 3 => 'D', 4 => 'A', 5 => 'R'];
                $distributor_id = $_SESSION['distributor_id'];
                $role_id = $_SESSION['role_id'];
                $all_supplier = $obj->allCondition('suppliar', 'role_id > ? AND id != ?', [$role_id, $distributor_id]);
                foreach ($all_supplier as $customer) {
                    $role_text = $role_labels[$customer->role_id] ?? $customer->role_id;
                    echo "<option value='{$customer->id}' 
                              data-name='{$customer->name}' 
                              data-role='{$customer->role_id}'>
                              {$customer->name} - {$customer->suppliar_code} - {$role_text}
                          </option>";
                }
              ?>
            </select>
          </div>

          <div class="col-md-6">
            <label for="buyer" style="font-weight: 500; display: block; margin-bottom: 8px;">Nama Customer</label>
            <input type="text" id="buyer" name="buyer" placeholder="Nama Customer" readonly
              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
          </div>
        </div>
        <button type="button" id="addProductBtn"
          style="background-color: #0073ea;
               color: #fff;
               border-radius: 12px;
               padding: 8px 20px;
               font-weight: 600;
               font-size: 12px;
               border: none;
               box-shadow: 0 4px 12px rgba(0,115,234,0.4);
               transition: background-color 0.3s ease, box-shadow 0.3s ease;
               display: flex;
               align-items: center;
               gap: 8px;
               user-select:none;">
          + Tambah Produk
        </button>
        <!-- Container untuk produk -->
        <div id="productRows" style="margin-bottom: 20px;margin-top:20px;"></div>
        <div class="row">
          <div class="col-md-6">
            <label for="total_payment" style="font-weight: 500; display: block; margin-bottom: 8px;">Total
              Pembayaran</label>
            <input type="number" id="total_payment" name="total_payment" readonly placeholder="Otomatis terisi"
              style="width: 100%; padding: 12px; background-color: #f9fafb; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
          </div>
        </div>

        <div style="text-align: center; margin-top: 32px;">
          <button type="reset"
            style="background-color: #ef4444; color: white; padding: 12px 24px; border: none; border-radius: 10px; margin-right: 12px; cursor: pointer; font-weight: 500;">
            Reset
          </button>
          <button type="submit"
            style="background-color: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 10px; cursor: pointer; font-weight: 500;">
            Submit Penjualan
          </button>
        </div>
      </form>
    </div>
  </section>
</div>

<script>
  // Kirim PHP array ke JS
  const productsData = <?php echo json_encode($products); ?>;

  const buyerDropdown = document.getElementById('customer_name');
  const buyerManual = document.getElementById('buyer');
  const totalPaymentInput = document.getElementById('total_payment');
  const productRowsContainer = document.getElementById('productRows');

  function createProductRow() {
    const row = document.createElement('div');
    row.classList.add('row', 'gx-4', 'gy-3', 'product-row');
    row.style.marginBottom = '15px';

    let options = '<option disabled selected>Pilih produk</option>';
    productsData.forEach(p => {
      options += `<option value="${p.id}" 
      data-price-hd="${p.sell_price_hd}" 
      data-price-d="${p.sell_price_d}" 
      data-price-a="${p.sell_price_a}" 
      data-price-r="${p.sell_price_r}">
      ${p.product_name}
    </option>`;
    });

    row.innerHTML = `
    <div class="col-md-6">
      <label>Produk *</label>
      <select name="product_id[]" class="product-select" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ccc;">
        ${options}
      </select>
    </div>
    <div class="col-md-4">
      <label>Kuantitas *</label>
      <input type="number" name="quantity[]" class="quantity-input" min="1" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ccc;">
    </div>
    <div class="col-md-2" style="display:flex;align-items:end;">
      <button type="button" class="remove-row" style="background:#ef4444;color:white;border:none;border-radius:6px;padding:6px 10px;cursor:pointer;">Hapus</button>
    </div>
  `;

    // Tombol hapus baris
    row.querySelector('.remove-row').addEventListener('click', () => {
      row.remove();
      updateTotalPayment();
    });

    // Event perubahan produk → cek duplikat
    row.querySelector('.product-select').addEventListener('change', function () {
      const selectedValue = this.value;
      let duplicate = false;

      document.querySelectorAll('.product-select').forEach(select => {
        if (select !== this && select.value === selectedValue && selectedValue !== "") {
          duplicate = true;
        }
      });

      if (duplicate) {
        alert('⚠️ Produk ini sudah dipilih di baris lain. Silakan pilih produk lain.');
        this.selectedIndex = 0; // Reset pilihan
      }

      updateTotalPayment();
    });

    // Event perubahan qty
    row.querySelector('.quantity-input').addEventListener('input', updateTotalPayment);

    productRowsContainer.appendChild(row);
  }

  function getPriceByRole(option, roleId) {
    switch (parseInt(roleId)) {
      case 1:
      case 2:
        return parseFloat(option.getAttribute('data-price-hd')) || 0;
      case 3:
        return parseFloat(option.getAttribute('data-price-d')) || 0;
      case 4:
        return parseFloat(option.getAttribute('data-price-a')) || 0;
      case 5:
        return parseFloat(option.getAttribute('data-price-r')) || 0;
      default:
        return 0;
    }
  }

  function updateTotalPayment() {
    const selectedBuyer = buyerDropdown.options[buyerDropdown.selectedIndex];
    const roleId = selectedBuyer ? selectedBuyer.getAttribute('data-role') : null;
    let total = 0;

    document.querySelectorAll('.product-row').forEach(row => {
      const prodSelect = row.querySelector('.product-select');
      const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
      if (prodSelect && prodSelect.value && qty) {
        const price = getPriceByRole(prodSelect.options[prodSelect.selectedIndex], roleId);
        total += price * qty;
      }
    });

    totalPaymentInput.value = total.toFixed(2);
  }

  document.getElementById('addProductBtn').addEventListener('click', createProductRow);

  buyerDropdown.addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    buyerManual.value = (this.value !== "0") ? (selected.getAttribute('data-name') || '') : 'Penjualan Pribadi';
    updateTotalPayment();
  });

  // Baris pertama otomatis dibuat
  createProductRow();
</script>