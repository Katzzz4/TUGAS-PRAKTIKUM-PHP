<?php
// File: controllers/admin/hapus-pengguna.php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../../admin/login.php");
    exit;
}

// Include koneksi database
include '../../config/Connection.php';

// Cek apakah method POST dan ada ID
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id_pengguna = (int) $_POST['id'];
    
    // Validasi ID pengguna
    if ($id_pengguna <= 0) {
        $_SESSION['error'] = "ID pengguna tidak valid!";
        header("Location: ../../admin/pages/daftar-pengguna.php");
        exit;
    }
    
    // Mulai transaction untuk memastikan data konsisten
    mysqli_begin_transaction($conn);
    
    try {
        // Cek apakah pengguna ada
        $check_query = "SELECT id_pengguna, nama FROM auth WHERE id_pengguna = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "i", $id_pengguna);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($result) == 0) {
            throw new Exception("Pengguna tidak ditemukan!");
        }
        
        $user_data = mysqli_fetch_assoc($result);
        $nama_pengguna = $user_data['nama'];
        
        // Hapus data terkait dari tabel cart (jika ada)
        $delete_cart = "DELETE FROM cart WHERE id_pengguna = ?";
        $stmt_cart = mysqli_prepare($conn, $delete_cart);
        mysqli_stmt_bind_param($stmt_cart, "i", $id_pengguna);
        mysqli_stmt_execute($stmt_cart);
        
        // Hapus data terkait dari tabel returns (jika ada)
        $delete_returns = "DELETE FROM returns WHERE id_pengguna = ?";
        $stmt_returns = mysqli_prepare($conn, $delete_returns);
        mysqli_stmt_bind_param($stmt_returns, "i", $id_pengguna);
        mysqli_stmt_execute($stmt_returns);
        
        // Hapus data terkait dari tabel orders (CASCADE akan menghapus order_items dan order_tracking otomatis)
        $delete_orders = "DELETE FROM orders WHERE id_pengguna = ?";
        $stmt_orders = mysqli_prepare($conn, $delete_orders);
        mysqli_stmt_bind_param($stmt_orders, "i", $id_pengguna);
        mysqli_stmt_execute($stmt_orders);
        
        // Hapus pengguna dari tabel auth
        $delete_user = "DELETE FROM auth WHERE id_pengguna = ?";
        $stmt_user = mysqli_prepare($conn, $delete_user);
        mysqli_stmt_bind_param($stmt_user, "i", $id_pengguna);
        $execute_result = mysqli_stmt_execute($stmt_user);
        
        if (!$execute_result) {
            throw new Exception("Gagal menghapus pengguna dari database!");
        }
        
        // Commit transaction jika semua berhasil
        mysqli_commit($conn);
        
        // Set pesan sukses
        $_SESSION['success'] = "Pengguna '{$nama_pengguna}' berhasil dihapus beserta semua data terkait!";
        
        // Log aktivitas admin (opsional)
        error_log("Admin {$_SESSION['admin']} menghapus pengguna ID: {$id_pengguna} - {$nama_pengguna}");
        
    } catch (Exception $e) {
        // Rollback transaction jika terjadi error
        mysqli_rollback($conn);
        $_SESSION['error'] = "Error: " . $e->getMessage();
        error_log("Error menghapus pengguna ID {$id_pengguna}: " . $e->getMessage());
    }
    
    // Tutup prepared statements
    if (isset($check_stmt)) mysqli_stmt_close($check_stmt);
    if (isset($stmt_cart)) mysqli_stmt_close($stmt_cart);
    if (isset($stmt_returns)) mysqli_stmt_close($stmt_returns);
    if (isset($stmt_orders)) mysqli_stmt_close($stmt_orders);
    if (isset($stmt_user)) mysqli_stmt_close($stmt_user);
    
} else {
    $_SESSION['error'] = "Method tidak valid atau ID pengguna tidak ditemukan!";
}

// Redirect kembali ke halaman daftar pengguna
header("Location: ../../view/admin/daftar-pengguna.php");
exit;
?>