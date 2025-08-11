<div class="content-wrapper" style="margin-top:75px; margin-bottom:75px; background-color: #f6f7fb;">
  <!-- Header -->
  <section class="content">
    <div class="container-fluid" style="margin-top: 40px;">
      <div style="
        background: white;
        border-radius: 16px;
        box-shadow: 0px 4px 20px rgba(0,0,0,0.05);
        padding: 24px;
      ">
        <h2 style="margin-bottom: 24px; font-size: 20px; font-weight: 600; color: #333;">↩️ Form Refund Produk</h2>
        
        <div class="alert alert-danger refundError-area" style="display:none;">
          <span id="refundError"></span>
        </div>

        <form id="refundForm">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="invoice_number" style="font-weight: 500;">No Invoice *</label>
              <input type="text" name="invoice_number" id="invoice_number" class="form-control" required placeholder="Masukkan nomor invoice" style="border-radius: 8px;">
            </div>
            <div class="col-md-6 mb-3">
              <label for="refund_quantity" style="font-weight: 500;">Jumlah Refund *</label>
              <input type="number" name="refund_quantity" id="refund_quantity" class="form-control" min="1" required style="border-radius: 8px;">
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="refund_reason" style="font-weight: 500;">Alasan Refund *</label>
              <textarea name="refund_reason" id="refund_reason" rows="3" class="form-control" placeholder="Masukkan alasan refund" required style="border-radius: 8px;"></textarea>
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
                onmouseout="this.style.backgroundColor='#f44336'"
              >Reset</button>

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
                onmouseout="this.style.backgroundColor='#0073ea'"
              >Submit Refund</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script>
  // Bisa ditambah validasi frontend disini jika perlu
  const refundForm = document.getElementById('refundForm');
  refundForm.addEventListener('submit', function(e) {
    const invoice = document.getElementById('invoice_number').value.trim();
    const qty = parseInt(document.getElementById('refund_quantity').value);
    const reason = document.getElementById('refund_reason').value.trim();

    if (!invoice || qty < 1 || !reason) {
      e.preventDefault();
      alert("Mohon isi semua field dengan benar.");
    }
  });
</script>
