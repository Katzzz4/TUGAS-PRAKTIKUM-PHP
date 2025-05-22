<?php
// File: admin/tambah_produk.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <link rel="stylesheet" href="../../assets/css/admin-style.css">
  <style>
    .form-box input,
    .form-box textarea,
    .form-box select,
    .form-box button {
      display: block;
      width: 100%;
      margin-bottom: 15px;
      padding: 10px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🛍️ Tambah Produk</h2>
    <form class="admin-form" action="../../controllers/admin/proses-produk.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="nama" placeholder="Nama Produk" required>
      <textarea name="deskripsi" placeholder="Deskripsi Produk" required></textarea>
      <input type="number" name="harga" placeholder="Harga" required>
      <select name="kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="rumah">Rumah</option>
        <option value="mainan">Mainan</option>
        <option value="kosmetik">Kosmetik</option>
        <option value="tas">Tas</option>
        <option value="fashion">Fashion</option>
        <option value="digital">Digital</option>
        <option value="elektronik">Elektronik</option>
        <option value="alat_tulis">Alat Tulis</option>
      </select>
      <input type="file" name="gambar" accept="image/*" required>

      <label style="margin: 5px 0;">
        <input type="checkbox" name="unggulan" value="1"> Tandai sebagai Produk Unggulan
      </label>

      <button type="submit">Simpan Produk</button>
       <a href="dashboard.php" class="btn-kembali">Kembali</a>
    </form>
  </div>
</body>
</html>
