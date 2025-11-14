<?php require_once 'app/init.php'; 
if ($Ouser->is_login() != false) {
  header("location:index.php");
}



?>
<!DOCTYPE html>
<html lang="en">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./assets/images/logo.png" type="image/png">
  <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>
<body style="background-image:url(assets/images/background-login.jpg); background-size:cover;">
  <?php if (isset($_GET['timeout'])): ?>
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'Session Expired',
      text: 'Session Anda berakhir karena tidak ada aktivitas selama 30 menit.',
      confirmButtonText: 'OK'
    });
  </script>
<?php endif; ?>

<div class="container">
  <div class="card" style="max-width:420px; margin:100px auto; padding:40px 30px; border:none; border-radius:16px; box-shadow:0 4px 16px rgba(0,0,0,0.05);">
    <div class="card-body p-0">
      <div class="text-center mb-0">
      </div>

      <h2 class="text-center mb-4" style="font-weight:400; font-size:20px; color:#333;">Sign in to your account</h2>

      <?php 
        if (isset($_SESSION['login_error'])) {
          echo "<div class='alert alert-danger text-center' style='font-size:14px; border-radius:8px;'>".$_SESSION['login_error']."</div>";
        }
      ?>

      <form action="app/action/login.php" method="post">
        <div class="mb-3">
          <label for="username" class="form-label" style="font-weight:600; font-size:14px; color:#333;">User ID</label>
          <input type="text" name="username" class="form-control" id="username" required style="height:44px; border-radius:10px; font-size:14px; border:1px solid #d0d7de;">
        </div>

        <div class="mb-3 position-relative">
  <label for="password" class="form-label" style="font-weight:600; font-size:14px; color:#333;">Password</label>
  <input type="password" name="password" class="form-control" id="password" required
         style="height:44px; border-radius:10px; font-size:14px; border:1px solid #d0d7de; padding-right:40px;">
  
  <!-- Tombol view password -->
  <span id="togglePassword" style="
        position:absolute;
        top:70%;
        right:12px;
        transform:translateY(-50%);
        cursor:pointer;
        font-size:14px;
        color:#555;">👁️</span>
</div>

        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember" style="border-radius:4px;">
          <label class="form-check-label" for="remember" style="font-size:13px; color:#555;">Remember me</label>
        </div>

        <button type="submit" name="admin_login" class="btn w-100" style="background-color:#EB4F7D; color:#fff; font-weight:600; border-radius:12px; height:44px; font-size:15px; transition: all 0.3s;">Login</button>
      </form>
      <div class="text-center mt-3">
        <a href="reset.php" style="font-size:13px; color:#EB4F7D; font-weight:600; text-decoration:none;">Lupa password? </a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
<script>
  const passwordInput = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');

  togglePassword.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
  });
</script>

<style>

</style>
