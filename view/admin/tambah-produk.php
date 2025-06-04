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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Produk</title>
  <link rel="stylesheet" href="../../assets/css/admin-style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php
  include 'sidebar.php'; 
  ?>
  <!-- Content -->
  <div class="content">
    <!-- Top Action Area -->
    <div class="top-action">
      <h3><i class="fas fa-plus-circle" style="color: #667eea; margin-right: 10px;"></i>Tambah Produk</h3>
    </div>

    <!-- Form Container -->
    <div class="form-container">
      <form action="../../controllers/admin/proses-produk.php" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
          <div class="form-group">
            <label for="nama"><i class="fas fa-tag"></i> Nama Produk</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan nama produk" required>
          </div>

          <div class="form-group">
            <label for="kategori"><i class="fas fa-list"></i> Kategori</label>
            <select id="kategori" name="kategori" required>
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
          </div>

          <div class="form-group">
            <label for="harga"><i class="fas fa-money-bill-wave"></i> Harga</label>
            <input type="number" id="harga" name="harga" placeholder="Masukkan harga produk" required>
          </div>

          <div class="form-group">
            <label for="gambar"><i class="fas fa-image"></i> Gambar Produk</label>
            <div class="file-input-wrapper">
              <input type="file" id="gambar" name="gambar" accept="image/*" required>
              <label for="gambar" class="file-input-label">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Pilih Gambar Produk</span>
              </label>
            </div>
          </div>

          <div class="form-group full-width">
            <label for="deskripsi"><i class="fas fa-align-left"></i> Deskripsi Produk</label>
            <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi produk" required></textarea>
          </div>
        </div>

        <div class="checkbox-group">
          <input type="checkbox" id="unggulan" name="unggulan" value="1">
          <label for="unggulan"><i class="fas fa-star"></i> Tandai sebagai Produk Unggulan</label>
        </div>

        <div class="form-actions">
          <a href="dashboard.php" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
          </a>
          <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i>
            Simpan Produk
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Dropdown functionality
    document.querySelectorAll('.dropdown-header').forEach(header => {
      header.addEventListener('click', () => {
        const dropdown = header.parentElement;
        dropdown.classList.toggle('active');
      });
    });

    // File input functionality
    document.getElementById('gambar').addEventListener('change', function(e) {
      const label = document.querySelector('.file-input-label span');
      if (e.target.files.length > 0) {
        label.textContent = e.target.files[0].name;
      } else {
        label.textContent = 'Pilih Gambar Produk';
      }
    });
  </script>
</body>
</html>