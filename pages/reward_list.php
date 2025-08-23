<div style="padding-top:52px; background-color: #f6f7fb;">
  <section class="content">
    <div class="container-fluid">

      <!-- Header -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <h2 style="margin: 0; font-size: 26px; font-weight: 600; color: #222; user-select:none;">
          📋 DAFTAR HADIAH
        </h2>
        <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 10): ?>
           <a href="index.php?page=add_reward" 
            style="
              background-color: #0073ea;
              color: #fff;
              border-radius: 12px;
              padding: 8px 20px;
              font-weight: 600;
              font-size: 12px;
              text-decoration: none;
              border: none;
              box-shadow: 0 4px 12px rgba(0,115,234,0.4);
              transition: background-color 0.3s ease, box-shadow 0.3s ease;
              display: flex;
              align-items: center;
              gap: 8px;
              user-select:none;
            "
            onmouseover="this.style.backgroundColor='#005bb5'; this.style.boxShadow='0 6px 18px rgba(0,91,181,0.5)'"
            onmouseout="this.style.backgroundColor='#0073ea'; this.style.boxShadow='0 4px 12px rgba(0,115,234,0.4)'">
            <i class="fas fa-plus" style="font-size: 16px;"></i> Tambah Reward
          </a>
          <?php endif; ?>
      </div>

      <!-- TAB MENU -->
     <!-- TAB MENU -->
<div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
  <?php if ($_SESSION['role_id'] == 10 || $_SESSION['role_id'] == 1): ?>
    <button class="reward-tab active" data-role="all">Semua Role</button>
    <button class="reward-tab" data-role="2">Head Distributor</button>
    <button class="reward-tab" data-role="3">Distributor</button>
    <button class="reward-tab" data-role="4">Agen</button>
    <button class="reward-tab" data-role="5">Reseller</button>
  <?php else: ?>
    <?php if ($_SESSION['role_id'] == 2): ?>
      <button class="reward-tab active" data-role="2">Head Distributor</button>
    <?php elseif ($_SESSION['role_id'] == 3): ?>
      <button class="reward-tab active" data-role="3">Distributor</button>
    <?php elseif ($_SESSION['role_id'] == 4): ?>
      <button class="reward-tab active" data-role="4">Agen</button>
    <?php elseif ($_SESSION['role_id'] == 5): ?>
      <button class="reward-tab active" data-role="5">Reseller</button>
    <?php endif; ?>
  <?php endif; ?>
</div>


      <!-- Table -->
      <div class="table-responsive" style="overflow-x:auto;">
        <table id="rewardListPlain" class="display dataTable text-center" style="
          width: 100%;
          border-collapse: separate;
          border-spacing: 0 10px;
          border-radius: 12px;
          overflow: hidden;
          box-shadow: 0 4px 18px rgba(0,0,0,0.07);
          background: #fff;
          font-size: 12px !important;
          min-width: 900px;
        ">
          <thead style="background-color: #2563eb; color: #fff; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; font-size: 11px;">
            <tr>
              <th style="padding: 16px 20px; border-bottom: none; border-top-left-radius: 12px; font-size: 11px;">No</th>
              <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Nama Hadiah</th>
              <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Periode Penjualan</th>
              <th style="padding: 16px 20px; border-bottom: none; font-size: 11px;">Role</th>
              <th style="padding: 16px 20px; border-bottom: none; font-size: 11px; border-top-right-radius: 12px;">Total Point</th>
            </tr>
          </thead>
          <tbody id="reward_list_res" style="color: #374151; font-weight: 500;">
            <tr>
              <td colspan="5" class="no-data">Pilih tab untuk melihat data.</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </section>
</div>

<style>
  /* Tab style */
  .reward-tab {
    background: #fff;
    border: 1px solid #ddd;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    font-weight: 500;
    color: #333;
  }
  .reward-tab:hover {
    background: #f0f4ff;
    border-color: #a3c4f3;
  }
  .reward-tab.active {
    background: #006eff;
    color: #fff;
    border-color: #006eff;
    box-shadow: 0 3px 6px rgba(0, 110, 255, 0.2);
  }
  .no-data {
    text-align: center;
    color: #9ca3af;
    font-style: italic;
  }

  /* Table row style */
  #rewardListPlain tbody tr {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }
  #rewardListPlain tbody tr:hover {
    background-color: #eff6ff;
    box-shadow: 0 6px 20px rgba(0, 115, 234, 0.15);
  }
  #rewardListPlain tbody tr td {
    padding: 14px 22px;
    vertical-align: middle;
  }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
  function loadRewardList(role_id) {
    $.ajax({
      url: 'app/ajax/reward_list_data.php',
      method: 'POST',
      data: { role_id: role_id },
      cache: false,
      beforeSend: function () {
        $("#reward_list_res").html('<tr><td colspan="5" class="no-data">Loading...</td></tr>');
      },
      success: function (html) {
        $("#reward_list_res").html(html);
      },
      error: function (xhr) {
        $("#reward_list_res").html(
          '<tr><td colspan="5" class="no-data">Error: ' + (xhr.statusText || 'Gagal memuat') + '</td></tr>'
        );
      }
    });
  }

  // Tab click
  $(document).on('click', '.reward-tab', function () {
    $(".reward-tab").removeClass("active");
    $(this).addClass("active");
    loadRewardList($(this).data("role"));
  });

  // Load default
  var defaultRole = $('.reward-tab.active').data('role') || 'all';
  loadRewardList(defaultRole);
});
</script>
