<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Akun Saya</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    h3{
      color : black;
    }
  </style>
</head>
<body>
  
  <?php include 'navbar.php'; ?>

  <div class="account-container">
    <div class="account-title">Your Account</div>
    <div class="account-grid">
      <div class="account-card">
        <a href="returns-orders.php">
        <img src="../../assets/images/fshub_yourorders.png" alt="Orders">
        <h3>Your Orders</h3>
        <p>Track, return, cancel an order, or download invoice</p>
        </a>
      </div>
      <div class="account-card">
        <img src="../../assets/images/fshub_security.png" alt="Login">
        <h3>Login & Security</h3>
        <p>Edit login, name, and mobile number</p>
      </div>
      <div class="account-card">
        <img src="../../assets/images/fshub_address_book._CB613924977_.png" alt="Address">
        <h3>Your Addresses</h3>
        <p>Edit or manage your saved addresses</p>
      </div>
      <div class="account-card">
        <img src="../../assets/images/fshub_yourpayments.png" alt="Payment">
        <h3>Your Payments</h3>
        <p>Manage payment methods and settings</p>
      </div>
      <div class="account-card">
        <img src="../../assets/images/fshub_customerservice.png" alt="Support">
        <h3>Customer Service</h3>
        <p>Contact support or browse help topics</p>
      </div>
    </div>
  </div>

</body>
</html>
