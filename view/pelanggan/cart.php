<?php
session_start();
require_once '../../config/Connection.php';

// Redirect jika belum login
if (!isset($_SESSION['nama'])) {
    header('Location: login.php');
    exit();
}

$id_pengguna = $_SESSION['nama'];

// Ambil data cart dengan join produk
$query = "SELECT c.*, p.nama_produk, p.harga, p.gambar, p.kategori,
          (c.quantity * p.harga) as subtotal
          FROM cart c 
          JOIN produk p ON c.id_produk = p.id_produk 
          WHERE c.id_pengguna = ? 
          ORDER BY c.created_at DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_pengguna);
mysqli_stmt_execute($stmt);
$cart_items = mysqli_stmt_get_result($stmt);

// Hitung total
$total = 0;
$cart_array = [];
while ($row = mysqli_fetch_assoc($cart_items)) {
    $cart_array[] = $row;
    $total += $row['subtotal'];
}

// Handle update quantity
if ($_POST && isset($_POST['action'])) {
    if ($_POST['action'] == 'update_quantity') {
        $id_cart = $_POST['id_cart'];
        $quantity = max(1, intval($_POST['quantity'])); // Minimal 1
        
        $update_query = "UPDATE cart SET quantity = ? WHERE id_cart = ? AND id_pengguna = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $id_cart, $id_pengguna);
        mysqli_stmt_execute($stmt);
        
        header('Location: cart.php');
        exit();
    }
    
    if ($_POST['action'] == 'remove_item') {
        $id_cart = $_POST['id_cart'];
        
        $delete_query = "DELETE FROM cart WHERE id_cart = ? AND id_pengguna = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "ii", $id_cart, $id_pengguna);
        mysqli_stmt_execute($stmt);
        
        header('Location: cart.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Amazon.com</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        body {
            background-color: #f3f3f3;
            font-family: "Amazon Ember", Arial, sans-serif;
        }
        
        .cart-main-container {
            max-width: 1500px;
            margin: 20px auto;
            padding: 20px;
            display: flex;
            gap: 30px;
        }
        
        .cart-left {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .cart-right {
            width: 300px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .cart-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .cart-title {
            font-size: 28px;
            font-weight: 400;
            color: #0F1111;
            margin: 0;
        }
        
        .deselect-all {
            color: #007185;
            text-decoration: none;
            font-size: 14px;
            margin-top: 5px;
            display: inline-block;
        }
        
        .deselect-all:hover {
            color: #C7511F;
            text-decoration: underline;
        }
        
        .cart-empty {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .cart-empty i {
            font-size: 80px;
            margin-bottom: 20px;
            color: #ddd;
        }

        .cart-empty h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #0F1111;
        }

        .cart-empty a {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        
        .cart-empty a:hover {
            background: #e88b00;
        }
        
        .cart-empty p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            align-items: flex-start;
            padding: 20px 0;
            border-bottom: 1px solid #ddd;
            gap: 20px;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .item-checkbox {
            margin-top: 10px;
        }
        
        .item-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #007185;
        }
        
        .item-image {
            width: 180px;
            height: 180px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-size: 16px;
            font-weight: 400;
            color: #007185;
            margin-bottom: 5px;
            line-height: 1.3;
        }
        
        .item-name:hover {
            color: #C7511F;
            text-decoration: underline;
            cursor: pointer;
        }
        
        .item-author {
            color: #565959;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .item-badge {
            background: #ff9900;
            color: white;
            padding: 2px 6px;
            font-size: 11px;
            border-radius: 2px;
            margin-right: 8px;
            font-weight: bold;
        }
        
        .item-category {
            color: #565959;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .item-stock {
            color: #007600;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .item-shipping {
            color: #565959;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .item-gift {
            color: #565959;
            font-size: 12px;
            margin-bottom: 15px;
        }
        
        .item-gift a {
            color: #007185;
            text-decoration: none;
        }
        
        .item-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            background: #f0f2f2;
            border: 1px solid #D5D9D9;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .quantity-btn {
            width: 29px;
            height: 29px;
            border: none;
            background: #f0f2f2;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #0F1111;
            transition: background 0.2s ease;
        }
        
        .quantity-btn:hover {
            background: #e3e6e6;
        }
        
        .quantity-input {
            width: 50px;
            text-align: center;
            border: none;
            background: white;
            padding: 7px 0;
            font-size: 14px;
            color: #0F1111;
        }
        
        .item-action-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .action-link {
            color: #007185;
            text-decoration: none;
            font-size: 12px;
            padding: 2px 0;
        }
        
        .action-link:hover {
            color: #C7511F;
            text-decoration: underline;
        }
        
        .item-price {
            font-size: 18px;
            font-weight: 700;
            color: #B12704;
            text-align: right;
            min-width: 80px;
        }
        
        .price-symbol {
            font-size: 12px;
            vertical-align: super;
        }
        
        .cart-summary {
            background: white;
            padding: 0;
        }
        
        .summary-subtotal {
            font-size: 18px;
            color: #0F1111;
            margin-bottom: 15px;
        }
        
        .summary-subtotal .amount {
            font-weight: 700;
        }
        
        .prime-badge {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            gap: 8px;
        }
        
        .prime-logo {
            color: #007185;
            font-weight: bold;
            font-size: 16px;
        }
        
        .prime-text {
            font-size: 12px;
            color: #0F1111;
            line-height: 1.3;
        }
        
        .prime-link {
            color: #007185;
            text-decoration: none;
            font-size: 12px;
        }
        
        .prime-link:hover {
            text-decoration: underline;
        }
        
        .checkout-btn {
            width: 100%;
            background: #FFD814;
            border: 1px solid #FCD200;
            border-radius: 20px;
            padding: 8px 20px;
            font-size: 13px;
            color: #0F1111;
            cursor: pointer;
            transition: background 0.2s ease;
            margin-bottom: 10px;
        }
        
        .checkout-btn:hover {
            background: #F7CA00;
        }
        
        .recent-items {
            margin-top: 30px;
        }
        
        .recent-items h3 {
            font-size: 16px;
            color: #0F1111;
            margin-bottom: 15px;
        }
        
        .recent-item {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .recent-item-image {
            width: 60px;
            height: 60px;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .recent-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .recent-item-info {
            flex: 1;
        }
        
        .recent-item-name {
            font-size: 12px;
            color: #007185;
            line-height: 1.3;
            margin-bottom: 2px;
        }
        
        .recent-item-author {
            font-size: 11px;
            color: #565959;
            margin-bottom: 2px;
        }
        
        .recent-item-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 2px;
        }
        
        .stars {
            color: #ffa41c;
            font-size: 11px;
        }
        
        .rating-count {
            color: #007185;
            font-size: 11px;
        }
        
        .recent-item-price {
            font-size: 11px;
            color: #0F1111;
        }
        
        .recent-item-offers {
            font-size: 11px;
            color: #565959;
        }
        
        .buy-options {
            text-align: right;
        }
        
        .see-options {
            color: #007185;
            font-size: 11px;
            text-decoration: none;
            border: 1px solid #D5D9D9;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }
        
        .see-options:hover {
            background: #f0f2f2;
            text-decoration: none;
        }
        
        @media (max-width: 1024px) {
            .cart-main-container {
                flex-direction: column;
            }
            
            .cart-right {
                width: 100%;
                position: static;
            }
        }
        
        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
            }
            
            .item-image {
                width: 150px;
                height: 150px;
                align-self: center;
            }
            
            .item-actions {
                justify-content: center;
            }
            
            .item-price {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="cart-main-container">
        <div class="cart-left">
            <div class="cart-header">
                <h1 class="cart-title">Shopping Cart</h1>
                <?php if (!empty($cart_array)): ?>
                    <a href="#" class="deselect-all">Deselect all items</a>
                <?php endif; ?>
            </div>

            <?php if (empty($cart_array)): ?>
                <div class="cart-empty">
                    <i class="fa-solid fa-shopping-cart"></i>
                    <h2>Your Amazon Cart is empty</h2>
                    <p>Shop today's deals</p>
                    <a href="index.php">Continue Shopping</a>
                </div>
            <?php else: ?>
                <div class="cart-items">
                    <?php foreach ($cart_array as $item): ?>
                        <div class="cart-item">
                            <div class="item-checkbox">
                                <input type="checkbox" checked>
                            </div>
                            
                            <div class="item-image">
                                <img src="../../assets/images-produk/<?= !empty($item['gambar']) ? $item['gambar'] : 'default.png' ?>" 
                                     alt="<?= htmlspecialchars($item['nama_produk']) ?>">
                            </div>
                            
                            <div class="item-details">
                                <div class="item-name"><?= htmlspecialchars($item['nama_produk']) ?></div>
                                <div class="item-author">by LORA Store</div>
                                <div class="item-category">
                                    <span class="item-badge">#1 Best Seller</span>
                                    in <?= htmlspecialchars($item['kategori']) ?>
                                </div>
                                <div class="item-category">Paperback</div>
                                <div class="item-stock">In Stock</div>
                                <div class="item-shipping">Shipped from: LORA-Indonesia</div>
                                <div class="item-gift">Gift options not available. <a href="#">Learn more</a></div>
                                
                                <div class="item-actions">
                                    <form method="POST" class="quantity-control">
                                        <input type="hidden" name="action" value="update_quantity">
                                        <input type="hidden" name="id_cart" value="<?= $item['id_cart'] ?>">
                                        
                                        <button type="button" class="quantity-btn" onclick="decreaseQuantity(<?= $item['id_cart'] ?>)">
                                            −
                                        </button>
                                        
                                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" 
                                               min="1" class="quantity-input" id="qty-<?= $item['id_cart'] ?>"
                                               onchange="updateQuantity(<?= $item['id_cart'] ?>)">
                                        
                                        <button type="button" class="quantity-btn" onclick="increaseQuantity(<?= $item['id_cart'] ?>)">
                                            +
                                        </button>
                                    </form>
                                    
                                    <div class="item-action-links">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="remove_item">
                                            <input type="hidden" name="id_cart" value="<?= $item['id_cart'] ?>">
                                            <button type="submit" class="action-link" style="border: none; background: none; cursor: pointer;" onclick="return confirm('Remove this item from cart?')">
                                                Delete
                                            </button>
                                        </form>
                                        <a href="#" class="action-link">Save for later</a>
                                        <a href="#" class="action-link">Compare with similar items</a>
                                        <a href="#" class="action-link">Share</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="item-price">
                                <span class="price-symbol">$</span><?= number_format($item['harga'] / 15000, 2) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div style="text-align: right; padding: 20px 0; border-top: 1px solid #ddd; margin-top: 20px;">
                        <div style="font-size: 18px; color: #0F1111;">
                            Subtotal (<?= count($cart_array) ?> item<?= count($cart_array) > 1 ? 's' : '' ?>): 
                            <span style="font-weight: 700; color: #B12704;">
                                <span class="price-symbol">$</span><?= number_format($total / 15000, 2) ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($cart_array)): ?>
        <div class="cart-right">
            <div class="cart-summary">
                <div class="summary-subtotal">
                    Subtotal (<?= count($cart_array) ?> item<?= count($cart_array) > 1 ? 's' : '' ?>): 
                    <span class="amount"><span class="price-symbol">$</span><?= number_format($total / 15000, 2) ?></span>
                </div>
                
                <div class="prime-badge">
                    <span class="prime-logo">prime</span>
                    <div>
                        <div class="prime-text">Free fast delivery. No order minimum. Exclusive savings.</div>
                        <a href="#" class="prime-link">Start your 30-day free trial of Prime.</a>
                    </div>
                </div>
                
                <button class="checkout-btn" onclick="proceedToCheckout()">
                    Proceed to checkout
                </button>
                
                <div style="text-align: center; margin-top: 10px;">
                    <a href="#" class="prime-link">Join Prime</a>
                </div>
            </div>
            
            <div class="recent-items">
                <h3>Your recently viewed items</h3>
                
                <!-- Sample recent item - you can make this dynamic -->
                <div class="recent-item">
                    <div class="recent-item-image">
                        <img src="../../assets/images-produk/default.png" alt="Recent Item">
                    </div>
                    <div class="recent-item-info">
                        <div class="recent-item-name">The Housemaid</div>
                        <div class="recent-item-author">Freida McFadden</div>
                        <div class="recent-item-rating">
                            <span class="stars">★★★★☆</span>
                            <span class="rating-count">505,642</span>
                        </div>
                        <div class="recent-item-price">Paperback</div>
                        <div class="recent-item-offers">28 offers from <span class="price-symbol">$</span>2.49</div>
                    </div>
                    <div class="buy-options">
                        <a href="#" class="see-options">See all buying options</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        function increaseQuantity(cartId) {
            const input = document.getElementById('qty-' + cartId);
            input.value = parseInt(input.value) + 1;
            updateQuantity(cartId);
        }
        
        function decreaseQuantity(cartId) {
            const input = document.getElementById('qty-' + cartId);
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                updateQuantity(cartId);
            }
        }
        
        function updateQuantity(cartId) {
            const form = document.querySelector(`input[value="${cartId}"]`).closest('form');
            form.submit();
        }
        
        function proceedToCheckout() {
            // Implementasi checkout
            alert('Proceeding to checkout...');
        }
        
        // Handle checkbox selection
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox input[type="checkbox"]');
            const deselectAll = document.querySelector('.deselect-all');
            
            if (deselectAll) {
                deselectAll.addEventListener('click', function(e) {
                    e.preventDefault();
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                });
            }
        });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>