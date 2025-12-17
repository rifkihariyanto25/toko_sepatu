<?php
session_start();
include '../config/database.php';

// Cek login admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Statistik
$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"))['total'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status != 'cancel'"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as revenue FROM orders WHERE status != 'cancel'"))['revenue'] ?? 0;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <div class="admin-layout">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <h2>ADMIN</h2>
            <a href="index.php">Dashboard</a>
            <a href="produk/index.php">Produk</a>
            <a href="pesanan.php">Pesanan</a>
            <a href="../proses/proses.php?aksi=logout">Logout</a>
        </div>

        <!-- CONTENT -->
        <div class="admin-content">
            <h2>Dashboard</h2>
            <p>Selamat datang, <?php echo $_SESSION['nama']; ?>!</p>

            <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:20px;margin-top:30px">
                <div class="card">
                    <h3><?php echo $total_produk; ?></h3>
                    <p>Total Produk</p>
                </div>
                <div class="card">
                    <h3><?php echo $total_orders; ?></h3>
                    <p>Total Orders</p>
                </div>
                <div class="card">
                    <h3 style="color:#4CAF50">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>

    </div>

</body>

</html>