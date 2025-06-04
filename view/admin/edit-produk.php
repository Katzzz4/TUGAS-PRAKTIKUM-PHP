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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Produk</title>
  <link rel="stylesheet" href="../../assets/css/admin-style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <!-- Sidebar -->
  <?php
  include 'sidebar.php'; 
  ?>
  <!-- Content -->
  <div class="content">
    <!-- Top Action Area -->
    <div class="top-action">
      <h3><i class="fas fa-edit" style="color: #667eea; margin-right: 10px;"></i>Edit Produk</h3>
    </div>

    <!-- Form Container -->
    <div class="form-container">
      <form action="../../controllers/admin/update-produk.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data['id_produk'] ?>">
        
        <div class="form-grid">
          <div class="form-group">
            <label for="nama"><i class="fas fa-tag"></i> Nama Produk</label>
            <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($data['nama_produk']) ?>" placeholder="Masukkan nama produk" required>
          </div>

          <div class="form-group">
            <label for="kategori"><i class="fas fa-list"></i> Kategori</label>
            <select id="kategori" name="kategori" required>
              <option value="">-- Pilih Kategori --</option>
              <?php
              $kategori_list = ['rumah','mainan','kosmetik','tas','fashion','digital','elektronik','alat_tulis'];
              foreach ($kategori_list as $k) {
                $selected = ($data['kategori'] == $k) ? 'selected' : '';
                $kategori_display = ucfirst(str_replace('_', ' ', $k));
                echo "<option value='$k' $selected>$kategori_display</option>";
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="harga"><i class="fas fa-money-bill-wave"></i> Harga</label>
            <input type="number" id="harga" name="harga" value="<?= $data['harga'] ?>" placeholder="Masukkan harga produk" required>
          </div>

          <div class="form-group">
            <label for="gambar"><i class="fas fa-image"></i> Gambar Produk</label>
            <div class="file-input-wrapper">
              <input type="file" id="gambar" name="gambar" accept="image/*">
              <label for="gambar" class="file-input-label">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Ganti Gambar Produk (Opsional)</span>
              </label>
            </div>
            <?php if (!empty($data['gambar'])): ?>
            <div class="current-image">
              <img src="../../assets/images-produk/<?= $data['gambar'] ?>" alt="Current Image">
              <p>Gambar saat ini</p>
            </div>
            <?php endif; ?>
          </div>

          <div class="form-group full-width">
            <label for="deskripsi"><i class="fas fa-align-left"></i> Deskripsi Produk</label>
            <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi produk" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
          </div>
        </div>

        <div class="checkbox-group">
          <input type="checkbox" id="unggulan" name="unggulan" value="1" <?= $data['unggulan'] ? 'checked' : '' ?>>
          <label for="unggulan"><i class="fas fa-star"></i> Tandai sebagai Produk Unggulan</label>
        </div>

        <div class="form-actions">
          <a href="dashboard.php" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
          </a>
          <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i>
            Update Produk
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
        label.textContent = 'Ganti Gambar Produk (Opsional)';
      }
    });
  </script>
</body>
</html>