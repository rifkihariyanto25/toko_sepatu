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
    <link rel="stylesheet" href="../assets/style.css?v=3">
</head>

<body class="home-page">

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="container">
            <a href="index.php" class="logo">
                <img src="../assets/logo%20sepatu.png" alt="Logo Toko Sepatu">
            </a>
            <div class="nav-links">
                <a href="#koleksi" class="nav-item"><span class="nav-icon">✦</span>Koleksi</a>
                <a href="cart.php" class="nav-item cart-link"><span class="nav-icon">🛍</span>Cart
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <b class="cart-count"><?php echo count($_SESSION['cart']); ?></b>
                    <?php endif; ?>
                </a>
                <?php if (isset($_SESSION['id_user'])): ?>
                    <a href="pesanan.php" class="nav-item"><span class="nav-icon">▣</span>Pesanan Saya</a>
                    <span>Halo, <?php echo $_SESSION['nama']; ?></span>
                    <a href="../proses/proses.php?aksi=logout" class="btn btn-outline">Logout</a>
                <?php else: ?>
                    <a href="../register.php" class="btn btn-register">Daftar <span>↗</span></a>
                    <a href="../login.php" class="btn btn-login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <section class="hero home-hero">
            <div class="hero-copy">
                <div class="eyebrow">Koleksi pilihan</div>
                <h1>Sneakers untuk setiap langkah.</h1>
                <p>Model pilihan dengan gaya yang mudah dipakai setiap hari.</p>
                <div class="hero-actions">
                    <a href="#koleksi" class="btn btn-accent">Jelajahi koleksi <span>↗</span></a>
                    <a href="#tentang" class="text-link">Kenapa kami? <span>↓</span></a>
                </div>
                <div class="hero-highlights">
                    <div><strong>10+</strong><span>koleksi aktif</span></div>
                    <div><strong>4.9/5</strong><span>rating pelanggan</span></div>
                    <div><strong>24h</strong><span>proses pesanan</span></div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-orbit"></div>
                <img class="hero-shoe"
                    src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=85"
                    onerror="this.onerror=null;this.src='../assets/sepatu/dummy-1.svg';"
                    alt="Sneakers merah koleksi pilihan">
                <div class="hero-caption"><strong>Featured</strong><span>Red classic sneakers</span></div>
            </div>
        </section>

        <div class="trust-strip" id="tentang">
            <span>Dipilih untuk gaya harian</span>
            <span>•</span>
            <span>Comfort first</span>
            <span>•</span>
            <span>Style that lasts</span>
        </div>

        <div class="section-heading" id="koleksi">
            <div>
                <div class="eyebrow">The edit</div>
                <h2>Koleksi terbaru</h2>
            </div>
            <p>Kurasi sneakers untuk gaya harianmu.</p>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 'checkout'): ?>
            <div class="notice">
                ✓ Checkout berhasil! Pesanan Anda sedang diproses.
            </div>
        <?php endif; ?>

        <div class="grid">

            <?php if (mysqli_num_rows($query) == 0): ?>
                <p>Belum ada produk.</p>
            <?php else: ?>
                <?php while ($produk = mysqli_fetch_assoc($query)): ?>
                    <!-- CARD PRODUK -->
                    <div class="card home-card">
                        <?php
                        $dummy_image = 'dummy-' . (($produk['id'] - 1) % 4 + 1) . '.svg';
                        $image_path = $produk['gambar'] && file_exists("../assets/sepatu/" . $produk['gambar'])
                            ? $produk['gambar']
                            : $dummy_image;
                        ?>
                        <img src="../assets/sepatu/<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($produk['nama']); ?>">
                        <div class="card-body">
                            <h3><?php echo $produk['nama']; ?></h3>
                            <div class="price">Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></div>
                            <p style="font-size:14px;color:#666">Stok: <?php echo $produk['stok']; ?></p>
                            <a href="detail.php?id=<?php echo $produk['id']; ?>" class="btn">Lihat detail <span>↗</span></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

        </div>
    </div>

</body>

</html>