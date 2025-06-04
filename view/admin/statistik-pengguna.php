<?php
// File: admin/statistik-pengguna.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../../config/Connection.php';

// Query untuk data line chart (6 bulan terakhir)
$query_line = "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') AS bulan,
                COUNT(*) AS total 
               FROM auth 
               WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
               GROUP BY bulan 
               ORDER BY bulan ASC";
$result_line = mysqli_query($conn, $query_line);

// Query untuk data pie chart
$query_pie = "SELECT 
                role AS kategori,
                COUNT(*) AS total 
              FROM auth 
              GROUP BY role";
$result_pie = mysqli_query($conn, $query_pie);

// Cek error query
if (!$result_line || !$result_pie) {
    die("Error dalam query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statistik Pengguna</title>
    <link rel="stylesheet" href="../../assets/css/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

        <?php include 'sidebar.php'; ?>

    <div class="content">
        <h1><i class="fas fa-chart-line"></i> Statistik Pengguna</h1>
        
        <!-- Summary Cards -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <i class="fas fa-users"></i>
                <div class="stat-content">
                    <h3>Total Pengguna</h3>
                    <p><?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM auth")) ?></p>
                </div>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="chart-container">
            <div class="chart-box">
                <h3><i class="fas fa-chart-line"></i> Registrasi 6 Bulan Terakhir</h3>
                <canvas id="lineChart"></canvas>
            </div>
            
            <div class="chart-box">
                <h3><i class="fas fa-chart-pie"></i> Distribusi Peran Pengguna</h3>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Line Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: [
                    <?php while($row = mysqli_fetch_assoc($result_line)): ?>
                        '<?= date('M Y', strtotime($row['bulan'])) ?>',
                    <?php endwhile; ?>
                ],
                datasets: [{
                    label: 'Registrasi Pengguna',
                    data: [
                        <?php 
                        mysqli_data_seek($result_line, 0);
                        while($row = mysqli_fetch_assoc($result_line)): 
                            echo $row['total'].',';
                        endwhile; 
                        ?>
                    ],
                    borderColor: '#4CAF50',
                    tension: 0.3,
                    fill: true,
                    backgroundColor: 'rgba(76, 175, 80, 0.1)'
                }]
            }
        });

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: [
                    <?php while($row = mysqli_fetch_assoc($result_pie)): ?>
                        '<?= $row['kategori'] ?>',
                    <?php endwhile; ?>
                ],
                datasets: [{
                    data: [
                        <?php 
                        mysqli_data_seek($result_pie, 0);
                        while($row = mysqli_fetch_assoc($result_pie)): 
                            echo $row['total'].',';
                        endwhile; 
                        ?>
                    ],
                    backgroundColor: [
                        '#4CAF50',
                        '#2196F3',
                        '#FF9800',
                        '#9C27B0',
                        '#E91E63'
                    ]
                }]
            }
        });
    </script>
</body>
</html>