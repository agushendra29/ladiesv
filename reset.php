<?php require_once 'app/init.php'; 
if ($Ouser->is_login() != false) {
  header("location:index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body style="background-color:#f6f7fb; font-family: 'Plus Jakarta Sans', sans-serif;">

<div class="container">
  <div class="card" style="max-width:420px; margin:100px auto; padding:40px 30px; border:none; border-radius:16px; box-shadow:0 4px 16px rgba(0,0,0,0.05);">
    <div class="card-body p-0">
      <div class="text-center mb-4">
        <img src="assets/images/logo.png" alt="logo" style="width:120px;">
      </div>

      <h2 class="text-center mb-4" style="font-weight:600; font-size:22px; color:#333;">🔑 Reset Password</h2>

      <?php 
        if (isset($_SESSION['reset_error'])) {
          echo "<div class='alert alert-danger text-center' style='font-size:14px; border-radius:8px;'>".$_SESSION['reset_error']."</div>";
          unset($_SESSION['reset_error']);
        }
        if (isset($_SESSION['reset_success'])) {
          echo "<div class='alert alert-success text-center' style='font-size:14px; border-radius:8px;'>".$_SESSION['reset_success']."</div>";
          unset($_SESSION['reset_success']);
        }
      ?>

      <form action="app/action/reset_password.php" method="post">
        <div class="mb-3">
          <label for="username" class="form-label" style="font-weight:600; font-size:14px; color:#333;">ID Member</label>
          <input type="text" name="username" class="form-control" id="username" required 
                 style="height:44px; border-radius:10px; font-size:14px; border:1px solid #d0d7de;">
        </div>

        <div class="mb-3">
          <label for="nik" class="form-label" style="font-weight:600; font-size:14px; color:#333;">6 Digit Terakhir NIK</label>
          <input type="text" name="nik_last6" maxlength="6" class="form-control" id="nik" required 
                 pattern="\d{6}" title="Masukkan 6 digit angka terakhir NIK"
                 style="height:44px; border-radius:10px; font-size:14px; border:1px solid #d0d7de;">
        </div>

        <button type="submit" name="reset_password" class="btn w-100" style="background-color:#EB4F7D; color:#fff; font-weight:600; border-radius:12px; height:44px; font-size:15px; transition: all 0.3s;">Reset Password</button>
      </form>

      <div class="text-center mt-3">
        <a href="login.php" style="font-size:13px; color:#EB4F7D; font-weight:600; text-decoration:none;">← Kembali ke Login</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
