<?php
require_once '../../config/Connection.php';
session_start();

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id'");
$produk = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($produk['nama_produk']) ?></title>
  <link rel="stylesheet" href="../style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f8f9fa;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    
    .detail-page {
      display: flex;
      gap: 30px;
      background-color: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    /* Product Images Section */
    .product-images {
      flex: 1;
      max-width: 400px;
    }
    
    .main-image-container {
      position: relative;
      margin-bottom: 15px;
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid #e5e7eb;
    }
    
    .main-image-container img {
      width: 100%;
      height: 400px;
      object-fit: cover;
      display: block;
    }
    
    .thumbnail-row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }
    
    .thumbnail-row img {
      width: 60px;
      height: 60px;
      border-radius: 6px;
      cursor: pointer;
      border: 2px solid #e5e7eb;
      object-fit: cover;
      transition: border-color 0.2s;
    }
    
    .thumbnail-row img.active,
    .thumbnail-row img:hover {
      border-color:  #232f3e;
    }
    
    /* Product Details Section */
    .product-details {
      flex: 1.5;
      padding-right: 20px;
    }
    
    .product-title {
      font-size: 24px;
      font-weight: 600;
      color: #1f2937;
      margin-bottom: 15px;
      line-height: 1.3;
    }
    
    .product-price {
      font-size: 28px;
      font-weight: 700;
      color: #dc2626;
      margin-bottom: 20px;
    }
    
    /* Tabs Section */
    .tabs-container {
      border-bottom: 1px solid #e5e7eb;
      margin-bottom: 20px;
    }
    
    .tabs {
      display: flex;
      gap: 30px;
    }
    
    .tab-item {
      padding: 12px 0;
      font-weight: 600;
      color: #6b7280;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.2s;
    }
    
    .tab-item.active {
      color: #00aa5b;
      border-bottom-color: #00aa5b;
    }
    
    /* Product Info Section */
    .product-info {
      font-size: 14px;
      line-height: 1.6;
    }
    
    .info-row {
      display: flex;
      margin-bottom: 12px;
      align-items: flex-start;
    }
    
    .info-label {
      font-weight: 600;
      color: #374151;
      min-width: 120px;
      margin-right: 15px;
    }
    
    .info-value {
      color: #6b7280;
      flex: 1;
    }
    
    .product-description {
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid #f3f4f6;
    }
    
    .description-content {
      color: #374151;
      line-height: 1.7;
      max-height: 120px;
      overflow: hidden;
      transition: max-height 0.3s ease;
      position: relative;
    }
    
    .description-content.expanded {
      max-height: none;
    }
    
    .description-content::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 30px;
      background: linear-gradient(transparent, white);
      pointer-events: none;
    }
    
    .description-content.expanded::after {
      display: none;
    }
    
    .read-more {
      color: #00aa5b;
      cursor: pointer;
      font-weight: 500;
      margin-top: 10px;
      display: inline-block;
      font-size: 14px;
    }
    
    .read-more:hover {
      text-decoration: underline;
    }
    
    /* Purchase Section */
    .purchase-section {
      flex: 1;
      max-width: 350px;
      background-color: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 25px;
      height: fit-content;
      position: sticky;
      top: 20px;
    }
    
    .purchase-section h3 {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 15px;
      color: #1f2937;
    }
    
    .price-box {
      font-size: 24px;
      font-weight: 700;
      color: #dc2626;
      margin-bottom: 8px;
    }
    
    .stock-info {
      font-size: 14px;
      color: #6b7280;
      margin-bottom: 20px;
    }
    
    .quantity-selector {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }
    
    .quantity-selector span {
      font-size: 14px;
      font-weight: 500;
    }
    
    .quantity-input {
      width: 60px;
      padding: 8px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      text-align: center;
      font-size: 14px;
    }
    
    .btn-primary, .btn-outline {
      width: 100%;
      padding: 14px;
      margin: 8px 0;
      font-size: 16px;
      font-weight: 600;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.2s;
      border: none;
    }
    
    .btn-primary {
      background-color:  #232f3e;
      color: #fff;
    }
    
    .btn-primary:hover {
      background-color:  #232f3e;
    }
    
    .btn-outline {
      border: 1px solid  #232f3e;
      background-color: transparent;
      color: #232f3e;
    }
    
    .btn-outline:hover {
      background-color: #f0fdf4;
    }
    
    .action-links {
      display: flex;
      justify-content: space-around;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid #f3f4f6;
    }
    
    .action-links span {
      cursor: pointer;
      color: #6b7280;
      font-size: 13px;
      display: flex;
      align-items: center;
      gap: 5px;
      padding: 8px;
      border-radius: 6px;
      transition: all 0.2s;
    }
    
    .action-links span:hover {
      color:  #232f3e;
      background-color: #f0fdf4;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
      .detail-page {
        flex-direction: column;
        gap: 20px;
        padding: 20px;
      }
      
      .product-details {
        padding-right: 0;
      }
      
      .purchase-section {
        max-width: none;
        position: static;
      }
      
      .product-images {
        max-width: none;
      }
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container">
    <div class="detail-page">
      <!-- Product Images -->
      <div class="product-images">
        <div class="main-image-container">
          <img src="../../assets/images-produk/<?= $produk['gambar'] ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>" id="main-image">
        </div>
        <!-- Thumbnail images (you can add more if available) -->
        <div class="thumbnail-row">
          <img src="../../assets/images-produk/<?= $produk['gambar'] ?>" alt="Thumbnail" class="active" onclick="changeImage(this)">
          <!-- Add more thumbnails here if you have multiple images -->
        </div>
      </div>

      <!-- Product Details -->
      <div class="product-details">
        <h1 class="product-title"><?= htmlspecialchars($produk['nama_produk']) ?></h1>
        <div class="product-price">Rp<?= number_format($produk['harga'], 0, ',', '.') ?></div>
        
        <!-- Tabs -->
        <div class="tabs-container">
          <div class="tabs">
            <div class="tab-item active">Detail</div>
          </div>
        </div>
        
        <!-- Product Info -->
        <div class="product-info">
          <div class="info-row">
            <span class="info-label">Kondisi:</span>
            <span class="info-value">Baru</span>
          </div>
          <div class="info-row">
            <span class="info-label">Min. Pemesanan:</span>
            <span class="info-value">1 Buah</span>
          </div>
          <div class="info-row">
            <span class="info-label">Kategori:</span>
            <span class="info-value"><?= htmlspecialchars($produk['kategori']) ?></span>
          </div>
          <div class="info-row">
            <span class="info-label">Etalase:</span>
            <span class="info-value" style="color: #22c55e; font-weight: 500;">Boneka</span>
          </div>
          
          <!-- Description Section -->
          <div class="product-description">
            <div class="info-label" style="margin-bottom: 10px;">Deskripsi:</div>
            <div class="description-content" id="desc">
              <?= nl2br(htmlspecialchars($produk['deskripsi'])) ?>
            </div>
            <span class="read-more" onclick="toggleDesc()">Lihat Lebih Banyak</span>
          </div>
        </div>
      </div>

      <!-- Purchase Section -->
      <div class="purchase-section">
        <h3>Pembelian</h3>
        
        <button class="btn-primary">+ Keranjang</button>
        <button class="btn-outline">Beli</button>
        
        <div class="action-links">
          <span>💬 Chat</span>
          <span>🤍 Wishlist</span>
          <span>🔗 Share</span>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script>
    function toggleDesc() {
      const desc = document.getElementById('desc');
      const more = document.querySelector('.read-more');
      desc.classList.toggle('expanded');
      more.textContent = desc.classList.contains('expanded') ? 'Sembunyikan' : 'Lihat Lebih Banyak';
    }
    
    function changeImage(thumbnail) {
      const mainImage = document.getElementById('main-image');
      const thumbnails = document.querySelectorAll('.thumbnail-row img');
      
      // Update main image
      mainImage.src = thumbnail.src;
      
      // Update active thumbnail
      thumbnails.forEach(thumb => thumb.classList.remove('active'));
      thumbnail.classList.add('active');
    }
  </script>
</body>
</html>