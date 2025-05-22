<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="login-body">
  <div class="login-container">
    <h2>Create Your Account</h2>
    <form action="../../controllers/pelanggan/process_register.php" method="POST">
      <input type="text" name="nama" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="repassword" placeholder="Re-enter Password" required>
      <button type="submit" class="btn-login">Register</button>
      <p class="signup-link">Already have an account? <a href="login.php">Sign in here</a></p>
    </form>
  </div>
</body>
</html>
