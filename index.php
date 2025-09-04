<?php require_once 'inc/header.php'; ?>
<?php require_once 'inc/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="padding:20px; background-color:#f1f5f9; min-height:100vh;">
  <?php 
        if (isset($_GET['page'])) {
          $page ='pages/' .$_GET['page'].'.php';
        } else {
          $page = 'pages/dashboard.php';
        }
        if (file_exists($page)) {
          require_once $page; 
        } else {
          require_once 'pages/error_page.php';
        }
  ?>
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<?php require_once 'inc/footer.php'; ?>

<!-- Responsive Sidebar Auto Collapse -->

