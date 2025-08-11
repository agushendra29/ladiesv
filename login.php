<?php require_once 'app/init.php'; 
if ($Ouser->is_login() != false) {
  header("location:index.php");
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
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

      <h2 class="text-center mb-4" style="font-weight:600; font-size:24px; color:#333;">Sign in to your account</h2>

      <?php 
        if (isset($_SESSION['login_error'])) {
          echo "<div class='alert alert-danger text-center' style='font-size:14px; border-radius:8px;'>".$_SESSION['login_error']."</div>";
        }
      ?>

      <form action="app/action/login.php" method="post">
        <div class="mb-3">
          <label for="username" class="form-label" style="font-weight:600; font-size:14px; color:#333;">Username</label>
          <input type="text" name="username" class="form-control" id="username" required style="height:44px; border-radius:10px; font-size:14px; border:1px solid #d0d7de;">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label" style="font-weight:600; font-size:14px; color:#333;">Password</label>
          <input type="password" name="password" class="form-control" id="password" required style="height:44px; border-radius:10px; font-size:14px; border:1px solid #d0d7de;">
        </div>

        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember" style="border-radius:4px;">
          <label class="form-check-label" for="remember" style="font-size:13px; color:#555;">Remember me</label>
        </div>

        <button type="submit" name="admin_login" class="btn w-100" style="background-color:#0073ea; color:#fff; font-weight:600; border-radius:12px; height:44px; font-size:15px; transition: all 0.3s;">Login</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
