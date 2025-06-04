<?php
session_start();
require_once '../../config/Connection.php';

$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$harga = $_POST['harga'];
$kategori = $_POST['kategori'];
$unggulan = isset($_POST['unggulan']) ? 1 : 0;

$gambar = $_FILES['gambar']['name'];
$tmp = $_FILES['gambar']['tmp_name'];
$folder = '../../assets/images-produk/';
$path = $folder . basename($gambar);

if (move_uploaded_file($tmp, $path)) {
    $query = "INSERT INTO produk (nama_produk, deskripsi, harga, kategori, gambar, unggulan) 
              VALUES ('$nama', '$deskripsi', '$harga', '$kategori', '$gambar', '$unggulan')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('✅ Produk berhasil ditambahkan!'); window.location='../../view/admin/dashboard.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menambahkan produk!'); window.location='../../view/admin/tambah_produk.php';</script>";
    }
} else {
    echo "<script>alert('❌ Upload gambar gagal!'); window.location='../../view/admin/tambah_produk.php';</script>";
}
?>
