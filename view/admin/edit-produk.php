<?php
require_once '../../config/Connection.php';
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <link rel="stylesheet" href="../../assets/css/admin-style.css">
  <style>
    .form-group {
      margin-bottom: 10px;
      display: flex;
      flex-direction: column;
    }
    .form-group label {
      font-weight: bold;
      margin-bottom: 5px;
    }
    .admin-form input[type="text"],
    .admin-form input[type="number"],
    .admin-form textarea,
    .admin-form select,
    .admin-form input[type="file"] {
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      width: 93%;
    }
    .checkbox-wrapper {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 10px 0 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>✏️ Edit Produk</h2>
    <form class="admin-form" action="../../controllers/admin/update-produk.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $data['id_produk'] ?>">

      <div class="form-group">
        <label>Nama Produk</label>
        <input type="text" name="nama" value="<?= $data['nama_produk'] ?>" required>
      </div>

      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" required><?= $data['deskripsi'] ?></textarea>
      </div>

      <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" value="<?= $data['harga'] ?>" required>
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <select name="kategori" required>
          <option value="">-- Pilih Kategori --</option>
          <?php
          $kategori_list = ['rumah','mainan','kosmetik','tas','fashion','digital','elektronik','alat_tulis'];
          foreach ($kategori_list as $k) {
            $selected = ($data['kategori'] == $k) ? 'selected' : '';
            echo "<option value='$k' $selected>" . ucfirst($k) . "</option>";
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label>Gambar Produk (biarkan kosong jika tidak ingin mengubah)</label>
        <input type="file" name="gambar" accept="image/*">
      </div>

      <div class="checkbox-wrapper">
        <input type="checkbox" name="unggulan" value="1" <?= $data['unggulan'] ? 'checked' : '' ?>>
        <label>Tandai sebagai Produk Unggulan</label>
      </div>

      <button type="submit">Update Produk</button>
      <a href="dashboard.php" class="btn-kembali">Kembali</a>
    </form>
  </div>
</body>
</html>
