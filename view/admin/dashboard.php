<?php
// File: admin/dashboard.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../../config/Connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="../../assets/css/admin-style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="sidebar">
    <h2>Menu</h2>
    <a href="dashboard.php?kategori=semua"><i class="fas fa-box"></i> Semua Produk</a>
    <a href="dashboard.php?kategori=rumah">🏠 Rumah</a>
    <a href="dashboard.php?kategori=mainan">🧸 Mainan</a>
    <a href="dashboard.php?kategori=kosmetik">💄 Kosmetik</a>
    <a href="dashboard.php?kategori=tas">👜 Tas</a>
    <a href="dashboard.php?kategori=fashion">🧦 Fashion</a>
    <a href="dashboard.php?kategori=digital">🎮 Digital</a>
    <a href="dashboard.php?kategori=elektronik">🔌 Elektronik</a>
    <a href="dashboard.php?kategori=alat_tulis">✏️ Alat Tulis</a>
    <a href="../../controllers/admin/logout.php" class="logout">Logout</a>
  </div>

  <div class="content">
    <h2>Dashboard Produk</h2>
    <div class="top-action">
      <a href="tambah-produk.php" class="btn-primary">+ Tambah Produk</a>
    </div>

    <!-- Filter kategori -->
    <?php
    $kategori = $_GET['kategori'] ?? 'semua';
    $query = ($kategori === 'semua') 
      ? "SELECT * FROM produk" 
      : "SELECT * FROM produk WHERE kategori = '$kategori'";
    $produk = mysqli_query($conn, $query);
    ?>

    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Gambar</th>
          <th>Nama Produk</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Deskripsi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($produk)) : ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><img src="../../assets/images/<?= $row['gambar'] ?>" width="80"></td>
          <td><?= $row['nama_produk'] ?></td>
          <td><?= ucfirst($row['kategori']) ?></td>
          <td>Rp <?= number_format($row['harga']) ?></td>
          <td><?= $row['deskripsi'] ?></td>
          <td>
            <a href="edit-produk.php?id=<?= $row['id_produk'] ?>" class="btn-secondary">Edit</a>
            <a href="../../controllers/admin/hapus-produk.php?id=<?= $row['id_produk'] ?>" class="btn-danger" onclick="return confirm('Hapus produk ini?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>