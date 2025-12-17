<?php
session_start();
include '../config/database.php';

// Cek login admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua pesanan
$query = mysqli_query($conn, "SELECT o.*, u.nama as nama_user, u.email 
                               FROM orders o 
                               JOIN users u ON o.user_id = u.id 
                               ORDER BY o.id DESC");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Data Pesanan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <div class="admin-layout">

        <div class="sidebar">
            <h2>ADMIN</h2>
            <a href="index.php">Dashboard</a>
            <a href="produk/index.php">Produk</a>
            <a href="pesanan.php">Pesanan</a>
            <a href="../proses/proses.php?aksi=logout">Logout</a>
        </div>

        <div class="admin-content">
            <h2>Data Pesanan</h2>

            <div class="table">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                    <?php if (mysqli_num_rows($query) == 0): ?>
                        <tr>
                            <td colspan="7" style="text-align:center">Belum ada pesanan</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php while ($order = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>#<?php echo $order['id']; ?></td>
                                <td>
                                    <strong><?php echo $order['nama_user']; ?></strong><br>
                                    <small style="color:#666"><?php echo $order['email']; ?></small>
                                </td>
                                <td>Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></td>
                                <td><?php echo date('d M Y', strtotime($order['tanggal'])); ?></td>
                                <td>
                                    <?php
                                    $status_color = '';
                                    if ($order['status'] == 'pending') $status_color = '#FF9800';
                                    elseif ($order['status'] == 'process') $status_color = '#2196F3';
                                    elseif ($order['status'] == 'done') $status_color = '#4CAF50';
                                    elseif ($order['status'] == 'cancel') $status_color = '#f44336';
                                    ?>
                                    <span style="background:<?php echo $status_color; ?>;color:white;padding:5px 12px;border-radius:12px;font-size:12px">
                                        <?php echo strtoupper($order['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="pesanan_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-small">Detail</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>

                </table>
            </div>
        </div>

    </div>

</body>

</html>