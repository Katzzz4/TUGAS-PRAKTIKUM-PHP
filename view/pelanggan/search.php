<?php
require_once '../../config/Connection.php';

// Ambil parameter pencarian
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'All';

// Query pencarian
$search_query = "SELECT * FROM produk WHERE 1=1";
$params = [];
$types = "";

// Filter berdasarkan keyword
if (!empty($keyword)) {
    $search_query .= " AND (nama_produk LIKE ? OR deskripsi LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
    $types .= "ss";
}

// Filter berdasarkan kategori
if ($category !== 'All' && !empty($category)) {
    $search_query .= " AND kategori = ?";
    $params[] = $category;
    $types .= "s";
}

$search_query .= " ORDER BY nama_produk ASC";

// Eksekusi query
if (!empty($params)) {
    $stmt = $conn->prepare($search_query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $search_results = $stmt->get_result();
} else {
    $search_results = mysqli_query($conn, $search_query);
}

$total_results = mysqli_num_rows($search_results);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Amazon.com</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/search.css">
</head>
<body>
    <!-- Header Navigation (sama seperti index.php) -->
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
                        <option value="All" <?= $category === 'All' ? 'selected' : '' ?>>All</option>
                        <option value="Rumah" <?= $category === 'Rumah' ? 'selected' : '' ?>>Rumah</option>
                        <option value="Mainan" <?= $category === 'Mainan' ? 'selected' : '' ?>>Mainan</option>
                        <option value="Kosmetik" <?= $category === 'Kosmetik' ? 'selected' : '' ?>>Kosmetik</option>
                        <option value="Tas" <?= $category === 'Tas' ? 'selected' : '' ?>>Tas</option>
                        <option value="Fashion" <?= $category === 'Fashion' ? 'selected' : '' ?>>Fashion</option>
                        <option value="Digital" <?= $category === 'Digital' ? 'selected' : '' ?>>Digital</option>
                        <option value="Elektronik" <?= $category === 'Elektronik' ? 'selected' : '' ?>>Elektronik</option>
                        <option value="Alat Tulis" <?= $category === 'Alat Tulis' ? 'selected' : '' ?>>Alat Tulis</option>
                    </select>
                    <input type="text" name="keyword" class="search-input" placeholder="Search product..." value="<?= htmlspecialchars($keyword) ?>"/>
                    <button type="submit" class="search-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <span id="widthHelper" style="visibility:hidden; position:absolute; white-space:nowrap; font-size:14px;"></span>
                </div>
            </form>
            
            <!-- Rest of navigation items -->
            <div class="map-icon border">
                <i class="fa-solid fa-earth-americas"></i>
                <div>
                    <p><b><pre> EN</pre></b></p>
                    <div class="sort-down">
                        <i class="fa-solid fa-sort-down"></i>
                    </div>
                </div>
            </div>

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
            <div class="panel-options">
                <a href="todays-deal.php"><p>Today's Deals</p></a>
                <a href="customer-service.php"><p>Customer Service</p></a>
                <a href="registry.php"><p>Registry</p></a>
                <a href="gift-card.php"><p>Gift Cards</p></a>
            </div>
        </div>
    </header>

    <!-- Search Results Content -->
    <div class="search-container">
        <!-- Search Info -->
        <div class="search-info">
            <div class="search-breadcrumb">
                <a href="index.php">Home</a> 
                <span class="breadcrumb-separator">›</span>
                <span>Search Results</span>
            </div>
            
            <div class="search-summary">
                <?php if (!empty($keyword)): ?>
                    <h1 class="search-title">
                        Search results for "<span class="search-keyword"><?= htmlspecialchars($keyword) ?></span>"
                    </h1>
                <?php else: ?>
                    <h1 class="search-title">
                        <?= $category !== 'All' ? 'Products in ' . htmlspecialchars($category) : 'All Products' ?>
                    </h1>
                <?php endif; ?>
                
                <div class="search-meta">
                    <span class="results-count"><?= $total_results ?> results found</span>
                    <?php if ($category !== 'All'): ?>
                        <span class="category-filter">in <?= htmlspecialchars($category) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div class="search-results">
            <?php if ($total_results > 0): ?>
                <div class="products-grid">
                    <?php while ($product = mysqli_fetch_assoc($search_results)): ?>
                        <div class="search-product-card">
                            <a href="detail-produk.php?id=<?= $product['id_produk'] ?>" class="product-link">
                                <div class="product-image">
                                    <img src="../../assets/images-produk/<?= !empty($product['gambar']) ? $product['gambar'] : 'default.png' ?>" 
                                         alt="<?= htmlspecialchars($product['nama_produk']) ?>">
                                    <?php if ($product['unggulan'] == 1): ?>
                                        <span class="featured-badge">⭐ Featured</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="product-details">
                                    <h3 class="product-name"><?= htmlspecialchars($product['nama_produk']) ?></h3>
                                    
                                    <?php if (!empty($product['deskripsi'])): ?>
                                        <p class="product-description">
                                            <?= htmlspecialchars(substr($product['deskripsi'], 0, 100)) ?>
                                            <?= strlen($product['deskripsi']) > 100 ? '...' : '' ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="product-category">
                                        <i class="fa-solid fa-tag"></i>
                                        <?= htmlspecialchars($product['kategori']) ?>
                                    </div>
                                    
                                    <div class="product-price">
                                        <span class="price-main">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                                    </div>
                                </div>
                            </a>
                            
                            <div class="product-actions">
                                <form method="POST" action="../../controllers/pelanggan/add-to-cart.php" class="add-to-cart-form">
                                    <input type="hidden" name="id_produk" value="<?= $product['id_produk'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn-add-cart">
                                        <i class="fa-solid fa-cart-plus"></i>
                                        Add to Cart
                                    </button>
                                </form>
                                <button class="btn-buy-now" onclick="window.location.href='detail-produk.php?id=<?= $product['id_produk'] ?>'">
                                    <i class="fa-solid fa-shopping-bag"></i>
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- No Results Found -->
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fa-solid fa-search"></i>
                    </div>
                    <h2>No results found</h2>
                    <p>We couldn't find any products matching your search.</p>
                    
                    <div class="search-suggestions">
                        <h3>Try these suggestions:</h3>
                        <ul>
                            <li>Check your spelling</li>
                            <li>Use different keywords</li>
                            <li>Try more general terms</li>
                            <li>Browse our categories</li>
                        </ul>
                    </div>
                    
                    <div class="alternative-actions">
                        <a href="index.php" class="btn-back-home">
                            <i class="fa-solid fa-home"></i>
                            Back to Home
                        </a>
                        <a href="index.php#categories" class="btn-browse-categories">
                            <i class="fa-solid fa-th-large"></i>
                            Browse Categories
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function resizeSelect(select) {
            const helper = document.getElementById('widthHelper');
            helper.textContent = select.options[select.selectedIndex].text;
            const computed = getComputedStyle(select);
            helper.style.font = computed.font;
            select.style.width = helper.offsetWidth + 48 + 'px';
        }

        document.addEventListener("DOMContentLoaded", function() {
            const select = document.getElementById("categorySelect");
            resizeSelect(select);

            if (select.offsetWidth < 60) {
                select.style.width = '56px';
            }
        });
    </script>
</body>
</html>