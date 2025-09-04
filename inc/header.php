<?php
require_once 'app/init.php';

if ($Ouser->is_login() == false) {
  header("location:login.php");
  exit;
}

$actual_link = explode('=', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
$actual_link = end($actual_link);

$distributor_id = $_SESSION['distributor_id'] ?? null;
if ($distributor_id) {
    // Query suppliar berdasar distributor_id
    $stmt = $pdo->prepare("SELECT * FROM suppliar WHERE id = :id LIMIT 1");
    $stmt->bindValue(':id', $distributor_id, PDO::PARAM_INT);
    $stmt->execute();
    $suppliar = $stmt->fetch(PDO::FETCH_OBJ);
}

$userRole = $_SESSION['name'] ?? 'Guest';
$roleId = $_SESSION['role_id'] ?? 'User';

$roleNames = [
    1 => 'Head Office',
    2 => 'Head Distributor',
    3 => 'Distributor',
    4 => 'Agent',
    5 => 'Reseller',
   10 => 'Super Admin'
];
$roleName = $roleNames[$roleId] ?? 'User';

/**
 * Fungsi breadcrumb
 */
function makeBreadcrumb($actual_link) {
    $formatLabel = function($txt) {
        return ucwords(str_replace('_', ' ', $txt));
    };

    if (strpos($actual_link, '_add') !== false) {
        $base = str_replace('_add', '', $actual_link);
        return $formatLabel($base) . ' > Add';
    }
    return $formatLabel($actual_link);
}

$breadcrumbText = makeBreadcrumb($actual_link);

/**
 * Fungsi hitung total point dari transaction_histories
 */
function getTotalPoints($suppliar_id) {
    global $pdo;

    $sql = "
        SELECT 
            COALESCE(SUM(CASE WHEN is_refund = 0 THEN quantity ELSE 0 END), 0) AS total_penjualan,
            COALESCE(SUM(CASE WHEN is_refund = 1 THEN quantity ELSE 0 END), 0) AS total_refund
        FROM transaction_histories
        WHERE suppliar_id = :suppliar_id
          AND type = 'penjualan'
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':suppliar_id' => $suppliar_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalPoints = ($row['total_penjualan'] ?? 0) - ($row['total_refund'] ?? 0);
    return max(0, $totalPoints);
}

// Hitung total point suppliar login saat ini
$totalPoint = $distributor_id ? getTotalPoints($distributor_id) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge" />

  <title>Ladies V</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
  <!-- Google Fonts -->
   <link rel="stylesheet" href="assets/css/responsive.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet" />

  <!-- DataTables -->
  <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
  <!-- Bootstrap Datepicker CSS -->
  <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
  <!-- Select2 CSS -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css" />
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css" />
  <!-- Date Range Picker -->
  <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />

  <!-- AdminLTE Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css" />

  <!-- Material Symbols -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

  <style>
    body,
    html {
      font-family: 'Plus Jakarta Sans', sans-serif;
      margin: 0;
      padding: 0;
    }
    .navbar-custom {
      background-color: #fff !important;
      border-bottom: 1px solid #e5e7eb;
      padding: 0 20px;
      height: 56px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      user-select: none;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1040;
    }
    .nav-left,
    .nav-right {
      display: flex;
      align-items: center;
      list-style: none;
      margin: 0;
      padding: 0;
    }
    .nav-link {
      font-size: 20px;
      color: #4b5563;
      display: flex;
      align-items: center;
      padding: 8px;
      border-radius: 8px;
      transition: background-color 0.3s ease, color 0.3s ease;
      text-decoration: none;
      cursor: pointer;
    }
    .nav-link:hover {
      background-color: #f3f4f6;
      color: #2563eb;
    }
    .user-info {
      text-align: right;
      min-width: 140px;
      margin-right: 16px;
      color: #343a40;
      font-weight: 600;
      font-size: 15px;
      user-select: none;
    }
    .user-role {
      font-size: 13px;
      color: #6c757d;
      font-weight: normal;
      margin-top: 2px;
    }
    .profile-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid #007bff;
      background-color: #e9f2ff;
      color: #007bff;
      font-size: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background-color 0.3s ease, color 0.3s ease;
      cursor: pointer;
      user-select: none;
      text-decoration: none;
      position: relative;
    }
    .profile-icon:hover {
      background-color: #d0e4ff;
    }
    .dropdown-menu-custom {
      position: absolute;
      top: 56px;
      right: 0;
      min-width: 180px;
      background-color: #fff;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
      border-radius: 12px;
      z-index: 1050;
      display: none;
      flex-direction: column;
    }
    .dropdown-item-custom {
      display: flex;
      align-items: center;
      padding: 10px 16px;
      color: #2563eb;
      font-weight: 600;
      font-size: 15px;
      text-decoration: none;
      gap: 8px;
      border-radius: 8px;
      transition: background-color 0.25s ease;
    }
    .dropdown-item-custom:hover {
      background-color: #e0e7ff;
      color: #1e40af;
    }
    .dropdown-item-logout {
      color: #dc2626 !important;
    }
    .dropdown-item-logout:hover {
      background-color: #fee2e2 !important;
      color: #991b1b !important;
    }
    .dropdown-divider-custom {
      height: 1px;
      background-color: #e5e7eb;
      margin: 6px 0;
    }

    /* Search box */
.dataTables_wrapper .dataTables_filter {
    text-align: right;
    margin-bottom: 12px;
}
.dataTables_wrapper .dataTables_filter input {
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 12px;
    outline: none;
    width: 180px;
    transition: all 0.2s;
}
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 6px rgba(37, 99, 235, 0.3);
}

/* Show entries */
.dataTables_wrapper .dataTables_length {
    margin-bottom: 12px;
}
.dataTables_wrapper .dataTables_length select {
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 12px;
    outline: none;
    min-width: 60px;
}

/* Pagination */
.dataTables_wrapper .dataTables_paginate {
    margin-top: 12px;
    text-align: center;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 5px 12px;
    margin: 0 2px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background-color: #fff;
    color: #374151;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #2563eb;
    color: #fff !important;
    border-color: #2563eb;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #e0e7ff;
    color: #2563eb !important;
    border-color: #2563eb;
}

  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-white navbar-light navbar-custom" role="navigation" aria-label="Main navigation">
      <!-- Left navbar links -->
      <ul class="nav-left navbar-nav" role="menubar">
        <li class="nav-item" role="none">
          <a class="nav-link" data-widget="pushmenu" href="#" id="menuToggle" role="menuitem" aria-haspopup="true" aria-expanded="false" aria-label="Toggle sidebar menu">
            <i class="fas fa-bars" aria-hidden="true"></i>
          </a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="nav-right navbar-nav" role="menubar">
        <li class="user-info" role="none">
          <div><?php echo htmlspecialchars($userRole); ?></div>
          <div class="user-role">
            <b>Total Point: <?php echo htmlspecialchars($totalPoint); ?></b>
            &nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($roleName); ?>
          </div>
        </li>
        <li class="nav-item dropdown" style="position: relative;" role="none">
          <a href="#" id="profileDropdown" class="profile-icon" role="menuitem" aria-haspopup="true" aria-expanded="false" aria-label="User menu">
            <span class="material-symbols-outlined" aria-hidden="true" style="font-size: 24px; line-height: 1;">account_circle</span>
          </a>

          <div id="profileDropdownMenu" class="dropdown-menu-custom" aria-labelledby="profileDropdown" role="menu">
            <a href="index.php?page=suppliar_edit&&edit_id=<?= $_SESSION['distributor_id'] ?>" class="dropdown-item-custom" role="menuitem" tabindex="-1">
              <i class="material-symbols-outlined" aria-hidden="true" style="font-size: 20px;">person</i> Profile
            </a>
            <div class="dropdown-divider-custom" role="separator"></div>
            <a href="app/action/logout.php" class="dropdown-item-custom dropdown-item-logout" role="menuitem" tabindex="-1">
              <i class="material-symbols-outlined" aria-hidden="true" style="font-size: 20px;">logout</i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const profileDropdown = document.getElementById("profileDropdown");
        const profileMenu = document.getElementById("profileDropdownMenu");

        profileDropdown.addEventListener("click", function (e) {
          e.preventDefault();
          const isShown = profileMenu.style.display === "flex";
          profileMenu.style.display = isShown ? "none" : "flex";
          profileDropdown.setAttribute("aria-expanded", !isShown);
        });

        document.addEventListener("click", function (e) {
          if (!profileDropdown.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.style.display = "none";
            profileDropdown.setAttribute("aria-expanded", "false");
          }
        });
      });
    </script>
    <script>
document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.getElementById("sidebar");
  const toggleBtn = document.querySelector("[data-widget='pushmenu']");

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener("click", (e) => {
      e.preventDefault();
      sidebar.classList.toggle("active");
    });
  }
});
</script>
  </div>
</body>
</html>
