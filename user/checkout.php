<?php
session_start();
include '../config/database.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php?checkout=1");
    exit;
}

// Cek cart kosong
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Hitung total
$cart_items = array();
$total = 0;

foreach ($_SESSION['cart'] as $id_produk => $qty) {
    $query = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id_produk'");
    $produk = mysqli_fetch_assoc($query);

    if ($produk) {
        $produk['qty'] = $qty;
        $produk['subtotal'] = $produk['harga'] * $qty;
        $cart_items[] = $produk;
        $total += $produk['subtotal'];
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <div class="navbar">
        <div class="container">
            <a href="cart.php">← Kembali ke Cart</a>
        </div>
    </div>

    <div class="container" style="margin-top:40px">
        <div class="detail-layout" style="align-items:start">

            <!-- FORM CHECKOUT -->
            <div>
                <h2>Data Pengiriman</h2>
                <form action="../proses/proses.php" method="POST" class="form-box" style="width:100%;margin:20px 0 0">
                    <input type="hidden" name="aksi" value="checkout">

                    <input type="text" name="nama" placeholder="Nama Lengkap" required
                        value="<?php echo $_SESSION['nama']; ?>">
                    <textarea name="alamat" placeholder="Alamat Lengkap" rows="4"
                        style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px" required></textarea>

                    <button class="btn" style="width:100%;margin-top:20px">Konfirmasi Pesanan</button>
                </form>
            </div>

            <!-- RINGKASAN PESANAN -->
            <div>
                <h2>Ringkasan Pesanan</h2>
                <div class="card">
                    <?php foreach ($cart_items as $item): ?>
                        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #eee">
                            <div>
                                <strong><?php echo $item['nama']; ?></strong>
                                <p style="font-size:14px;color:#666">Qty: <?php echo $item['qty']; ?></p>
                            </div>
                            <div>
                                Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div style="display:flex;justify-content:space-between;padding:20px 0;margin-top:20px;border-top:2px solid #eee">
                        <h3>Total:</h3>
                        <h3 style="color:#4CAF50">Rp <?php echo number_format($total, 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>

</html>