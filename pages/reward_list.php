<div class="reward-wrapper">
  <section class="content">
    <div class="container-fluid">

      <!-- ===== Header ===== -->
      <div class="reward-header">
        <h2>📋 DAFTAR HADIAH</h2>
        <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
          <a href="index.php?page=add_reward" class="btn-add-reward">
            <i class="fas fa-plus"></i> Tambah Reward
          </a>
        <?php endif; ?>
      </div>

      <!-- ===== Tab Menu ===== -->
      <div class="reward-tab-wrapper">
        <?php
        $loginRole = $_SESSION['role_id'];
        if ($loginRole == 10 || $loginRole == 1): ?>
          <button class="reward-tab active" data-role="2">Head Distributor</button>
          <button class="reward-tab" data-role="3">Distributor</button>
          <button class="reward-tab" data-role="4">Agen</button>
          <button class="reward-tab" data-role="5">Reseller</button>
        <?php else: ?>
          <?php if ($loginRole == 2): ?>
            <button class="reward-tab active" data-role="2">Head Distributor</button>
          <?php elseif ($loginRole == 3): ?>
            <button class="reward-tab active" data-role="3">Distributor</button>
          <?php elseif ($loginRole == 4): ?>
            <button class="reward-tab active" data-role="4">Agen</button>
          <?php elseif ($loginRole == 5): ?>
            <button class="reward-tab active" data-role="5">Reseller</button>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <!-- ===== Table Reward ===== -->
      <div class="table-responsive">
        <table id="rewardListPlain" class="display dataTable text-center reward-table">
          <thead>
          
          <tbody id="reward_list_res">
            <tr>
              <td colspan="7" class="no-data">Pilih tab untuk melihat data.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ===== Riwayat Penukaran ===== -->
      <div class="mt-5">
        <h3 class="riwayat-title">🕑 Riwayat Penukaran</h3>
        <div class="table-responsive">
          <table id="redeemHistoryTable" class="riwayat-table">
            <thead>
              <tr>
                <th>Event</th>
                <th>Reward</th>
                <th>Qty</th>
                <th>Point/Unit</th>
                <th>Total Point</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <!-- data diisi AJAX -->
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
.reward-wrapper {
  padding-top: 52px;
  background-color: #fff;
  padding-left: 12px;
  padding-right: 12px;
}
.reward-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 28px;
  flex-wrap: wrap;
}
.reward-header h2 {
  font-size: 24px;
  font-weight: 600;
  color: #222;
  margin: 0;
  user-select: none;
}
.btn-add-reward {
  background-color: #EB4F7D;
  color: #fff;
  border-radius: 12px;
  padding: 8px 20px;
  font-weight: 600;
  font-size: 12px;
  text-decoration: none;
  border: none;
  box-shadow: 0 4px 12px rgba(0,115,234,0.4);
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}
.btn-add-reward:hover {
  background-color: #d92765;
  box-shadow: 0 6px 18px rgba(0,91,181,0.5);
}

/* TAB */
.reward-tab-wrapper {
  display: flex;
  gap: 8px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}
.reward-tab {
  background: #fff;
  border: 1px solid #ddd;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 13px;
  cursor: pointer;
  font-weight: 500;
  color: #333;
  transition: all 0.2s ease-in-out;
}
.reward-tab:hover {
  background: #f0f4ff;
  border-color: #a3c4f3;
}
.reward-tab.active {
  background: #d92765;
  color: #fff;
  border-color: #d92765;
}

/* TABLE REWARD */
.reward-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 8px;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 18px rgba(0,0,0,0.07);
  background: #fff;
  font-size: 12px !important;
  min-width: 700px;
}
.reward-table thead {
  background-color: #EEA0A0;
  color: #fff;
  text-transform: uppercase;
  font-weight: 600;
  letter-spacing: 0.03em;
  font-size: 10.5px; /* lebih kecil */
}
.reward-table thead th {
  padding: 12px 8px;
  font-size:12px !important;
}
.reward-table tbody tr {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
.reward-table tbody tr:hover {
  background-color: #eff6ff;
  box-shadow: 0 6px 20px rgba(0, 115, 234, 0.15);
}
.reward-table tbody td {
  padding: 12px 8px;
  vertical-align: middle;
}
.no-data {
  text-align: center;
  color: #9ca3af;
  font-style: italic;
}

/* ======= RIWAYAT ======= */
.riwayat-title {
  font-size: 18px;
  font-weight: 600;
  color: #333;
  margin-bottom: 15px;
}
.riwayat-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  box-shadow: 0 2px 12px rgba(0,0,0,0.05);
  border-radius: 10px;
  overflow: hidden;
  font-size: 12px;
}
.riwayat-table thead {
  background: #f7f7f9;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  font-weight: 600;
}
.riwayat-table thead th {
  padding: 10px 8px;
  border-bottom: 1px solid #e5e7eb;
}
.riwayat-table tbody tr {
  border-bottom: 1px solid #f0f0f0;
  transition: background 0.2s ease;
}
.riwayat-table tbody tr:hover {
  background: #f9fafb;
}
.riwayat-table tbody td {
  padding: 10px 8px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .reward-header h2 {
    font-size: 20px;
  }
  .btn-add-reward {
    font-size: 11px;
    padding: 6px 14px;
  }
  .reward-table,
  .riwayat-table {
    font-size: 11px !important;
    min-width: 600px;
  }
}
</style>

<script>
$(function () {
  function loadRedeemHistory(){
    $.ajax({
        url: "app/ajax/redeem_history.php",
        type: "GET",
        dataType: "json",
        success: function(res){
            const tbody = $("#redeemHistoryTable tbody");
            tbody.empty();
            if(res.length === 0){
                tbody.append(`<tr><td colspan="6" class="text-center">Belum ada riwayat</td></tr>`);
            } else {
                res.forEach(row=>{
                    tbody.append(`
                        <tr>
                            <td>${row.event_name}</td>
                            <td>${row.nama_reward}</td>
                            <td>${row.qty_redeem}</td>
                            <td>${row.point_per_unit}</td>
                            <td>${row.total_point}</td>
                            <td>${row.created_at}</td>
                        </tr>
                    `);
                });
            }
        },
        error: function(err){
            console.error("Load history error:", err);
        }
    });
  }

  function loadRewardList(role_id) {
    $.ajax({
      url: 'app/ajax/reward_list_data.php',
      method: 'POST',
      data: { role_id: role_id },
      cache: false,
      beforeSend: function () {
        $("#reward_list_res").html('<tr><td colspan="7" class="no-data">Loading...</td></tr>');
      },
      success: function (html) {
        $("#reward_list_res").html(html);
      },
      error: function (xhr) {
        $("#reward_list_res").html(
          '<tr><td colspan="7" class="no-data">Error: ' + (xhr.statusText || 'Gagal memuat') + '</td></tr>'
        );
      }
    });
  }

  // Tab click
  $(document).on('click', '.reward-tab', function () {
    $(".reward-tab").removeClass("active");
    $(this).addClass("active");
    loadRewardList($(this).data("role"));
    loadRedeemHistory();
  });

  // Toggle Event Rows
  $(document).on('click', '.toggle-event', function () {
    const target = $(this).data('target');
    const $rows  = $('.' + target);
    const isOpen = $rows.is(':visible');
    $rows.toggle(!isOpen);
    const $firstCell = $(this).find('td').first();
    const text = $firstCell.text().replace(/^.\s/, '');
    $firstCell.text((isOpen ? '▶ ' : '▼ ') + text);
  });

  // Load default
  var defaultRole = $('.reward-tab.active').data('role') || 'all';
  loadRewardList(defaultRole);
  loadRedeemHistory();
});
</script>
