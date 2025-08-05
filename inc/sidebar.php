
<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar ">

    <!-- Brand Logo -->
    <a href="https://mayurik.com" class="brand-link">
      <img src="assets/images/logo.png" alt="logo" class="brand-image ">
     
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            <?php 
               $login_user = $_SESSION['user_id'];
               $login_user = $obj->find('user','id',  $login_user);
               echo $login_user->username;
             ?>
          </a>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a style="font-size:14px !important;" href="index.php?page=dashboard" class="nav-link <?php echo $actual_link=='dashboard'?'active':'';?>">
              <i class="material-symbols-outlined" style="font-size:20px !important;">dashboard</i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          
           <li class="nav-item">
            <a href="index.php?page=suppliar" style="font-size:14px !important;" class="nav-link <?php echo $actual_link=='suppliar'?'active':'';?>">
              <i class="material-symbols-outlined">group</i>
              <p>
                Member
              </p>
            </a>
          </li>
        
          <li class="nav-item has-treeview">
            <a style="font-size:14px !important;" href="#" class="nav-link <?php 
              if ($actual_link == 'add_product' || $actual_link =='product_list') {echo "active";
          }else{
            echo "";
          }
            ?>">
              <i class="material-symbols-outlined">inventory</i><p>
                Produk
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="index.php?page=product_list" style="font-size:14px !important;" class="nav-link <?php echo $actual_link=='product_list'?'active':'';?>">
                  <!-- <i class="fas fa-align-justify nav-icon"></i> -->
                  <p>Daftar Produk</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="index.php?page=stock_management" style="font-size:14px !important;" class="nav-link <?php echo $actual_link=='stock_management'?'active':'';?>">
                  <!-- <i class="fas fa-align-justify nav-icon"></i> -->
                  <p>Stok Produk</p>
                </a>
              </li>
             </ul>
          </li>

          <li class="nav-item has-treeview">
            <a style="font-size:14px !important;" href="#" class="nav-link <?php 
              if ($actual_link == 'quick_sell' || $actual_link =='sell_list' || $actual_link =='sell_return_list') {echo "active";
          }else{
            echo "";
          }
            ?>">
              <i class="material-symbols-outlined">sell</i>
              <p>
                Penjualan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a style="font-size:14px !important;" href="index.php?page=sell_list" class="nav-link <?php echo $actual_link=='sell_list'?'active':'';?>">
                  <!-- <i class="fas fa-align-justify nav-icon"></i> -->
                  <p>Penjualan</p>
                </a>
              </li>
              <li class="nav-item">
                <a style="font-size:14px !important;" href="index.php?page=sell_return_list" class="nav-link <?php echo $actual_link=='sell_return_list'?'active':'';?>">
                  <!-- <i class="fas fa-align-justify nav-icon"></i> -->
                  <p>Retur Penjualan</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- expense sidebar menu -->
         

          <!-- buy sidebar  -->
           <li class="nav-item has-treeview">
            <a style="font-size:14px !important;" href="#" class="nav-link <?php 
              if ($actual_link == 'purchase_order' || $actual_link =='purchase_order' || $actual_link == 'purchase_order') {echo "";
          }else{
            echo "";
          }
            ?>">
              <i  class="material-symbols-outlined">payments</i>
              <p>
                Pemesanan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a style="font-size:14px !important;" href="index.php?page=purchase_order" class="nav-link <?php echo $actual_link=='purchase_order'?'active':'';?>">
                  <!-- <i class="fas fa-align-justify nav-icon"></i> -->
                  <p>Daftar Transaksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a style="font-size:14px !important;" href="index.php?page=buy_refund_list" class="nav-link <?php echo $actual_link=='buy_refund_list'?'active':'';?>">
                  <!-- <i class="fas fa-align-justify nav-icon"></i> -->
                  <p>Refund Order list</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- buy sidebar  -->      
           <li class="nav-item has-treeview">
            <a style="font-size:14px !important;" href="#" class="nav-link <?php 
              if ($actual_link == 'profit_loss' || $actual_link =='sales_report' || $actual_link =='purchase_report' || $actual_link =='purchase_pay_report' || $actual_link =='sell_pay_report') {echo "active";
          }else{
            echo "";
          }
            ?>">
               <i class="material-symbols-outlined">lab_profile</i>
              <p>
               Reports
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a style="font-size:14px !important;" href="index.php?page=profit_loss" class="nav-link <?php echo $actual_link=='profit_loss'?'active':'';?>">
                 
                  <p>Profit loss report</p>
                </a>
              </li>
              <li class="nav-item">
                <a style="font-size:14px !important;" href="index.php?page=sales_report" class="nav-link <?php echo $actual_link=='sales_report'?'active':'';?>">
                 
                  <p>Sales report</p>
                </a>
              </li>
              <li class="nav-item">
                <a style="font-size:14px !important;" href="index.php?page=purchase_report" class="nav-link <?php echo $actual_link=='purchase_report'?'active':'';?>">
                  
                  <p>Purchase report</p>
                </a>
              </li>
              <li class="nav-item">
                <a style="font-size:14px !important;" href="index.php?page=purchase_pay_report" class="nav-link <?php echo $actual_link=='purchase_pay_report'?'active':'';?>">
                  
                  <p>Purchase payment report</p>
                </a>
              </li>
              <li class="nav-item">
                <a style="font-size:14px !important;" href="index.php?page=sell_pay_report" class="nav-link <?php echo $actual_link=='sell_pay_report'?'active':'';?>">
                  
                  <p>Sell payment report</p>
                </a>
              </li>
            </ul>
          </li>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

    </div>
    <?php require_once 'inc/member_add_modal.php'; ?>
    <?php require_once 'inc/catagory_modal.php'; ?>
    <?php require_once 'inc/suppliar_modal.php'; ?>
    <?php require_once 'inc/expense_catagory_modal.php'; ?>