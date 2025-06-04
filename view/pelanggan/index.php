<?php
require_once '../../config/Connection.php';
$unggulan = mysqli_query($conn, "SELECT * FROM produk WHERE unggulan = 1 LIMIT 4");
$terbaru = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 4");
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
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
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../assets/css/style.css" />
  </head>
  <body>
    
    <header>
       <div class="navbar">
        <div class="nav-logo border">
          <a href="index.php">
            <div class="logo"></div>
          </a>
        </div>
        <div class="nav-address">
        <i class="fa-solid fa-location-dot"></i>
        <div class="address-text">
          <span class="add-first">Deliver to</span>
          <span class="add-second">Indonesia</span>
        </div>
      </div>

     <form action="search.php" method="GET" class="nav-search">
                <div class="nav-search">
                    <select class="search-select" name="category" id="categorySelect" onchange="resizeSelect(this)">
                        <option value="All" <?= "kategori.php?kategori=Semua" ? 'selected' : '' ?>>All</option>
                        <option value="Rumah" <?= "kategori.php?kategori=Rumah" ? 'selected' : '' ?>>Rumah</option>
                        <option value="Mainan" <?= "kategori.php?kategori=Mainan" ? 'selected' : '' ?>>Mainan</option>
                        <option value="Kosmetik" <?= "kategori.php?kategori=Kosmetik" ? 'selected' : '' ?>>Kosmetik</option>
                        <option value="Tas" <?= "kategori.php?kategori=Tas" ? 'selected' : '' ?>>Tas</option>
                        <option value="Fashion" <?= "kategori.php?kategori=Fashion" ? 'selected' : '' ?>>Fashion</option>
                        <option value="Digital" <?= "kategori.php?kategori=Digital" ? 'selected' : '' ?>>Digital</option>
                        <option value="Elektronik" <?= "kategori.php?kategori=Elektronik" ? 'selected' : '' ?>>Elektronik</option>
                        <option value="Alat Tulis" <?= "kategori.php?kategori=alat_tulis" ? 'selected' : '' ?>>Alat Tulis</option>
                    </select>
                    <input type="text" name="keyword" class="search-input" placeholder="  Search product..." value="<?= htmlspecialchars($keyword) ?>"/>
                    <button type="submit" class="search-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <span id="widthHelper" style="visibility:hidden; position:absolute; white-space:nowrap; font-size:14px;"></span>
                </div>
            </form>
        
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
        <button class="account-btn">
        <?php if (isset($_SESSION['nama'])): ?>
             Hello, <?= htmlspecialchars($_SESSION['nama']) ?><br><strong>Account & Lists</strong>
        <?php else: ?>
             Hello, sign in<br><strong>Account & Lists</strong>
        <?php endif; ?>
    </button>
    <div class="dropdown-content">
        <?php if (!isset($_SESSION['nama'])): ?>
            <button class="sign-in-btn" onclick="window.location.href='login.php'">Sign in</button>
        <?php endif; ?>
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
                  <li><a href="account.php">Account</a></li>
                  <?php if (isset($_SESSION['nama'])): ?>
                  <li><a href="../../controllers/pelanggan/logout.php" style="color:rgb(255, 1, 1);">Logout</a></li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
     </div>
  </div>

        <a href="returns-orders.php">
        <div class="nav-return border">
          <p><span>Returns</span></p>
          <p class="nav-second">& Orders</p>
        </div>
        </a>

        <a href="cart.php">
        <div class="nav-cart border">
          <i class="fa-solid fa-cart-shopping"></i>
          Cart
        </div>
        </a>
      </div>

      <div class="panel">
        <div class="panel-all">
          <i class="fa-solid fa-bars"></i>
          ALL
        </div>
        <div class="panel-options" class="a">
          <a href="todays-deal.php"><p>Today's Deals</p></a>
          <a href="customer-service.php"><p>Customer Service</p></a>
          <a href="registry.php"><p>Registry</p></a>
          <a href="gift-card.php"><p>Gift Cards</p></a>
        </div>
      </div>
    </header>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
             <?php if (isset($_SESSION['nama'])): ?>
             Hello, <?= htmlspecialchars($_SESSION['nama']) ?>
             <?php endif; ?>
            <button class="sidebar-close" id="sidebarClose">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-content">
            <!-- Shop by Department -->
            <div class="sidebar-section">
                <h4>Shop by Department</h4>
                <ul class="sidebar-menu">
                    <li><a href="kategori.php?kategori=rumah" class= "category-name" >Rumah</a></li>
                    <li><a href="kategori.php?kategori=mainan" class= "category-name" >Mainan</a></li>
                    <li><a href="kategori.php?kategori=kosmetik" class= "category-name" >Kosmetik</a></li>
                    <li><a href="kategori.php?kategori=tas" class= "category-name" >Tas</a></li>
                    <li><a href="kategori.php?kategori=fashion" class= "category-name" >Fashion</a></li>
                    <li><a href="kategori.php?kategori=digital" class= "category-name" >Digital</a></li>
                    <li><a href="kategori.php?kategori=elektronik" class= "category-name" >Elektronik</a></li>
                    <li><a href="kategori.php?kategori=alat_tulis" class= "category-name" >Alat Tulis</a></li>
                </ul>
            </div>

            <!-- Programs & Features -->
            <div class="sidebar-section">
                <h4>Programs & Features</h4>
                <ul class="sidebar-menu">
                    <li><a href="gift-card.php">Gift Cards</a></li>
                </ul>
            </div>

            <!-- Help & Settings -->
            <div class="sidebar-section">
                <h4>Help & Settings</h4>
                <ul class="sidebar-menu">
                    <li><a href="account.php">Your Account</a></li>
                    <li><a href="customer-service.php">Customer Service</a></li>
                    <li><a href="register.php">Sign in</a></li>
                </ul>
            </div>
        </div>
    </div>

<div class="container">
        <!-- Hero Banner/Slider -->
        <div class="hero-banner">
            <div class="hero-slider">
                <div class="slide">
                <img src="../../assets/images/image-banner.png" alt="Little M Night Light" title="Little M Night Light">
                    <div class="slide-content">
                      
                        <p class="slide-desc">Produk pilihan untuk kebutuhan sehari-hari</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="categories">
            <div class="category">
              <a href="kategori.php?kategori=rumah" class= "category-name" >
                <div class="category-icon"><i class="fas fa-couch"></i></div>
                <p class="category-name">Rumah</p>
                </a>
            </div>
            <div class="category">
              <a href="kategori.php?kategori=mainan" class= "category-name" >
                <div class="category-icon"><i class="fas fa-puzzle-piece"></i> </div>
                <p class="category-name">Mainan</p>
               </a>
            </div>
            <div class="category">
               <a href="kategori.php?kategori=kosmetik">
                <div class="category-icon"><i class="fas fa-paint-brush"></i></div>
                <p  class= "category-name">Kosmetik</p>
               </a>
            </div>
            <div class="category">
              <a href="kategori.php?kategori=tas" class= "category-name" >
                <div class="category-icon"><i class="fas fa-shopping-bag"></i></div>
                <a href="kategori.php?kategori=tas" class= "category-name" >Tas</a>
            </div>
            <div class="category">
              <a href="kategori.php?kategori=tas" class= "category-name" >
                <div class="category-icon"><i class="fas fa-tshirt"></i></div>
                <p class="category-name">Fashion</p>  
              </a>
            </div>
            <div class="category">
              <a href="kategori.php?kategori=digital" class= "category-name" >
                <div class="category-icon"><i class="fas fa-tablet-alt"></i></div>
                <p class="category-name">Digital</p>
              </a>
            </div>
            <div class="category">
              <a href="kategori.php?kategori=elektronik" class= "category-name" >
                <div class="category-icon"><i class="fas fa-tv"></i></div>
                <p>Elektronik</p>
              </a>
            </div>
            <div class="category">
              <a href="kategori.php?kategori=alat_tulis" class= "category-name" >
                <div class="category-icon"><i class="fas fa-pen"></i></div>
                <p>Alat Tulis</p>
              </a>
            </div>
        </div>

        <!-- Featured Products -->
        <h2 class="section-title">✨ Produk Unggulan</h2>
<div class="products">
  <?php while ($row = mysqli_fetch_assoc($unggulan)) : ?>
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
          <form method="POST" action="../../controllers/pelanggan/add-to-cart.php" style="display: inline;">
              <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
              <input type="hidden" name="quantity" value="1">
              <button type="submit" class="btn-buy pre-order">+ Keranjang</button>
            </form>
          <button class="btn-buy buy-now">Beli</button>
        </div>
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

      <script>
      function resizeSelect(select) {
        const helper = document.getElementById('widthHelper');
        helper.textContent = select.options[select.selectedIndex].text;
        const computed = getComputedStyle(select);
        helper.style.font = computed.font;
        select.style.width = helper.offsetWidth + 48 + 'px'; // +30 for arrow padding
      }

      // Jalankan sekali saat halaman load
      document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById("categorySelect");
        resizeSelect(select);

        if (select.offsetWidth < 60) {
            select.style.width = '56px';
          }
      });
      </script>

      <script>
// JavaScript untuk fitur sidebar ALL menu
document.addEventListener("DOMContentLoaded", function() {
    // Get elements
    const panelAll = document.querySelector('.panel-all');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarClose = document.getElementById('sidebarClose');

    // Function to open sidebar
    function openSidebar() {
        sidebar.classList.add('active');
        sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Function to close sidebar
    function closeSidebar() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Event listeners
    if (panelAll) {
        panelAll.addEventListener('click', function(e) {
            e.preventDefault();
            openSidebar();
        });
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', function(e) {
            e.preventDefault();
            closeSidebar();
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function(e) {
            if (e.target === sidebarOverlay) {
                closeSidebar();
            }
        });
    }

    // Handle expandable menu items
    const expandableItems = document.querySelectorAll('.sidebar .expandable > a');
    expandableItems.forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            const subMenu = parent.querySelector('.sub-menu');
            
            if (subMenu) {
                parent.classList.toggle('expanded');
                subMenu.classList.toggle('expanded');
            }
        });
    });

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    });
});
</script>

  </body>
</html>