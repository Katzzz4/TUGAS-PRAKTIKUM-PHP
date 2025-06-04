<?php
// File: admin/edit-pengguna.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../../config/Connection.php';

// Ambil ID pengguna dari URL
$id_pengguna = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_pengguna <= 0) {
    header("Location: daftar-pengguna.php");
    exit;
}

// Ambil data pengguna berdasarkan ID
$query = "SELECT * FROM auth WHERE id_pengguna = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_pengguna);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: daftar-pengguna.php");
    exit;
}

$user = mysqli_fetch_assoc($result);

// Proses form jika ada POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
    
    $errors = [];
    
    // Validasi input
    if (empty($nama)) {
        $errors[] = "Nama tidak boleh kosong";
    }
    
    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    } else {
        // Cek email duplikat (kecuali email pengguna sendiri)
        $check_email = "SELECT id_pengguna FROM auth WHERE email = ? AND id_pengguna != ?";
        $stmt_check = mysqli_prepare($conn, $check_email);
        mysqli_stmt_bind_param($stmt_check, "si", $email, $id_pengguna);
        mysqli_stmt_execute($stmt_check);
        if (mysqli_num_rows(mysqli_stmt_get_result($stmt_check)) > 0) {
            $errors[] = "Email sudah digunakan pengguna lain";
        }
    }
    
    if (empty($errors)) {
        // Update data pengguna
        if (!empty($password)) {
            // Jika password diisi, hash password baru
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_query = "UPDATE auth SET nama = ?, email = ?, password = ?, role = ? WHERE id_pengguna = ?";
            $stmt_update = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt_update, "ssssi", $nama, $email, $hashed_password, $role, $id_pengguna);
        } else {
            // Jika password kosong, tidak update password
            $update_query = "UPDATE auth SET nama = ?, email = ?, role = ? WHERE id_pengguna = ?";
            $stmt_update = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt_update, "sssi", $nama, $email, $role, $id_pengguna);
        }
        
        if (mysqli_stmt_execute($stmt_update)) {
            $success_message = "Data pengguna berhasil diperbarui";
            // Refresh data pengguna
            $user['nama'] = $nama;
            $user['email'] = $email;
            $user['role'] = $role;
        } else {
            $errors[] = "Gagal memperbarui data pengguna";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - <?= htmlspecialchars($user['nama']) ?></title>
    <link rel="stylesheet" href="../../assets/css/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* CSS Khusus untuk Edit Pengguna */
        .edit-user-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .edit-user-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .edit-user-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
        }

        .edit-user-header .user-info {
            margin-top: 15px;
            opacity: 0.9;
        }

        .edit-user-form {
            padding: 40px;
        }

        .edit-form-grid {
            display: grid;
            gap: 25px;
        }

        .edit-form-group {
            position: relative;
        }

        .edit-form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .edit-form-group label i {
            margin-right: 8px;
            color: #667eea;
            width: 18px;
        }

        .edit-form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .edit-form-input:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .edit-form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .edit-form-select:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .edit-password-note {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
            font-style: italic;
        }

        .edit-form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e1e5e9;
        }

        .edit-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .edit-btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
            justify-content: center;
        }

        .edit-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .edit-btn-secondary {
            background: #6c757d;
            color: white;
            flex: 0 0 auto;
        }

        .edit-btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        .edit-alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 0.95rem;
        }

        .edit-alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .edit-alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .edit-alert ul {
            margin: 5px 0 0 20px;
            padding: 0;
        }

        .edit-alert ul li {
            margin-bottom: 5px;
        }

        .edit-user-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e1e5e9;
        }

        .edit-stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .edit-stat-card i {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 10px;
        }

        .edit-stat-card h4 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .edit-stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }

        @media (max-width: 768px) {
            .edit-user-form {
                padding: 20px;
            }
            
            .edit-form-actions {
                flex-direction: column;
            }
            
            .edit-btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="edit-user-container">
            <div class="edit-user-header">
                <h1><i class="fas fa-user-edit"></i> Edit Pengguna</h1>
                <div class="user-info">
                    <p>ID: <?= $user['id_pengguna'] ?> | Bergabung: <?= date('d M Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>

            <div class="edit-user-form">
                <?php if (isset($success_message)): ?>
                    <div class="edit-alert edit-alert-success">
                        <i class="fas fa-check-circle"></i> <?= $success_message ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="edit-alert edit-alert-error">
                        <i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan:
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="edit-form-grid">
                        <div class="edit-form-group">
                            <label for="nama">
                                <i class="fas fa-user"></i> Nama Lengkap
                            </label>
                            <input 
                                type="text" 
                                id="nama" 
                                name="nama" 
                                class="edit-form-input" 
                                value="<?= htmlspecialchars($user['nama']) ?>" 
                                required
                            >
                        </div>

                        <div class="edit-form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="edit-form-input" 
                                value="<?= htmlspecialchars($user['email']) ?>" 
                                required
                            >
                        </div>

                        <div class="edit-form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i> Password Baru
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="edit-form-input" 
                                placeholder="Kosongkan jika tidak ingin mengubah password"
                            >
                            <div class="edit-password-note">
                                * Kosongkan jika tidak ingin mengubah password
                            </div>
                        </div>

                        <div class="edit-form-group">
                            <label for="role">
                                <i class="fas fa-user-tag"></i> Role/Peran
                            </label>
                            <select id="role" name="role" class="edit-form-select">
                                <option value="">Pilih Role</option>
                                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                <option value="premium" <?= $user['role'] == 'premium' ? 'selected' : '' ?>>Premium User</option>
                                <option value="moderator" <?= $user['role'] == 'moderator' ? 'selected' : '' ?>>Moderator</option>
                            </select>
                        </div>
                    </div>

                    <div class="edit-form-actions">
                        <button type="submit" class="edit-btn edit-btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="daftar-pengguna.php" class="edit-btn edit-btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>

                <?php
                // Ambil statistik pengguna (contoh: jumlah pesanan)
                $stats_query = "SELECT 
                    COUNT(o.id_order) as total_orders,
                    COALESCE(SUM(o.total_amount), 0) as total_spent
                    FROM orders o 
                    WHERE o.id_pengguna = ?";
                $stmt_stats = mysqli_prepare($conn, $stats_query);
                mysqli_stmt_bind_param($stmt_stats, "i", $id_pengguna);
                mysqli_stmt_execute($stmt_stats);
                $stats_result = mysqli_stmt_get_result($stmt_stats);
                $stats = mysqli_fetch_assoc($stats_result);
                ?>

                <div class="edit-user-stats">
                    <div class="edit-stat-card">
                        <i class="fas fa-shopping-cart"></i>
                        <h4>Total Pesanan</h4>
                        <div class="stat-value"><?= $stats['total_orders'] ?></div>
                    </div>
                    <div class="edit-stat-card">
                        <i class="fas fa-money-bill-wave"></i>
                        <h4>Total Belanja</h4>
                        <div class="stat-value">Rp <?= number_format($stats['total_spent'], 0, ',', '.') ?></div>
                    </div>
                    <div class="edit-stat-card">
                        <i class="fas fa-calendar-alt"></i>
                        <h4>Member Since</h4>
                        <div class="stat-value"><?= date('M Y', strtotime($user['created_at'])) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dropdown functionality untuk sidebar
        document.querySelectorAll('.dropdown-header').forEach(header => {
            header.addEventListener('click', () => {
                const dropdown = header.parentElement;
                dropdown.classList.toggle('active');
            });
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const nama = document.getElementById('nama').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!nama) {
                alert('Nama tidak boleh kosong');
                e.preventDefault();
                return false;
            }
            
            if (!email) {
                alert('Email tidak boleh kosong');
                e.preventDefault();
                return false;
            }
            
            // Konfirmasi sebelum menyimpan
            if (!confirm('Apakah Anda yakin ingin menyimpan perubahan data pengguna ini?')) {
                e.preventDefault();
                return false;
            }
        });

        // Auto hide success message
        setTimeout(function() {
            const successAlert = document.querySelector('.edit-alert-success');
            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }
        }, 3000);
    </script>
</body>
</html>