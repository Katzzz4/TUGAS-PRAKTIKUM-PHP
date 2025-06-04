<?php
session_start();
require_once '../../config/Connection.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$harga = $_POST['harga'];
$kategori = $_POST['kategori'];
$unggulan = isset($_POST['unggulan']) ? 1 : 0;

// Validasi input
if (!empty($_FILES['gambar']['name'])) {
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = '../../assets/images-produk/';
    $path = $folder . basename($gambar);

    if (move_uploaded_file($tmp, $path)) {
        $query = "UPDATE produk SET nama_produk='$nama', deskripsi='$deskripsi', harga='$harga', kategori='$kategori', unggulan='$unggulan', gambar='$gambar' WHERE id_produk='$id'";
    } else {
        echo "<script>alert('❌ Upload gambar gagal!'); window.location='../../view/admin/edit_produk.php?id=$id';</script>";
        exit;
    }
} else {
    $query = "UPDATE produk SET nama_produk='$nama', deskripsi='$deskripsi', harga='$harga', kategori='$kategori', unggulan='$unggulan' WHERE id_produk='$id'";
}

$update = mysqli_query($conn, $query);

if ($update) {
    echo "<script>alert('✅ Produk berhasil diperbarui!'); window.location='../../view/admin/dashboard.php';</script>";
} else {
    echo "<script>alert('❌ Gagal memperbarui produk.'); window.location='../../view/edit_produk.php?id=$id';</script>";
}
?>
