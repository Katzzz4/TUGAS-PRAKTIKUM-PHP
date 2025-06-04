<?php
// File: admin/daftar-pesanan.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../../config/Connection.php';

function getStatistic($table, $condition = '') {
    global $conn;
    $query = "SELECT COUNT(*) as total FROM $table $condition";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['total'];
}

// Get status filter
$status_filter = $_GET['status'] ?? 'semua';

// Build query based on status
if ($status_filter === 'semua') {
    $query = "SELECT o.*, a.nama, a.email 
              FROM orders o 
              LEFT JOIN auth a ON o.id_pengguna = a.id_pengguna 
              ORDER BY o.created_at DESC";
} else {
    $query = "SELECT o.*, a.nama, a.email 
              FROM orders o 
              LEFT JOIN auth a ON o.id_pengguna = a.id_pengguna 
              WHERE o.status = '$status_filter' 
              ORDER BY o.created_at DESC";
}

$orders = mysqli_query($conn, $query);

// Status mapping for display
$status_labels = [
    'processing' => 'Pesanan Baru',
    'shipped' => 'Sedang Dikirim',
    'delivered' => 'Selesai',
    'cancelled' => 'Dibatalkan',
    'returned' => 'Dikembalikan'
];

$payment_status_labels = [
    'pending' => 'Menunggu',
    'paid' => 'Lunas',
    'failed' => 'Gagal',
    'refunded' => 'Dikembalikan'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Daftar Pesanan - Admin</title>
  <link rel="stylesheet" href="../../assets/css/admin-style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="sidebar">
  <h2><i class="fas fa-user-shield"></i> Menu Admin</h2>
    
  <!-- Menu Produk -->
  <div class="dropdown">
    <div class="dropdown-header">
      <i class="fas fa-boxes icon-main"></i>
      <span>Kategori Produk</span>
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-content scrollable-menu">
      <a href="dashboard.php?kategori=semua" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-cubes"></i>
          <div class="menu-text">
            <div>Semua Produk</div>
            <small><?= getStatistic('produk') ?> item</small>
          </div>
        </div>
      </a>
      <!-- Other product categories... -->
    </div>
  </div>

  <!-- Menu Pesanan/Orderan -->
  <div class="dropdown active">
    <div class="dropdown-header">
      <i class="fas fa-shopping-cart icon-main"></i>
      <span>Manajemen Pesanan</span>
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-content scrollable-menu">
      <a href="daftar-pesanan.php?status=semua" class="menu-link <?= $status_filter === 'semua' ? 'active' : '' ?>">
        <div class="menu-item">
          <i class="fas fa-list-alt"></i>
          <div class="menu-text">
            <div>Semua Pesanan</div>
            <small><?= getStatistic('orders') ?> pesanan</small>
          </div>
        </div>
      </a>

      <a href="daftar-pesanan.php?status=processing" class="menu-link <?= $status_filter === 'processing' ? 'active' : '' ?>">
        <div class="menu-item">
          <i class="fas fa-clock"></i>
          <div class="menu-text">
            <div>Pesanan Baru</div>
            <small><?= getStatistic('orders', "WHERE status = 'processing'") ?> pesanan</small>
          </div>
        </div>
      </a>

      <a href="daftar-pesanan.php?status=shipped" class="menu-link <?= $status_filter === 'shipped' ? 'active' : '' ?>">
        <div class="menu-item">
          <i class="fas fa-truck"></i>
          <div class="menu-text">
            <div>Sedang Dikirim</div>
            <small><?= getStatistic('orders', "WHERE status = 'shipped'") ?> pesanan</small>
          </div>
        </div>
      </a>

      <a href="daftar-pesanan.php?status=delivered" class="menu-link <?= $status_filter === 'delivered' ? 'active' : '' ?>">
        <div class="menu-item">
          <i class="fas fa-check-circle"></i>
          <div class="menu-text">
            <div>Pesanan Selesai</div>
            <small><?= getStatistic('orders', "WHERE status = 'delivered'") ?> pesanan</small>
          </div>
        </div>
      </a>

      <a href="daftar-pesanan.php?status=cancelled" class="menu-link <?= $status_filter === 'cancelled' ? 'active' : '' ?>">
        <div class="menu-item">
          <i class="fas fa-times-circle"></i>
          <div class="menu-text">
            <div>Pesanan Dibatalkan</div>
            <small><?= getStatistic('orders', "WHERE status = 'cancelled'") ?> pesanan</small>
          </div>
        </div>
      </a>

      <a href="daftar-pesanan.php?status=returned" class="menu-link <?= $status_filter === 'returned' ? 'active' : '' ?>">
        <div class="menu-item">
          <i class="fas fa-undo"></i>
          <div class="menu-text">
            <div>Pesanan Dikembalikan</div>
            <small><?= getStatistic('orders', "WHERE status = 'returned'") ?> pesanan</small>
          </div>
        </div>
      </a>

      <div class="menu-divider"></div>

      <a href="tracking-pesanan.php" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-map-marker-alt"></i>
          <div class="menu-text">
            <div>Tracking Pesanan</div>
            <small><?= getStatistic('order_tracking') ?> tracking</small>
          </div>
        </div>
      </a>

      <a href="daftar-return.php" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-exchange-alt"></i>
          <div class="menu-text">
            <div>Pengembalian Barang</div>
            <small><?= getStatistic('returns') ?> return</small>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Menu Pengguna -->
  <div class="dropdown">
    <div class="dropdown-header">
      <i class="fas fa-users-cog icon-main"></i>
      <span>Manajemen Pengguna</span>
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-content">
      <a href="daftar-pengguna.php" class="menu-link">
        <div class="menu-item">
          <i class="fas fa-user-friends"></i>
          <div class="menu-text">
            <div>Daftar Pengguna</div>
            <small><?= getStatistic('auth') ?> akun</small>
          </div>
        </div>
      </a>
    </div>
  </div>
  
  <a href="../../controllers/admin/logout.php" class="logout">
    <i class="fas fa-sign-out-alt"></i> Logout
  </a>
</div>

<div class="content">
  <div class="top-action">
    <h3>
      <i class="fas fa-shopping-cart"></i> 
      <?= $status_filter === 'semua' ? 'Semua Pesanan' : $status_labels[$status_filter] ?? 'Pesanan' ?>
    </h3>
  </div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th class="col-no">No</th>
          <th class="col-order-id">ID Pesanan</th>
          <th class="col-customer">Pelanggan</th>
          <th class="col-total">Total</th>
          <th class="col-status">Status Pesanan</th>
          <th class="col-payment">Status Pembayaran</th>
          <th class="col-date">Tanggal</th>
          <th class="col-actions">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($order = mysqli_fetch_assoc($orders)) : ?>
        <tr>
          <td class="col-no">
            <div class="row-number"><?= $no++ ?></div>
          </td>
          <td class="col-order-id">
            <div class="order-id"><?= htmlspecialchars($order['order_id']) ?></div>
          </td>
          <td class="col-customer">
            <div class="customer-info">
              <div class="customer-name"><?= htmlspecialchars($order['nama']) ?></div>
              <small class="customer-email"><?= htmlspecialchars($order['email']) ?></small>
            </div>
          </td>
          <td class="col-total">
            <div class="order-total">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></div>
          </td>
          <td class="col-status">
            <span class="status-badge status-<?= $order['status'] ?>">
              <?= $status_labels[$order['status']] ?? $order['status'] ?>
            </span>
          </td>
          <td class="col-payment">
            <span class="payment-badge payment-<?= $order['payment_status'] ?>">
              <?= $payment_status_labels[$order['payment_status']] ?? $order['payment_status'] ?>
            </span>
          </td>
          <td class="col-date">
            <div class="order-date">
              <?= date('d/m/Y', strtotime($order['created_at'])) ?><br>
              <small><?= date('H:i', strtotime($order['created_at'])) ?></small>
            </div>
          </td>
          <td class="col-actions">
            <div class="action-buttons">
              <a href="detail-pesanan.php?id=<?= $order['id_order'] ?>" class="btn-info" title="Lihat Detail">
                <i class="fas fa-eye"></i>
              </a>
              <?php if ($order['status'] === 'processing') : ?>
              <a href="../../controllers/admin/update-status-pesanan.php?id=<?= $order['id_order'] ?>&status=shipped" 
                 class="btn-warning" title="Kirim Pesanan" 
                 onclick="return confirm('Konfirmasi pengiriman pesanan <?= $order['order_id'] ?>?')">
                <i class="fas fa-truck"></i>
              </a>
              <?php endif; ?>
              <?php if ($order['status'] === 'shipped') : ?>
              <a href="../../controllers/admin/update-status-pesanan.php?id=<?= $order['id_order'] ?>&status=delivered" 
                 class="btn-success" title="Selesai" 
                 onclick="return confirm('Konfirmasi pesanan sudah diterima pelanggan?')">
                <i class="fas fa-check"></i>
              </a>
              <?php endif; ?>
              <?php if (in_array($order['status'], ['processing', 'shipped'])) : ?>
              <a href="../../controllers/admin/update-status-pesanan.php?id=<?= $order['id_order'] ?>&status=cancelled" 
                 class="btn-danger" title="Batalkan Pesanan" 
                 onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan <?= $order['order_id'] ?>?')">
                <i class="fas fa-times"></i>
              </a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
        
        <?php if (mysqli_num_rows($orders) === 0) : ?>
        <tr>
          <td colspan="8" class="empty-state">
            <div class="empty-message">
              <i class="fas fa-inbox"></i>
              <h4>Tidak ada pesanan</h4>
              <p>
                <?php if ($status_filter === 'semua') : ?>
                Belum ada pesanan yang masuk
                <?php else : ?>
                Tidak ada pesanan dengan status "<?= $status_labels[$status_filter] ?? $status_filter ?>"
                <?php endif; ?>
              </p>
            </div>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.querySelectorAll('.dropdown-header').forEach(header => {
  header.addEventListener('click', () => {
    header.parentElement.classList.toggle('active');
  });
});

// Prevent dropdown closure saat scroll
document.querySelectorAll('.dropdown-content').forEach(content => {
  content.addEventListener('wheel', (e) => {
    e.stopPropagation();
  });
});
</script>

</body>
</html>