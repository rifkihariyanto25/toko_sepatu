<?php
session_start();
include '../config/database.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = (int)$_SESSION['id_user'];

// Ambil semua pesanan user
$query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Pesanan Saya</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="container">
            <a href="index.php" style="text-decoration:none;color:#111">← Beranda</a>
            <div>
                <a href="cart.php">🛒 Cart
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        (<?php echo count($_SESSION['cart']); ?>)
                    <?php endif; ?>
                </a>
                <span>Halo, <?php echo $_SESSION['nama']; ?></span>
                <a href="../proses/proses.php?aksi=logout" class="btn btn-outline">Logout</a>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top:40px">
        <h2>Pesanan Saya</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == 'checkout'): ?>
            <div style="background:#4CAF50;color:white;padding:15px;border-radius:8px;margin:20px 0">
                ✓ Checkout berhasil! Pesanan Anda sedang diproses.
            </div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($query) == 0): ?>
            <div class="card">
                <p style="text-align:center;color:#666">Belum ada pesanan</p>
                <a href="index.php" class="btn" style="display:block;text-align:center;margin-top:20px">Mulai Belanja</a>
            </div>
        <?php else: ?>
            <?php while ($order = mysqli_fetch_assoc($query)): ?>
                <div class="card" style="margin-bottom:20px">
                    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:15px;border-bottom:2px solid #eee">
                        <div>
                            <h3>Order #<?php echo $order['id']; ?></h3>
                            <p style="font-size:14px;color:#666"><?php echo date('d M Y H:i', strtotime($order['tanggal'])); ?></p>
                        </div>
                        <div>
                            <?php
                            $status_color = '';
                            if ($order['status'] == 'pending') $status_color = '#FF9800';
                            elseif ($order['status'] == 'process') $status_color = '#2196F3';
                            elseif ($order['status'] == 'done') $status_color = '#4CAF50';
                            elseif ($order['status'] == 'cancel') $status_color = '#f44336';
                            ?>
                            <span style="background:<?php echo $status_color; ?>;color:white;padding:8px 16px;border-radius:20px;font-size:14px;font-weight:bold">
                                <?php echo strtoupper($order['status']); ?>
                            </span>
                        </div>
                    </div>

                    <div style="margin-top:15px">
                        <p><strong>Penerima:</strong> <?php echo $order['nama']; ?></p>
                        <p><strong>Alamat:</strong> <?php echo $order['alamat']; ?></p>
                    </div>

                    <?php
                    // Ambil detail pesanan
                    $detail_query = mysqli_query($conn, "SELECT od.*, p.nama as nama_produk, p.gambar 
                                                          FROM order_detail od 
                                                          JOIN produk p ON od.produk_id = p.id 
                                                          WHERE od.order_id='{$order['id']}'");
                    ?>

                    <div style="margin-top:20px;padding-top:20px;border-top:1px solid #eee">
                        <strong>Detail Produk:</strong>
                        <?php while ($detail = mysqli_fetch_assoc($detail_query)): ?>
                            <div style="display:flex;gap:15px;align-items:center;margin-top:15px;padding:10px;background:#f9f9f9;border-radius:8px">
                                <?php if ($detail['gambar']): ?>
                                    <img src="../assets/sepatu/<?php echo $detail['gambar']; ?>"
                                        style="width:60px;height:60px;object-fit:cover;border-radius:8px">
                                <?php endif; ?>
                                <div style="flex:1">
                                    <strong><?php echo $detail['nama_produk']; ?></strong>
                                    <p style="font-size:14px;color:#666">
                                        <?php echo $detail['qty']; ?> x Rp <?php echo number_format($detail['harga'], 0, ',', '.'); ?>
                                    </p>
                                </div>
                                <div>
                                    <strong>Rp <?php echo number_format($detail['subtotal'], 0, ',', '.'); ?></strong>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <div style="margin-top:20px;padding-top:20px;border-top:2px solid #eee;text-align:right">
                        <h3 style="color:#4CAF50">Total: Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></h3>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

</body>

</html>