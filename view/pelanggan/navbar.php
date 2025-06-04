<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
      <style>
    a {
      text-decoration: none;
      color: white;
    }
    
  </style>
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
          <select class="search-select" id="categorySelect" onchange="resizeSelect(this)">
            <option>All</option>
            <option>Rumah</option>
            <option>Mainan</option>
            <option>Kosmetik</option>
            <option>Tas</option>
            <option>Fashion</option>
            <option>Digital</option>
            <option>Elektronik</option>
            <option>Alat Tulis</option>
          </select>
          <input type="text" name="keyword" class="search-input" placeholder="Search product..." />
          <button type="submit" class="search-icon">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
          <!-- Elemen tersembunyi untuk mengukur lebar teks -->
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
                  <li><a href="account.php">Account</a></li>
                  <li><a href="../../controllers/pelanggan/logout.php" style="  color:rgb(255, 1, 1);">Logout</a></li>
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
            <!-- Digital Content & Devices -->
            <div class="sidebar-section">
                <h4>Digital Content & Devices</h4>
                <ul class="sidebar-menu">
                    <li class="expandable">
                        <a href="#">Amazon Music</a>
                        <ul class="sub-menu">
                            <li><a href="#">Free Streaming</a></li>
                            <li><a href="#">Prime Music</a></li>
                            <li><a href="#">Amazon Music Unlimited</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Kindle E-readers & Books</a></li>
                    <li><a href="#">Amazon Appstore</a></li>
                </ul>
            </div>

            <!-- Shop by Department -->
            <div class="sidebar-section">
                <h4>Shop by Department</h4>
                <ul class="sidebar-menu">
                    <li class="expandable">
                        <a href="#">Electronics</a>
                        <ul class="sub-menu">
                            <li><a href="#">Smartphones</a></li>
                            <li><a href="#">Laptops</a></li>
                            <li><a href="#">Cameras</a></li>
                            <li><a href="#">Headphones</a></li>
                        </ul>
                    </li>
                    <li class="expandable">
                        <a href="#">Computers</a>
                        <ul class="sub-menu">
                            <li><a href="#">Laptops</a></li>
                            <li><a href="#">Desktops</a></li>
                            <li><a href="#">Tablets</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Smart Home</a></li>
                    <li><a href="#">Arts & Crafts</a></li>
                    <li><a href="#">Automotive</a></li>
                    <li><a href="#">Baby</a></li>
                    <li><a href="#">Beauty and Personal Care</a></li>
                    <li><a href="#">Books</a></li>
                    <li><a href="#">Fashion</a></li>
                </ul>
            </div>

            <!-- Programs & Features -->
            <div class="sidebar-section">
                <h4>Programs & Features</h4>
                <ul class="sidebar-menu">
                    <li><a href="#">Gift Cards</a></li>
                    <li><a href="#">Amazon Live</a></li>
                    <li><a href="#">International Shopping</a></li>
                    <li><a href="#">Amazon Second Chance</a></li>
                </ul>
            </div>

            <!-- Help & Settings -->
            <div class="sidebar-section">
                <h4>Help & Settings</h4>
                <ul class="sidebar-menu">
                    <li><a href="#">Your Account</a></li>
                    <li><a href="#">Customer Service</a></li>
                    <li><a href="#">Sign in</a></li>
                </ul>
            </div>
        </div>
    </div>

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