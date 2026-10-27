<?php
session_start();
include '../config/database.php';

// Ambil ID produk
$id = (int)$_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $produk['nama']; ?> - Detail Produk</title>
    <link rel="stylesheet" href="../assets/style.css?v=3">
</head>

<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="container">
            <a href="index.php">← Kembali</a>
            <div>
                <a href="cart.php">🛒 Cart
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        (<?php echo count($_SESSION['cart']); ?>)
                    <?php endif; ?>
                </a>
                <?php if (isset($_SESSION['id_user'])): ?>
                    <a href="pesanan.php">📦 Pesanan</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top:60px">

        <?php if (isset($_GET['error']) && $_GET['error'] == 'stok_habis'): ?>
            <div style="background:#f44336;color:white;padding:15px;border-radius:8px;margin-bottom:20px">
                ✗ Stok tidak mencukupi! Jumlah yang Anda minta melebihi stok tersedia.
            </div>
        <?php endif; ?>

        <div class="detail-layout">

            <div>
                <?php
                $dummy_image = 'dummy-' . (($produk['id'] - 1) % 4 + 1) . '.svg';
                $image_path = $produk['gambar'] && file_exists("../assets/sepatu/" . $produk['gambar'])
                    ? $produk['gambar']
                    : $dummy_image;
                ?>
                <img src="../assets/sepatu/<?php echo htmlspecialchars($image_path); ?>" class="detail-image" alt="<?php echo htmlspecialchars($produk['nama']); ?>">
            </div>

            <div class="detail-copy">
                <h2><?php echo $produk['nama']; ?></h2>
                <p class="price">Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></p>
                <p style="margin:20px 0">
                    <?php echo nl2br($produk['deskripsi']); ?>
                </p>
                <p style="color:#666">Stok: <?php echo $produk['stok']; ?></p>

                <?php if ($produk['stok'] > 0): ?>
                    <form action="../proses/proses.php" method="POST" style="margin-top:30px">
                        <input type="hidden" name="aksi" value="tambah_cart">
                        <input type="hidden" name="id_produk" value="<?php echo $produk['id']; ?>">
                        <div style="display:flex;gap:10px;align-items:center">
                            <input type="number" name="qty" value="1" min="1" max="<?php echo $produk['stok']; ?>">
                            <button class="btn">Tambah ke Keranjang</button>
                        </div>
                    </form>
                <?php else: ?>
                    <p style="color:red;font-weight:bold">Stok habis</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

</body>

</html>