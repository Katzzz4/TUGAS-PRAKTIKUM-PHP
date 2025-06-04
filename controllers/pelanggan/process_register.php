<?php
session_start();
require_once '../../config/Connection.php';

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];
$repassword = $_POST['repassword'];

if ($password !== $repassword) {
    echo "<script>alert('Password tidak cocok!'); window.location='register.php';</script>";
    exit;
}

// Cek duplikat email
$cek = mysqli_query($conn, "SELECT * FROM auth WHERE email = '$email'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Email sudah digunakan!'); window.location='../../view/pelanggan/register.php';</script>";
    exit;
}

// Hash dan simpan
$hash = password_hash($password, PASSWORD_BCRYPT);
$query = "INSERT INTO auth (id_pengguna, email, password, nama, created_at) VALUES (NULL, '$email', '$hash', '$nama', NOW())";
mysqli_query($conn, $query) or die("Query error: " . mysqli_error($conn));

echo "<script>alert('Berhasil mendaftar! Silakan login.'); window.location='../../view/pelanggan/login.php';</script>";
?>
