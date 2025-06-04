<?php
require_once '../../config/Connection.php';
$kategori = $_GET['kategori'] ?? '';
// Validasi kategori atau proses filter kategori
$query = mysqli_query($conn, "SELECT * FROM produk WHERE kategori = '$kategori'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kategori: <?= ucfirst($kategori) ?></title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    .category-box {
      background: linear-gradient(135deg, #fff0f0, #ffeaea);
      border-left: 6px solid #d10024;
      padding: 15px 20px;
      border-radius: 10px;
      margin: 30px auto 20px;
      max-width: 600px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .category-box i {
      font-size: 24px;
      color: #d10024;
    }
    .category-box h2 {
      margin: 0;
      font-size: 22px;
      color: #333;
    }
    .btn-kembali {
      display: inline-block;
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 10px 20px;
      background: #0f1111;
      color: #fff;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-kembali:hover {
      background: #0f1111;
    }
  </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

  <div class="container">
    <div class="category-box">
      <i>📂</i>
      <h2>Produk <?= ucfirst($kategori) ?></h2>
    </div>

    <div class="products">
      <?php while ($row = mysqli_fetch_assoc($query)) : ?>
        <div class="product">
          <a href="detail-produk.php?id=<?= $row['id_produk'] ?>">
          <div class="product-img">
            <img src="../../assets/images-produk/<?= !empty($row['gambar']) ? $row['gambar'] : 'default.png' ?>" alt="<?= $row['nama_produk'] ?>">
          </div>
          </a>
           <div class="product-info flex-layout">
            <div class="product-details">
              <div class="product-name"><?= $row['nama_produk'] ?></div>
              <div class="product-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
            </div>
            <div class="product-actions inline">
              <button class="btn-buy pre-order">+ Keranjang</button>
              <button class="btn-buy buy-now">Beli</button>
            </div>
          </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>

  <a href="index.php" class="btn-kembali">Kembali ke Beranda</a>

<?php include 'footer.php'; ?>

</body>
</html>
