<div class="content-wrapper">
  <!-- Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <h1 class="m-0 text-dark">Penjualan</h1>
        </div>
        <div class="col-md-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Penjualan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main -->
  <section class="content">
    <div
      style="padding: 32px; margin: 0 auto; background: #ffffff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
      <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 24px; color: #1f2937;">Form Penjualan</h2>

      <div id="saleErrorArea"
        style="display:none; color: #b91c1c; margin-bottom: 16px; background-color: #fee2e2; padding: 12px; border-radius: 8px;">
      </div>

      <form id="salesForm">
        <div style="display: flex; flex-wrap: wrap; gap: 24px;">

          <!-- Produk -->
          <div style="flex: 1 1 280px;">
            <label style="font-weight: 500; display: block; margin-bottom: 8px;">Produk *</label>
            <select id="product_id" name="product_id" required
              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
              <option disabled selected>Pilih produk</option>
              <?php 
                $role_id = $_SESSION['role_id'] ?? null;

                $products = $obj->all('products');
                foreach ($products as $product) {
    // Tentukan harga berdasarkan role_id
    switch ($role_id) {
        case 1:
            $price = $product->sell_price_hd;
            break;
        case 2:
            $price = $product->sell_price_hd;
            break;
        case 3:
            $price = $product->sell_price_d;
            break;
        case 4:
            $price = $product->sell_price_r;
            break;
        default:
            $price = $product->sell_price_hd; // fallback kalau role_id tidak dikenali
            break;
    }

    echo "<option value='{$product->id}' data-price='{$price}'>{$product->product_name}</option>";
}
?>
            </select>
          </div>

          <!-- Kuantitas -->
          <div style="flex: 1 1 280px;">
            <label style="font-weight: 500; display: block; margin-bottom: 8px;">Kuantitas *</label>
            <input type="number" id="quantity" name="quantity" required min="1" placeholder="Masukkan jumlah"
              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
          </div>

          <!-- Pilih Agen -->
          <div style="flex: 1 1 280px;">
            <label style="font-weight: 500; display: block; margin-bottom: 8px;">Pilih Anggota</label>
            <select id="customer_name"
              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;" required>
              <option value="">Pilih Anggota</option>
              <option value="0" data-name="penjualan pribadi">Penjualan Pribadi</option>
              <?php 
              $role_labels = [
    1 => 'HO',
    2 => 'HD',
    3 => 'D',
    4 => 'A',
    5 => 'R'
];
                $distributor_id = $_SESSION['distributor_id'];
                $role_id = $_SESSION['role_id'];
                $all_supplier = $obj->allCondition('suppliar', 'role_id > ? AND id != ?', [$role_id, $distributor_id]);

                foreach ($all_supplier as $customer) {
                    $role_text = $role_labels[$customer->role_id] ?? $customer->role_id;
                    echo "<option value='{$customer->id}' data-name='{$customer->name}'>{$customer->name} - {$customer->suppliar_code} - {$role_text}</option>";
                }
            ?>
            </select>
          </div>

          <!-- Nama Manual -->
          <div style="flex: 1 1 280px;">
            <label style="font-weight: 500; display: block; margin-bottom: 8px;">Atau Ketik Nama Pembeli *</label>
            <input type="text" id="buyer" name="buyer" placeholder="Nama Pembeli"
              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
          </div>

          <!-- Total Bayar -->
          <div style="flex: 1 1 280px;">
            <label style="font-weight: 500; display: block; margin-bottom: 8px;">Total Pembayaran</label>
            <input type="number" id="total_payment" name="total_payment" readonly placeholder="Otomatis terisi"
              style="width: 100%; padding: 12px; background-color: #f9fafb; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
          </div>
        </div>

        <!-- Tombol -->
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
  function getRoleByID() {

  }
  const productSelect = document.getElementById('product_id');
  const quantityInput = document.getElementById('quantity');
  const paymentInput = document.getElementById('total_payment');
  const buyerDropdown = document.getElementById('customer_name');
  const buyerManual = document.getElementById('buyer');
  const errorArea = document.getElementById('saleErrorArea');
  const salesForm = document.getElementById('salesForm');

  function updateTotalPayment() {
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    const quantity = parseInt(quantityInput.value) || 0;
    const total = price * quantity;
    paymentInput.value = total.toFixed(2);
  }

  productSelect.addEventListener('change', updateTotalPayment);
  quantityInput.addEventListener('input', updateTotalPayment);

  salesForm.addEventListener('submit', function (e) {
    const dropdownValue = buyerDropdown.value;
    const manualValue = buyerManual.value.trim();

    if ((!dropdownValue || dropdownValue === "0") && manualValue === "") {
      e.preventDefault();
      errorArea.style.display = 'block';
      errorArea.innerText = 'Silakan pilih pembeli dari daftar ATAU ketik nama pembeli secara manual.';
    } else {
      errorArea.style.display = 'none';
    }
  });

  buyerDropdown.addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    const name = selected.getAttribute('data-name') || '';

    if (this.value !== "0") {
      buyerManual.value = name;
    } else  {
      buyerManual.value = 'Penjualan Pribadi';
    }

    errorArea.style.display = 'none';
  });

  buyerManual.addEventListener('input', () => errorArea.style.display = 'none');
</script>