<?php
session_start();
require_once '../../config/Connection.php';

// Cegah akses langsung tanpa data POST
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo "<script>alert('Akses tidak sah.'); window.location='login.php';</script>";
    exit;
}

$email = $_POST['username'];
$password = $_POST['password'];

// Cari data user berdasarkan email
$query = mysqli_query($conn, "SELECT * FROM auth WHERE email = '$email'");
$data = mysqli_fetch_assoc($query);

// Verifikasi password dan login
if ($data && password_verify($password, $data['password'])) {
    $_SESSION['nama'] = $data['nama'] ?? 'Pengguna';

    // Redirect ke halaman utama
    header("Location: ../../view/pelanggan/index.php");
    exit;
} else {
    echo "<script>alert('Email atau password salah!'); window.location='../../view/pelanggan/login.php';</script>";
    exit;
}
?>
