<?php
session_start();
require_once '../../config/Connection.php';
 
$id = $_GET['id'];
// Ambil data produk untuk hapus file gambar
$data = mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk = '$id'");
$row = mysqli_fetch_assoc($data);
$gambar = $row['gambar'];

// Hapus data produk
$query = mysqli_query($conn, "DELETE FROM produk WHERE id_produk = '$id'");

if ($query) {
    // Hapus gambar dari folder jika ada
    $path = '../../assets/images-produk/' . $gambar;
    if (file_exists($path)) {
        unlink($path);
    }
    echo "<script>alert('Produk berhasil dihapus!'); window.location='../../view/admin/dashboard.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus produk.'); window.location='../../view/admin/dashboard.php';</script>";
}
?>
