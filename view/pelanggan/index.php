<?php
require_once '../../config/Connection.php';
$unggulan = mysqli_query($conn, "SELECT * FROM produk WHERE unggulan = 1 LIMIT 4");
$terbaru = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 4");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Amazon.com</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../../assets/css/style.css" />
  </head>
  <body>
    <header>
      <div class="navbar">
        <div class="nav-logo border">
          <div class="logo"></div>
        </div>
        <div class="nav-address border">
          <p class="add-first">Deliver to</p>
          <div class="address-icon">
            <i class="fa-solid fa-location-dot"></i>
            <p class="add-second"><b>Indonesia </b></p>
          </div>
        </div>
    
        <div class="nav-search">
          <select class="search-select">
            <option>All</option>
          </select>
          <input class="search-input" placeholder="Search Amazon.in" />
          <div class="search-icon">
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>
        <div class="map-icon border">
          <i class="fa-solid fa-earth-americas"></i>
          <div>
            <p>
              <b><pre> EN</pre></b>
            </p>
            <div class="sort-down">
              <i class="fa-solid fa-sort-down"></i>
            </div>
          </div>
        </div>

  <div class="account-dropdown border">
          <?php session_start(); ?>
    <div class="account-dropdown border">
        <button class="account-btn">
        <?php if (isset($_SESSION['nama'])): ?>
             Hello, <?= htmlspecialchars($_SESSION['nama']) ?><br><strong>Account & Lists</strong>
        <?php else: ?>
             Hello, sign in<br><strong>Account & Lists</strong>
        <?php endif; ?>
    </button>
    <div class="dropdown-content">
        <?php if (isset($_SESSION['nama'])): ?>
            <a href="logout.php" class="sign-in-btn">Logout</a>
        <?php else: ?>
            <a href="login.php" class="sign-in-btn">Sign in</a>
         <?php endif; ?>
     </div>
    </div>

          <div class="dropdown-content">
           <button class="sign-in-btn" onclick="window.location.href='login.php'">Sign in</button>
            <div class="dropdown-sections">
              <div>
                <h4>Your Lists</h4>
                <ul>
                  <li><a href="#">Create a List</a></li>
                  <li><a href="#">Find a List or Registry</a></li>
                </ul>
              </div>
              <div>
                <h4>Your Account</h4>
                <ul>
                  <li><a href="#">Account</a></li>
                  <li><a href="../../controllers/pelanggan/logout.php" style="  color: #007185;">Logout</a></li>
                  <li><a href="#">Orders</a></li>
                  <li><a href="#">Recommendations</a></li>
                  <li><a href="#">Browsing History</a></li>
                  <li><a href="#">Watchlist</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="nav-return border">
          <p><span>Returns</span></p>
          <p class="nav-second">& Orders</p>
        </div>

        <div class="nav-cart border">
          <i class="fa-solid fa-cart-shopping"></i>
          Cart
        </div>
      </div>

      <div class="panel">
        <div class="panel-all">
          <i class="fa-solid fa-bars"></i>
          ALL
        </div>
        <div class="panel-options">
          <p>Today's Deals</p>
          <p>Customer Service</p>
          <p>Registry</p>
          <p>Gift Cards</p>
          <p>Sell</p>
        </div>
      </div>
    </header>

<div class="container">
        <!-- Hero Banner/Slider -->
        <div class="hero-banner">
            <div class="hero-slider">
                <div class="slide">
                <img src="../../assets/images/image.png" alt="Little M Night Light" title="Little M Night Light">
                    <div class="slide-content">
                      
                        <p class="slide-desc">Produk pilihan untuk kebutuhan sehari-hari</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="categories">
            <div class="category">
                <div class="category-icon">🏠</div>
                <a href="kategori.php?kategori=rumah" class= "category-name" >Rumah</a>
            </div>
            <div class="category">
                <div class="category-icon">🧸</div>
               <a href="kategori.php?kategori=mainan" class= "category-name" >Mainan</a>
            </div>
            <div class="category">
                <div class="category-icon">💄</div>
               <a href="kategori.php?kategori=kosmetik" class= "category-name" >Kosmetik</a>
            </div>
            <div class="category">
                <div class="category-icon">🎒</div>
                <a href="kategori.php?kategori=tas" class= "category-name" >Tas</a>
            </div>
            <div class="category">
                <div class="category-icon">🧦</div>
                <a href="kategori.php?kategori=fashion" class= "category-name" >Fashion</a>
            </div>
            <div class="category">
                <div class="category-icon">🎮</div>
                <a href="kategori.php?kategori=digital" class= "category-name" >Digital</a>
            </div>
            <div class="category">
                <div class="category-icon">🔌</div>
                <a href="kategori.php?kategori=elektronik" class= "category-name" >Elektronik</a>
            </div>
            <div class="category">
                <div class="category-icon">✏️</div>
                <a href="kategori.php?kategori=alat_tulis" class= "category-name" >Alat Tulis</a>
            </div>
        </div>

        <!-- Featured Products -->
        <h2 class="section-title">✨ Produk Unggulan</h2>
<div class="products">
  <?php while ($row = mysqli_fetch_assoc($unggulan)) : ?>
    <div class="product">
      <div class="product-img">
        <img src="../../assets/images/<?= !empty($row['gambar']) ? $row['gambar'] : 'default.png' ?>" alt="<?= $row['nama_produk'] ?>">
      </div>
      <div class="product-info">
        <div class="product-name"><?= $row['nama_produk'] ?></div>
        <div class="product-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
      </div>
    </div>
  <?php endwhile; ?>
</div>


        <!-- Promo Banners -->
        <div class="promo-banners">
            <div class="promo-banner">
                <img src="../../assets/images/image2.png" alt="Promo Miniso">
            </div>
            <div class="promo-banner">
                <img src="../../assets/images/image3.png" alt="Promo Miniso">
            </div>
        </div>

        <!-- New Arrivals -->
       <h2 class="section-title">🆕 Produk Terbaru</h2>
        <div class="products">
        <?php while ($row = mysqli_fetch_assoc($terbaru)) : ?>
            <div class="product">
            <div class="product-img">
                <img src="../../assets/images/<?= !empty($row['gambar']) ? $row['gambar'] : 'default.png' ?>" alt="<?= $row['nama_produk'] ?>">
            </div>
            <div class="product-info">
                <div class="product-name"><?= $row['nama_produk'] ?></div>
                <div class="product-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
            </div>
            </div>
        <?php endwhile; ?>
        </div>
    </div>

<footer class="footer">
        <div class="container">
            <!-- Zalora Brand and Description -->
            <div class="footer-top">
                <div class="footer-brand">
                    <h2 class="zalora-logo">LORA</h2>
                    <p class="brand-description">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tempor quam vitae imperdiet dictum. 
                        Ut blandit justo id neque euismod, vel mattis erat mattis. 
                        Quisque pretium diam eget hendrerit faucibus. Suspendisse in ante vitae nunc imperdiet pellentesque a vel nunc. 
                        Duis sed dui turpis. Ut in eros nec lectus consequat commodo. Nullam ut mauris sit amet ante varius placerat ut a lacus. 
                        Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut luctus accumsan tempor.
                    </p>
                    <p class="brand-tagline">Bersama LORA, You Own Now.</p>
                </div>

                <!-- Consumer Support -->
                <div class="consumer-support">
                    <p class="support-title">Layanan Pengaduan Konsumen</p>
                    <p class="support-brand">LORA</p>
                    <p class="support-email">E-mail: customer@id.X.com</p>
                    <p class="support-dept">Direktorat Jenderal Perlindungan Konsumen dan<br>Tertib Niaga Kementerian Perdagangan RI</p>
                    <p class="support-whatsapp">WhatsApp: +62 853 1111 5555</p>
                </div>
            </div>

            <!-- Main Footer Navigation -->
            <div class="footer-main">
                <!-- Service Links Column -->
                <div class="footer-col">
                    <h4>LAYANAN</h4>
                    <ul>
                        <li><a href="#">Bantuan</a></li>
                        <li><a href="#">Cara Pengembalian</a></li>
                        <li><a href="#">Product Index</a></li>
                        <li><a href="#">Promo Partner Kami</a></li>
                        <li><a href="#">Konfirmasi Transfer</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                        <li><a href="#">Cara Berjualan</a></li>
                        <li><a href="#">Pengembalian Ongkir</a></li>
                        <li><a href="#">Status Order</a></li>
                    </ul>
                </div>

                <!-- About Us Column -->
                <div class="footer-col">
                    <h4>TENTANG KAMI</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Promosikan Brand Anda</a></li>
                        <li><a href="#">Pers/Media</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Persyaratan & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Responsible Disclosure</a></li>
                        <li><a href="#">Influencer Program</a></li>
                    </ul>
                </div>

                <!-- Newsletter Subscription -->
                <div class="footer-col subscribe-col">
                    <h4>ANDA BARU DI LORA</h4>
                    <p class="subscribe-text">Dapatkan berita mode terbaru dan peluncuran produk hanya dengan subscribe newsletter kami.</p>
                    
                    <div class="subscribe-form">
                        <p class="email-label">Alamat email Kamu</p>
                        <input type="email" placeholder="Alamat email Anda">
                        
                        <div class="gender-buttons">
                            <button class="gender-btn women-btn">WANITA</button>
                            <button class="gender-btn men-btn">PRIA</button>
                        </div>
                        
                        <p class="privacy-note">Dengan mendaftar, Anda menyetujui persyaratan dalam <a href="#">Kebijakan Privasi</a> kami.</p>
                    </div>
                </div>
            </div>

            <!-- Social Media Section -->
            <div class="footer-social">
                <div class="social-find">
                    <h4>TEMUKAN KAMI</h4>
                    <div class="social-icons">
                        <a href="#" class="social-icon"><span>f</span></a>
                        <a href="#" class="social-icon"><span>📷</span></a>
                        <a href="#" class="social-icon"><span>🐦</span></a>
                        <a href="#" class="social-icon"><span>📰</span></a>
                        <a href="#" class="social-icon"><span>📌</span></a>
                        <a href="#" class="social-icon"><span>▶️</span></a>
                        <a href="#" class="social-icon"><span>🅰️</span></a>
                        <a href="#" class="social-icon"><span>in</span></a>
                    </div>
                </div>

                <div class="app-download">
                    <h4>DOWNLOAD APP KAMI SEKARANG</h4>
                    <div class="app-buttons">
                        <a href="#" class="app-btn">
                            <img src="/api/placeholder/135/40" alt="Google Play">
                        </a>
                        <a href="#" class="app-btn">
                            <img src="/api/placeholder/135/40" alt="App Store">
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Support and Legal -->
            <div class="footer-bottom">
                <div class="support-links">
                    <p>Anda punya pertanyaan? Kami siap membantu.</p>
                    <div class="support-contacts">
                        <a href="#">Kontak</a> | <a href="#">Bantuan</a>
                    </div>
                </div>

                <div class="legal-links">
                    <div class="legal-nav">
                        <a href="#">Tentang Lora</a> | <a href="#">Kebijakan Privasi</a> | <a href="#">Persyaratan dan Ketentuan</a>
                    </div>
                    <p class="copyright">&copy; 2012-2025 Lora</p>
                </div>
            </div>
        </div>
    </footer>
  </body>
</html>