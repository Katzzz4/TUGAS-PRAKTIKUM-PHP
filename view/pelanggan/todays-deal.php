<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/Connection.php';

// Query untuk mengambil produk dengan diskon (misalnya produk unggulan sebagai deal)
$deals_query = "SELECT * FROM produk WHERE unggulan = 1 ORDER BY RAND() LIMIT 12";
$deals_result = mysqli_query($conn, $deals_query);

// Query untuk Lightning Deals (produk terbaru sebagai contoh)
$lightning_query = "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 6";
$lightning_result = mysqli_query($conn, $lightning_query);

// Query untuk Deal of the Day (produk random)
$daily_deal_query = "SELECT * FROM produk ORDER BY RAND() LIMIT 1";
$daily_deal_result = mysqli_query($conn, $daily_deal_query);
$daily_deal = mysqli_fetch_assoc($daily_deal_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Deals - Amazon.com</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../assets/css/style.css" />
    <style>
        /* Today's Deals Specific Styles */
        .deals-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .deals-header {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 40px 20px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .deals-header h1 {
            font-size: 3rem;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .deals-header p {
            font-size: 1.2rem;
            margin: 10px 0 0 0;
            opacity: 0.9;
        }

        .countdown-timer {
            background: rgba(255,255,255,0.2);
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            display: inline-block;
        }

        .timer-display {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .deal-section {
            margin-bottom: 40px;
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #e74c3c;
        }

        .deal-of-day {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .deal-of-day-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            align-items: center;
        }

        .deal-of-day img {
            width: 100%;
            max-width: 300px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .deal-info h3 {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .deal-price {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 20px 0;
        }

        .current-price {
            font-size: 2.5rem;
            font-weight: bold;
            color: #f39c12;
        }

        .original-price {
            font-size: 1.5rem;
            text-decoration: line-through;
            opacity: 0.7;
        }

        .discount-badge {
            background: #e74c3c;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .deals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .deal-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
        }

        .deal-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }

        .deal-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-content {
            padding: 20px;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .card-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .card-current-price {
            font-size: 1.4rem;
            font-weight: bold;
            color: #e74c3c;
        }

        .card-original-price {
            font-size: 1rem;
            text-decoration: line-through;
            color: #7f8c8d;
        }

        .card-discount {
            background: #e74c3c;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .deal-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #f39c12;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .lightning-badge {
            background: #9b59b6;
        }

        .deal-timer {
            background: #34495e;
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 15px;
        }

        .add-to-cart-btn {
            width: 100%;
            background: #f39c12;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background: #e67e22;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .filter-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #3498db;
            background: white;
            color: #3498db;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: #3498db;
            color: white;
        }

        @media (max-width: 768px) {
            .deals-header h1 {
                font-size: 2rem;
            }
            
            .deal-of-day-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .deals-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-buttons {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="deals-container">
        <!-- Header Section -->
        <div class="deals-header">
            <h1><i class="fas fa-fire"></i> Today's Deals</h1>
            <p>Penawaran terbaik hari ini - jangan sampai terlewat!</p>
            <div class="countdown-timer">
                <div class="timer-display" id="countdown">
                    <i class="fas fa-clock"></i> Berakhir dalam: 23:59:42
                </div>
            </div>
        </div>

        <!-- Deal of the Day -->
        <?php if ($daily_deal): ?>
        <div class="deal-section">
            <h2 class="section-title">
                <i class="fas fa-crown"></i>
                Deal of the Day
            </h2>
            <div class="deal-of-day">
                <div class="deal-of-day-content">
                    <div>
                        <img src="../../assets/images-produk/<?= !empty($daily_deal['gambar']) ? $daily_deal['gambar'] : 'default.png' ?>" 
                             alt="<?= htmlspecialchars($daily_deal['nama_produk']) ?>">
                    </div>
                    <div class="deal-info">
                        <h3><?= htmlspecialchars($daily_deal['nama_produk']) ?></h3>
                        <div class="deal-price">
                            <span class="current-price">Rp <?= number_format($daily_deal['harga'] * 0.7, 0, ',', '.') ?></span>
                            <span class="original-price">Rp <?= number_format($daily_deal['harga'], 0, ',', '.') ?></span>
                            <span class="discount-badge">30% OFF</span>
                        </div>
                        <div class="deal-timer">
                            <i class="fas fa-clock"></i> Tersisa 18 jam 42 menit
                        </div>
                        <form method="POST" action="../../controllers/pelanggan/add-to-cart.php">
                            <input type="hidden" name="id_produk" value="<?= $daily_deal['id_produk'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-to-cart-btn">
                                <i class="fas fa-cart-plus"></i> Add to Cart - Deal Price!
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filter Section -->
        <div class="filter-section">
            <h3>Filter by Category:</h3>
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterDeals('all')">All Deals</button>
                <button class="filter-btn" onclick="filterDeals('elektronik')">Electronics</button>
                <button class="filter-btn" onclick="filterDeals('fashion')">Fashion</button>
                <button class="filter-btn" onclick="filterDeals('rumah')">Home</button>
                <button class="filter-btn" onclick="filterDeals('kosmetik')">Beauty</button>
            </div>
        </div>

        <!-- Lightning Deals -->
        <div class="deal-section">
            <h2 class="section-title">
                <i class="fas fa-bolt"></i>
                Lightning Deals
            </h2>
            <div class="deals-grid" id="lightningDeals">
                <?php 
                mysqli_data_seek($lightning_result, 0);
                while ($row = mysqli_fetch_assoc($lightning_result)): 
                    $discount = rand(20, 50);
                    $discounted_price = $row['harga'] * (1 - $discount/100);
                ?>
                    <div class="deal-card" data-category="elektronik">
                        <div class="deal-badge lightning-badge">
                            <i class="fas fa-bolt"></i> Lightning
                        </div>
                        <a href="detail-produk.php?id=<?= $row['id_produk'] ?>">
                            <img src="../../assets/images-produk/<?= !empty($row['gambar']) ? $row['gambar'] : 'default.png' ?>" 
                                 alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                        </a>
                        <div class="card-content">
                            <h3 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h3>
                            <div class="deal-timer">
                                <i class="fas fa-clock"></i> <?= rand(1, 6) ?> jam tersisa
                            </div>
                            <div class="card-price">
                                <span class="card-current-price">Rp <?= number_format($discounted_price, 0, ',', '.') ?></span>
                                <span class="card-original-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                                <span class="card-discount"><?= $discount ?>% OFF</span>
                            </div>
                            <form method="POST" action="../../controllers/pelanggan/add-to-cart.php">
                                <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="add-to-cart-btn">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- All Today's Deals -->
        <div class="deal-section">
            <h2 class="section-title">
                <i class="fas fa-tags"></i>
                All Today's Deals
            </h2>
            <div class="deals-grid" id="allDeals">
                <?php 
                mysqli_data_seek($deals_result, 0);
                while ($row = mysqli_fetch_assoc($deals_result)): 
                    $discount = rand(15, 40);
                    $discounted_price = $row['harga'] * (1 - $discount/100);
                    $categories = ['elektronik', 'fashion', 'rumah', 'kosmetik'];
                    $random_category = $categories[array_rand($categories)];
                ?>
                    <div class="deal-card" data-category="<?= $random_category ?>">
                        <div class="deal-badge">
                            <i class="fas fa-percent"></i> Deal
                        </div>
                        <a href="detail-produk.php?id=<?= $row['id_produk'] ?>">
                            <img src="../../assets/images-produk/<?= !empty($row['gambar']) ? $row['gambar'] : 'default.png' ?>" 
                                 alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                        </a>
                        <div class="card-content">
                            <h3 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h3>
                            <div class="card-price">
                                <span class="card-current-price">Rp <?= number_format($discounted_price, 0, ',', '.') ?></span>
                                <span class="card-original-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                                <span class="card-discount"><?= $discount ?>% OFF</span>
                            </div>
                            <form method="POST" action="../../controllers/pelanggan/add-to-cart.php">
                                <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="add-to-cart-btn">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Countdown Timer
        function updateCountdown() {
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(0, 0, 0, 0);
            
            const diff = tomorrow - now;
            
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            document.getElementById('countdown').innerHTML = 
                `<i class="fas fa-clock"></i> Berakhir dalam: ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
        
        setInterval(updateCountdown, 1000);
        updateCountdown();

        // Deal Timer for individual cards
        function updateDealTimers() {
            const timers = document.querySelectorAll('.deal-timer');
            timers.forEach(timer => {
                if (timer.innerHTML.includes('jam tersisa')) {
                    const currentText = timer.innerHTML;
                    const hours = parseInt(currentText.match(/(\d+) jam/)[1]);
                    const minutes = Math.floor(Math.random() * 60);
                    const seconds = Math.floor(Math.random() * 60);
                    
                    timer.innerHTML = `<i class="fas fa-clock"></i> ${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} tersisa`;
                }
            });
        }
        
        updateDealTimers();
        setInterval(updateDealTimers, 1000);

        // Filter functionality
        function filterDeals(category) {
            const cards = document.querySelectorAll('.deal-card');
            const buttons = document.querySelectorAll('.filter-btn');
            
            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter cards
            cards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 100);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        }

        // Smooth scroll animation for deal cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.deal-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(50px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>