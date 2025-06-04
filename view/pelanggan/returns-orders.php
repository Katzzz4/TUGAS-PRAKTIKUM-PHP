<?php
session_start();
require_once '../../config/Connection.php';

// Redirect jika belum login
if (!isset($_SESSION['nama'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id_pengguna'] ?? 1;

// Function untuk generate order ID
function generateOrderId() {
    return 'ORD-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

// Function untuk generate return ID
function generateReturnId() {
    return 'RET-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

// Handle AJAX requests
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'add_to_cart':
            $product_id = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']) ?: 1;
            
            // Check if product exists
            $check_product = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $product_id");
            if (mysqli_num_rows($check_product) == 0) {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
                exit;
            }
            
            // Check if item already in cart
            $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE id_pengguna = $user_id AND id_produk = $product_id");
            if (mysqli_num_rows($check_cart) > 0) {
                // Update quantity
                $update_cart = mysqli_query($conn, "UPDATE cart SET quantity = quantity + $quantity WHERE id_pengguna = $user_id AND id_produk = $product_id");
            } else {
                // Insert new item
                $insert_cart = mysqli_query($conn, "INSERT INTO cart (id_pengguna, id_produk, quantity) VALUES ($user_id, $product_id, $quantity)");
            }
            
            echo json_encode(['success' => true, 'message' => 'Product added to cart']);
            exit;
            
        case 'create_order':
            // Simple order creation from cart
            $cart_items = mysqli_query($conn, "
                SELECT c.*, p.nama_produk, p.harga, p.gambar 
                FROM cart c 
                JOIN produk p ON c.id_produk = p.id_produk 
                WHERE c.id_pengguna = $user_id
            ");
            
            if (mysqli_num_rows($cart_items) == 0) {
                echo json_encode(['success' => false, 'message' => 'Cart is empty']);
                exit;
            }
            
            $total_amount = 0;
            $items = [];
            while ($item = mysqli_fetch_assoc($cart_items)) {
                $subtotal = $item['harga'] * $item['quantity'];
                $total_amount += $subtotal;
                $items[] = $item;
            }
            
            $order_id = generateOrderId();
            $shipping_address = $_POST['shipping_address'] ?? 'Default Address';
            $phone = $_POST['phone'] ?? '081234567890';
            
            // Insert order
            $insert_order = mysqli_query($conn, "
                INSERT INTO orders (order_id, id_pengguna, total_amount, shipping_address, phone_number) 
                VALUES ('$order_id', $user_id, $total_amount, '$shipping_address', '$phone')
            ");
            
            if ($insert_order) {
                $new_order_id = mysqli_insert_id($conn);
                
                // Insert order items
                foreach ($items as $item) {
                    $subtotal = $item['harga'] * $item['quantity'];
                    mysqli_query($conn, "
                        INSERT INTO order_items (id_order, id_produk, quantity, price, subtotal) 
                        VALUES ($new_order_id, {$item['id_produk']}, {$item['quantity']}, {$item['harga']}, $subtotal)
                    ");
                }
                
                // Clear cart
                mysqli_query($conn, "DELETE FROM cart WHERE id_pengguna = $user_id");
                
                echo json_encode(['success' => true, 'message' => 'Order created successfully', 'order_id' => $order_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create order']);
            }
            exit;
            
        case 'initiate_return':
            $order_id = $_POST['order_id'];
            $reason = $_POST['reason'] ?? 'Customer request';
            
            // Get order details
            $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE order_id = '$order_id' AND id_pengguna = $user_id AND status = 'delivered'");
            if (mysqli_num_rows($order_query) == 0) {
                echo json_encode(['success' => false, 'message' => 'Order not eligible for return']);
                exit;
            }
            
            $order = mysqli_fetch_assoc($order_query);
            $return_id = generateReturnId();
            
            // Insert return request
            $insert_return = mysqli_query($conn, "
                INSERT INTO returns (return_id, id_order, id_pengguna, reason, return_amount) 
                VALUES ('$return_id', {$order['id_order']}, $user_id, '$reason', {$order['total_amount']})
            ");
            
            if ($insert_return) {
                echo json_encode(['success' => true, 'message' => 'Return request submitted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to submit return request']);
            }
            exit;
            
        case 'cancel_order':
            $order_id = $_POST['order_id'];
            
            $cancel_order = mysqli_query($conn, "
                UPDATE orders 
                SET status = 'cancelled' 
                WHERE order_id = '$order_id' AND id_pengguna = $user_id AND status = 'processing'
            ");
            
            if ($cancel_order && mysqli_affected_rows($conn) > 0) {
                echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to cancel order']);
            }
            exit;
    }
}

// Get filter parameters
$time_period = $_GET['time_period'] ?? '3months';
$order_status = $_GET['order_status'] ?? 'all';
$search_term = $_GET['search'] ?? '';

// Build WHERE clause for filters
$where_conditions = ["o.id_pengguna = $user_id"];

if ($order_status != 'all') {
    $where_conditions[] = "o.status = '$order_status'";
}

if (!empty($search_term)) {
    $where_conditions[] = "(o.order_id LIKE '%$search_term%' OR p.nama_produk LIKE '%$search_term%')";
}

// Time period filter
switch ($time_period) {
    case '3months':
        $where_conditions[] = "o.created_at >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
        break;
    case '6months':
        $where_conditions[] = "o.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
        break;
    case 'year':
        $where_conditions[] = "o.created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        break;
}

$where_clause = implode(' AND ', $where_conditions);

// Query untuk mengambil pesanan user dengan items
$orders_query = "
    SELECT 
        o.*,
        GROUP_CONCAT(
            CONCAT(oi.quantity, 'x ', p.nama_produk, '|', p.harga, '|', COALESCE(p.gambar, 'default.png'))
            SEPARATOR '||'
        ) as items_data
    FROM orders o
    LEFT JOIN order_items oi ON o.id_order = oi.id_order
    LEFT JOIN produk p ON oi.id_produk = p.id_produk
    WHERE $where_clause
    GROUP BY o.id_order
    ORDER BY o.created_at DESC
";

$orders_result = mysqli_query($conn, $orders_query);

// Query untuk summary statistics
$summary_query = "
    SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_count,
        SUM(CASE WHEN status = 'shipped' THEN 1 ELSE 0 END) as shipped_count,
        SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing_count
    FROM orders 
    WHERE id_pengguna = $user_id
";
$summary_result = mysqli_query($conn, $summary_query);
$summary = mysqli_fetch_assoc($summary_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders - Returns & Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        /* Styles khusus untuk halaman Returns & Orders */
        .returns-orders-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
            min-height: calc(100vh - 200px);
        }

        .page-header {
           background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
            color: white;
            padding: 40px 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .breadcrumb {
            margin-bottom: 20px;
            padding: 10px 0;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb span {
            color: #6c757d;
            margin: 0 8px;
        }

        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .filter-row {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }

        .filter-select, .search-input-orders {
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            min-width: 200px;
        }

        .filter-select:focus, .search-input-orders:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-btn {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .orders-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border-left: 5px solid transparent;
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .summary-card.total { border-left-color: #667eea; }
        .summary-card.delivered { border-left-color: #28a745; }
        .summary-card.shipped { border-left-color: #ffc107; }
        .summary-card.processing { border-left-color: #17a2b8; }

        .summary-card .icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #667eea;
        }

        .summary-card.delivered .icon { color: #28a745; }
        .summary-card.shipped .icon { color: #ffc107; }
        .summary-card.processing .icon { color: #17a2b8; }

        .summary-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .summary-card p {
            color: #6c757d;
            font-weight: 500;
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .order-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px 25px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .order-info {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .order-detail {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .order-detail label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }

        .order-detail span {
            font-weight: 600;
            color: #2c3e50;
        }

        .order-status {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .status-delivered {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-shipped {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-processing {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #b8daff;
        }

        .order-body {
            padding: 25px;
        }

        .order-items {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .order-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            align-items: center;
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 10px;
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
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .item-price {
            color: #667eea;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .item-qty {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .order-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-track {
            background-color: #667eea;
            color: white;
        }

        .btn-track:hover {
            background-color: #5a6fd8;
            transform: translateY(-1px);
        }

        .btn-return {
            background-color: #28a745;
            color: white;
        }

        .btn-return:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        .btn-reorder {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-reorder:hover {
            background-color: #e0a800;
            transform: translateY(-1px);
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .empty-state .icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #adb5bd;
            margin-bottom: 20px;
        }

        .btn-shop-now {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-shop-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .returns-orders-container {
                padding: 15px;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-select, .search-input-orders {
                min-width: auto;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-info {
                gap: 20px;
            }

            .order-item {
                flex-direction: column;
                text-align: center;
            }

            .item-image {
                align-self: center;
            }

            .order-actions {
                flex-direction: column;
            }

            .action-btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
     <?php include 'navbar.php'; ?>

    <div class="returns-orders-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>›</span>
            <a href="account.php">Your Account</a>
            <span>›</span>
            <span>Your Orders</span>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-box"></i> Your Orders</h1>
            <p>Track packages, view order history, and manage returns</p>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Time Period</label>
                    <select class="filter-select" id="timePeriod">
                        <option value="3months">Past 3 months</option>
                        <option value="6months">Past 6 months</option>
                        <option value="year">This year</option>
                        <option value="all">All orders</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Order Status</label>
                    <select class="filter-select" id="orderStatus">
                        <option value="all">All orders</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Search Orders</label>
                    <input type="text" class="search-input-orders" placeholder="Search by order ID or product name" id="searchOrders">
                </div>
                <button class="search-btn" onclick="filterOrders()">
                    <i class="fas fa-search"></i>
                    Search Orders
                </button>
            </div>
        </div>

        <!-- Orders Summary -->
        <div class="orders-summary">
            <div class="summary-card total">
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3><?= $summary['total_orders'] ?></h3>
                <p>Total Orders</p>
            </div>
            <div class="summary-card delivered">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3><?= $summary['delivered_count'] ?></h3>
                <p>Delivered</p>
            </div>
            <div class="summary-card shipped">
                <div class="icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3><?= $summary['shipped_count'] ?></h3>
                <p>Shipped</p>
            </div>
            <div class="summary-card processing">
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3><?= $summary['processing_count'] ?></h3>
                <p>Processing</p>
            </div>
        </div>

        <!-- Orders List -->
        <div class="orders-list" id="ordersList">
            <?php if (mysqli_num_rows($orders_result) > 0): ?>
                <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                    <?php
                    // Parse items data dari GROUP_CONCAT
                    $items = [];
                    if (!empty($order['items_data'])) {
                        $items_array = explode('||', $order['items_data']);
                        foreach ($items_array as $item_data) {
                            $item_parts = explode('|', $item_data);
                            if (count($item_parts) >= 3) {
                                // Extract quantity and product name from first part (e.g., "2x Product Name")
                                $qty_and_name = $item_parts[0];
                                if (preg_match('/^(\d+)x\s*(.+)$/', $qty_and_name, $matches)) {
                                    $items[] = [
                                        'qty' => $matches[1],
                                        'name' => $matches[2],
                                        'price' => $item_parts[1],
                                        'image' => $item_parts[2]
                                    ];
                                }
                            }
                        }
                    }
                    ?>
                    <div class="order-card" data-status="<?= strtolower($order['status']) ?>" data-order-id="<?= $order['order_id'] ?>">
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-detail">
                                    <label>ORDER ID</label>
                                    <span><?= $order['order_id'] ?></span>
                                </div>
                                <div class="order-detail">
                                    <label>ORDER DATE</label>
                                    <span><?= date('M d, Y', strtotime($order['created_at'])) ?></span>
                                </div>
                                <div class="order-detail">
                                    <label>TOTAL</label>
                                    <span>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                                </div>
                                <div class="order-detail">
                                    <label>ITEMS</label>
                                    <span><?= count($items) ?> item(s)</span>
                                </div>
                            </div>
                            <div class="order-status status-<?= strtolower($order['status']) ?>">
                                <?= ucfirst($order['status']) ?>
                            </div>
                        </div>
                        <div class="order-body">
                            <div class="order-items">
                                <?php foreach ($items as $item): ?>
                                    <div class="order-item">
                                        <div class="item-image">
                                            <img src="../../assets/images-produk/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" onerror="this.src='../../assets/images-produk/default.png'">
                                        </div>
                                        <div class="item-details">
                                            <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                                            <div class="item-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                                            <div class="item-qty">Quantity: <?= $item['qty'] ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Shipping Address -->
                            <?php if (!empty($order['shipping_address'])): ?>
                                <div style="margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                    <strong>Shipping Address:</strong><br>
                                    <?= htmlspecialchars($order['shipping_address']) ?><br>
                                    <?php if (!empty($order['phone_number'])): ?>
                                        <strong>Phone:</strong> <?= htmlspecialchars($order['phone_number']) ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="order-actions">
                                <?php if (strtolower($order['status']) == 'delivered'): ?>
                                    <button class="action-btn btn-return" onclick="initiateReturn('<?= $order['order_id'] ?>')">
                                        <i class="fas fa-undo"></i>
                                        Return Items
                                    </button>
                                    <button class="action-btn btn-reorder" onclick="reorderItems('<?= $order['order_id'] ?>')">
                                        <i class="fas fa-redo"></i>
                                        Buy Again
                                    </button>
                                <?php elseif (strtolower($order['status']) == 'shipped'): ?>
                                    <button class="action-btn btn-track" onclick="trackOrder('<?= $order['order_id'] ?>')">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Track Package
                                    </button>
                                <?php elseif (strtolower($order['status']) == 'processing'): ?>
                                    <button class="action-btn btn-cancel" onclick="cancelOrder('<?= $order['order_id'] ?>')">
                                        <i class="fas fa-times"></i>
                                        Cancel Order
                                    </button>
                                <?php endif; ?>
                                <button class="action-btn btn-track" onclick="viewOrderDetails('<?= $order['order_id'] ?>')">
                                    <i class="fas fa-eye"></i>
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3>No orders found</h3>
                    <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                    <a href="index.php" class="btn-shop-now">
                        <i class="fas fa-shopping-cart"></i>
                        Start Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

     <?php include 'footer.php'; ?>

    <script>
        // Filter orders functionality
        function filterOrders() {
            const timePeriod = document.getElementById('timePeriod').value;
            const orderStatus = document.getElementById('orderStatus').value;
            const searchTerm = document.getElementById('searchOrders').value.toLowerCase();
            
            const orderCards = document.querySelectorAll('.order-card');
            
            orderCards.forEach(card => {
                const status = card.dataset.status;
                const orderId = card.dataset.orderId.toLowerCase();
                const productNames = Array.from(card.querySelectorAll('.item-name')).map(el => el.textContent.toLowerCase());
                
                let showCard = true;
                
                // Filter by status
                if (orderStatus !== 'all' && status !== orderStatus) {
                    showCard = false;
                }
                
                // Filter by search term
                if (searchTerm && !orderId.includes(searchTerm) && !productNames.some(name => name.includes(searchTerm))) {
                    showCard = false;
                }
                
                card.style.display = showCard ? 'block' : 'none';
            });
        }

        // Order action functions
        function trackOrder(orderId) {
            alert(`Tracking order: ${orderId}\nThis feature will be implemented with real tracking API.`);
        }

        function initiateReturn(orderId) {
            if (confirm(`Are you sure you want to return items from order ${orderId}?`)) {
                alert(`Return initiated for order: ${orderId}\nYou will receive return instructions via email.`);
            }
        }

        function reorderItems(orderId) {
            if (confirm('Add these items to your cart?')) {
                alert(`Items from order ${orderId} have been added to your cart.`);
            }
        }

        function cancelOrder(orderId) {
            if (confirm(`Are you sure you want to cancel order ${orderId}?`)) {
                alert(`Order ${orderId} has been cancelled successfully.`);
                location.reload();
            }
        }

        function viewOrderDetails(orderId) {
            alert(`Viewing detailed information for order: ${orderId}\nThis will redirect to order details page.`);
        }

        // Real-time search
        document.getElementById('searchOrders').addEventListener('input', function() {
            setTimeout(filterOrders, 300);
        });

        // Auto-filter on status change
        document.getElementById('orderStatus').addEventListener('change', filterOrders);
        document.getElementById('timePeriod').addEventListener('change', filterOrders);

        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.order-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>