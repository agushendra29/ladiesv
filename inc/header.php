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

$userRole = $_SESSION['user_role'] ?? 'Guest';
$roleId = $_SESSION['role_id'] ?? 'User';

$roleNames = [
    1 => 'Head Office',
    2 => 'Head Distributor',
    3 => 'Distributor',
    4 => 'Agent',
    5 => 'Reseller'
];
$roleName = $roleNames[$roleId] ?? 'User';
?>

<?php
function makeBreadcrumb($actual_link) {
    // Ubah underscore jadi spasi & kapital tiap kata
    $formatLabel = function($txt) {
        return ucwords(str_replace('_', ' ', $txt));
    };

    if (strpos($actual_link, '_add') !== false) {
        // contoh: purchase_order_add → Purchase Order > Add
        $base = str_replace('_add', '', $actual_link);
        return $formatLabel($base) . ' > Add';
    }

    // default
    return $formatLabel($actual_link);
}

$breadcrumbText = makeBreadcrumb($actual_link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />

  <title>Ladies V</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
  <!-- Google Fonts -->
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

    /* Navbar Container */
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
      left: 260px; /* adjust to sidebar width */
      right: 0;
      z-index: 1040;
    }

    /* Navigation Lists */
    .nav-left,
    .nav-right {
      display: flex;
      align-items: center;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    /* Sidebar Toggle Button */
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

    /* User Info Section */
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

    /* Profile Icon */
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

    /* Dropdown Menu */
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

    /* Dropdown Items */
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

    /* Logout Item */
    .dropdown-item-logout {
      color: #dc2626 !important;
    }

    .dropdown-item-logout:hover {
      background-color: #fee2e2 !important;
      color: #991b1b !important;
    }

    /* Divider */
    .dropdown-divider-custom {
      height: 1px;
      background-color: #e5e7eb;
      margin: 6px 0;
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
          <div class="user-role"><b>Total Point:<?php echo htmlspecialchars($suppliar->total_point); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($roleName); ?></div>
           <div class="user-role"></div>
        </li>
        <li class="nav-item dropdown" style="position: relative;" role="none">
          <a href="#" id="profileDropdown" class="profile-icon" role="menuitem" aria-haspopup="true" aria-expanded="false" aria-label="User menu">
            <span class="material-symbols-outlined" aria-hidden="true" style="font-size: 24px; line-height: 1;">account_circle</span>
          </a>

          <div id="profileDropdownMenu" class="dropdown-menu-custom" aria-labelledby="profileDropdown" role="menu">
            <a href="index.php?page=profile" class="dropdown-item-custom" role="menuitem" tabindex="-1">
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
  </div>
</body>

</html>
