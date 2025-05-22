<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "myblog"; // Ganti jika nama databasenya berbeda

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
