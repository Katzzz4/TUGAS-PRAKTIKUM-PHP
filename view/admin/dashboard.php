<?php
// File: admin/dashboard.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../../config/Connection.php';

function getStatistic($table, $condition = '') {
    global $conn;
    $query = "SELECT COUNT(*) as total FROM $table $condition";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['total'];
}

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
  <h2><i class="fas fa-user-shield"></i> Menu Admin</h2>
    
    <!-- Menu Produk -->
  <div class="dropdown">
    <div class="dropdown-header">
      <i class="fas fa-boxes icon-main"></i> <!-- Pakai class icon-main -->
      <span>Kategori Produk</span>
      <i class="fas fa-chevron-down"></i>
    </div>
  <div class="dropdown-content scrollable-menu">
      <!-- Semua Produk -->
      <a href="dashboard.php?kategori=semua" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-cubes"></i>
          <div class="menu-text">
            <div>Semua Produk</div>
            <small><?= getStatistic('produk') ?> item</small>
          </div>
        </div>
      </a>

      <!-- Rumah -->
      <a href="dashboard.php?kategori=rumah" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-home"></i>
          <div class="menu-text">
            <div>Perabot Rumah</div>
            <small><?= getStatistic('produk', "WHERE kategori = 'rumah'") ?> item</small>
          </div>
        </div>
      </a>

      <!-- Mainan -->
      <a href="dashboard.php?kategori=mainan" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-puzzle-piece"></i>
          <div class="menu-text">
            <div>Mainan</div>
            <small><?= getStatistic('produk', "WHERE kategori = 'mainan'") ?> item</small>
          </div>
        </div>
      </a>

      <!-- Kosmetik -->
      <a href="dashboard.php?kategori=kosmetik" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-spa"></i>
          <div class="menu-text">
            <div>Kosmetik</div>
            <small><?= getStatistic('produk', "WHERE kategori = 'kosmetik'") ?> item</small>
          </div>
        </div>
      </a>

      <!-- Tas -->
      <a href="dashboard.php?kategori=tas" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-bag-shopping"></i>
          <div class="menu-text">
            <div>Tas</div>
            <small><?= getStatistic('produk', "WHERE kategori = 'tas'") ?> item</small>
          </div>
        </div>
      </a>

      <!-- Fashion -->
      <a href="dashboard.php?kategori=fashion" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-shirt"></i>
          <div class="menu-text">
            <div>Fashion</div>
            <small><?= getStatistic('produk', "WHERE kategori = 'fashion'") ?> item</small>
          </div>
        </div>
      </a>

      <!-- Digital -->
      <a href="dashboard.php?kategori=digital" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-gamepad"></i>
          <div class="menu-text">
            <div>Digital</div>
            <small><?= getStatistic('produk', "WHERE kategori = 'digital'") ?> item</small>
          </div>
        </div>
      </a>

      <!-- Elektronik -->
      <a href="dashboard.php?kategori=elektronik" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-plug"></i>
          <div class="menu-text">
            <div>Elektronik</div>
            <small><?= getStatistic('produk', "WHERE kategori = 'elektronik'") ?> item</small>
          </div>
        </div>
      </a>

      <!-- Alat Tulis -->
      <a href="dashboard.php?kategori=alat_tulis" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-pencil"></i>
          <div class="menu-text">
            <div>Alat Tulis</div>
            <small><?= getStatistic('produk', "WHERE kategori = 'alat_tulis'") ?> item</small>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Menu Pengguna -->
  <div class="dropdown">
      <div class="dropdown-header">
        <i class="fas fa-users-cog icon-main"></i>
        <span>Manajemen Pengguna</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="dropdown-content">
        <a href="daftar-pengguna.php" class="menu-link">
          <div class="menu-item">
            <i class="fas fa-user-friends"></i>
            <div class="menu-text">
              <div>Daftar Pengguna</div>
              <small><?= getStatistic('auth') ?> akun</small>
            </div>
          </div>
        </a>
      </div>
    </div>
  
  <a href="../../controllers/admin/logout.php" class="logout">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

  <div class="content">
    <div class="top-action">
      <h3>Dashboard Produk</h3>
      <a href="tambah-produk.php" class="btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk
      </a>
    </div>

    <!-- Filter kategori -->
    <?php
    $kategori = $_GET['kategori'] ?? 'semua';
    $query = ($kategori === 'semua') 
      ? "SELECT * FROM produk ORDER BY id_produk DESC" 
      : "SELECT * FROM produk WHERE kategori = '$kategori' ORDER BY id_produk DESC";
    $produk = mysqli_query($conn, $query);
    ?>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th class="col-no">No</th>
            <th class="col-image">Gambar</th>
            <th class="col-name">Nama Produk</th>
            <th class="col-category">Kategori</th>
            <th class="col-price">Harga</th>
            <th class="col-description">Deskripsi</th>
            <th class="col-actions">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($produk)) : ?>
          <tr>
            <td class="col-no">
              <div class="row-number"><?= $no++ ?></div>
            </td>
            <td class="col-image">
              <img src="../../assets/images-produk/<?= $row['gambar'] ?>" 
                   alt="<?= htmlspecialchars($row['nama_produk']) ?>" 
                   class="product-image"
                   onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNzAiIGhlaWdodD0iNzAiIHZpZXdCb3g9IjAgMCA3MCA3MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjcwIiBoZWlnaHQ9IjcwIiBmaWxsPSIjRjdGQUZDIi8+CjxwYXRoIGQ9Ik0yMCAyNUgyNVYzMEgyMFYyNVoiIGZpbGw9IiNEREREREQiLz4KPHA+dGggZD0iTTIwIDM1SDUwVjQ1SDIwVjM1WiIgZmlsbD0iI0RERERERCIvPgo8L3N2Zz4K'">
            </td>
            <td class="col-name">
              <div class="product-name"><?= htmlspecialchars($row['nama_produk']) ?></div>
            </td>
            <td class="col-category">
              <span class="category-badge"><?= ucfirst($row['kategori']) ?></span>
            </td>
            <td class="col-price">
              <div class="product-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
            </td>
            <td class="col-description">
              <div class="description-container">
                <div class="description-text" id="desc-<?= $row['id_produk'] ?>">
                  <?= htmlspecialchars($row['deskripsi']) ?>
                </div>
                <?php if (strlen($row['deskripsi']) > 150) : ?>
                <span class="read-more-btn" onclick="toggleDescription(<?= $row['id_produk'] ?>)">
                  Baca selengkapnya
                </span>
                <?php endif; ?>
              </div>
            </td>
            <td class="col-actions">
              <div class="action-buttons">
                <a href="edit-produk.php?id=<?= $row['id_produk'] ?>" class="btn-secondary">
                  <i class="fas fa-edit"></i> Edit
                </a>
                <a href="../../controllers/admin/hapus-produk.php?id=<?= $row['id_produk'] ?>" 
                   class="btn-danger" 
                   onclick="return confirm('Apakah Anda yakin ingin menghapus produk <?= htmlspecialchars($row['nama_produk']) ?>?')">
                  <i class="fas fa-trash"></i> Hapus
                </a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    document.querySelectorAll('.dropdown-header').forEach(header => {
      header.addEventListener('click', () => {
        header.parentElement.classList.toggle('active');
      });
    });

    // Prevent dropdown closure saat scroll
    document.querySelectorAll('.dropdown-content').forEach(content => {
      content.addEventListener('wheel', (e) => {
        e.stopPropagation();
      });
    });

    // Toggle description function
    function toggleDescription(productId) {
      const descElement = document.getElementById(`desc-${productId}`);
      const btnElement = descElement.nextElementSibling;
      
      if (descElement.classList.contains('expanded')) {
        descElement.classList.remove('expanded');
        btnElement.textContent = 'Baca selengkapnya';
      } else {
        descElement.classList.add('expanded');
        btnElement.textContent = 'Sembunyikan';
      }
    }

    // Image error handling
    document.querySelectorAll('.product-image').forEach(img => {
      img.addEventListener('error', function() {
        this.style.backgroundColor = '#f7fafc';
        this.style.display = 'flex';
        this.style.alignItems = 'center';
        this.style.justifyContent = 'center';
        this.style.fontSize = '20px';
        this.innerHTML = '📷';
      });
    });
  </script>

</body>
</html>