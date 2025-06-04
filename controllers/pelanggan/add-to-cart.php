
<?php
session_start();
require_once '../../config/Connection.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_pengguna'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php?redirect=cart');
    exit();
}

// Cek apakah ada data POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_produk'])) {
    $id_pengguna = $_SESSION['id_pengguna'];
    $id_produk = intval($_POST['id_produk']);
    $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;
    
    // Validasi apakah produk exists
    $check_product = mysqli_query($conn, "SELECT id_produk FROM produk WHERE id_produk = $id_produk");
    if (mysqli_num_rows($check_product) == 0) {
        $_SESSION['error'] = "Produk tidak ditemukan!";
        header('Location: index.php');
        exit();
    }
    
    // Cek apakah produk sudah ada di cart
    $check_cart = "SELECT id_cart, quantity FROM cart WHERE id_pengguna = ? AND id_produk = ?";
    $stmt = mysqli_prepare($conn, $check_cart);
    mysqli_stmt_bind_param($stmt, "ii", $id_pengguna, $id_produk);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Jika sudah ada, update quantity
        $row = mysqli_fetch_assoc($result);
        $new_quantity = $row['quantity'] + $quantity;
        
        $update_query = "UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE id_cart = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ii", $new_quantity, $row['id_cart']);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Quantity produk berhasil diperbarui di keranjang!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui quantity produk!";
        }
    } else {
        // Jika belum ada, insert baru
        $insert_query = "INSERT INTO cart (id_pengguna, id_produk, quantity) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "iii", $id_pengguna, $id_produk, $quantity);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Produk berhasil ditambahkan ke keranjang!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan produk ke keranjang!";
        }
    }
    
    // Redirect berdasarkan parameter
    if (isset($_POST['redirect']) && $_POST['redirect'] == 'cart') {
        header('Location: cart.php');
    } else {
        header('Location: index.php');
    }
    exit();
} else {
    // Jika tidak ada data POST yang valid
    $_SESSION['error'] = "Data tidak valid!";
    header('Location: index.php');
    exit();
}
?>