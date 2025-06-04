<?php
include '../../config/Connection.php';

$username = $_POST['username'];
$nama = $_POST['nama_admin'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$query = "INSERT INTO admin (username, nama_admin, password) VALUES ('$username', '$nama', '$password')";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "<script>alert('Admin berhasil ditambahkan!'); window.location='../../view/admin/login.php';</script>";
} else {
    echo "<script>alert('Gagal menambahkan admin!'); window.location='../../view/admin/maintenance-admin.php';</script>";
}
?>
