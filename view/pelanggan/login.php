<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="../../assets/css/style.css"> 
<body class="login-body">
  <div class="login-container">
    <h2>Sign In to Your Account</h2>
    <form action="../../controllers/pelanggan/login-proses.php" method="POST">
      <input type="text" name="username" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn-login">Login</button>
      <p class="signup-link">Don't have an account? <a href="register.php">Register here</a></p>
    </form>
  </div>
</body>
</html>
