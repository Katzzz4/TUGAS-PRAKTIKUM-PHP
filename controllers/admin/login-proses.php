<?php
session_start();
require_once '../../config/Connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");
$data = mysqli_fetch_assoc($query);

if ($data && password_verify($password, $data['password'])) {
    $_SESSION['admin'] = $data['nama_admin'];
    header("Location: ../../view/admin/dashboard.php");
    exit;
} else {
    echo "<script>alert('Login admin gagal!'); window.location='../../view/admin/login.php';</script>";
}
?>
