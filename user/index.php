<?php
session_start();
include '../config/database.php';

// Ambil semua produk
$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Toko Sepatu</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="container">
            <div class="logo">SEPATU.</div>
            <div>
                <a href="cart.php">🛒 Cart
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        (<?php echo count($_SESSION['cart']); ?>)
                    <?php endif; ?>
                </a>
                <?php if (isset($_SESSION['id_user'])): ?>
                    <a href="pesanan.php">📦 Pesanan Saya</a>
                    <span>Halo, <?php echo $_SESSION['nama']; ?></span>
                    <a href="../proses/proses.php?aksi=logout" class="btn btn-outline">Logout</a>
                <?php else: ?>
                    <a href="../register.php" class="btn">Daftar</a>
                    <a href="../login.php" class="btn btn-outline">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">
        <h2 style="margin-top:40px">Produk Terbaru</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == 'checkout'): ?>
            <div style="background:#4CAF50;color:white;padding:15px;border-radius:8px;margin:20px 0">
                ✓ Checkout berhasil! Pesanan Anda sedang diproses.
            </div>
        <?php endif; ?>

        <div class="grid">

            <?php if (mysqli_num_rows($query) == 0): ?>
                <p>Belum ada produk.</p>
            <?php else: ?>
                <?php while ($produk = mysqli_fetch_assoc($query)): ?>
                    <!-- CARD PRODUK -->
                    <div class="card">
                        <?php if ($produk['gambar'] && file_exists("../assets/sepatu/" . $produk['gambar'])): ?>
                            <img src="../assets/sepatu/<?php echo $produk['gambar']; ?>" alt="<?php echo $produk['nama']; ?>">
                        <?php else: ?>
                            <div style="width:100%;height:200px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;color:#999;font-size:18px;border-radius:8px 8px 0 0">
                                No Image
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h3><?php echo $produk['nama']; ?></h3>
                            <div class="price">Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></div>
                            <p style="font-size:14px;color:#666">Stok: <?php echo $produk['stok']; ?></p>
                            <a href="detail.php?id=<?php echo $produk['id']; ?>" class="btn">Lihat Detail</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

        </div>
    </div>

</body>

</html>