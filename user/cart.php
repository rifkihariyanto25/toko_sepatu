<?php
session_start();
include '../config/database.php';

$cart_items = array();
$total = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
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
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <div class="navbar">
        <div class="container">
            <a href="index.php">← Belanja Lagi</a>
            <?php if (isset($_SESSION['id_user'])): ?>
                <a href="pesanan.php">📦 Pesanan Saya</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container" style="margin-top:40px">
        <h2>Keranjang Belanja</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == 'tambah'): ?>
            <div class="notice">
                ✓ Produk berhasil ditambahkan ke keranjang!
            </div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <div class="card table-card">
                <p style="text-align:center;color:#666">Keranjang Anda kosong</p>
                <a href="index.php" class="btn" style="display:block;text-align:center;margin-top:20px">Mulai Belanja</a>
            </div>
        <?php else: ?>
            <div class="card table-card">
                <form action="../proses/proses.php" method="POST">
                    <input type="hidden" name="aksi" value="update_cart">

                    <table style="width:100%">
                        <thead>
                            <tr style="border-bottom:2px solid #eee">
                                <th style="text-align:left;padding:10px">Produk</th>
                                <th style="text-align:center;padding:10px">Harga</th>
                                <th style="text-align:center;padding:10px">Qty</th>
                                <th style="text-align:right;padding:10px">Subtotal</th>
                                <th style="padding:10px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                                <tr style="border-bottom:1px solid #eee">
                                    <td style="padding:15px">
                                        <div style="display:flex;gap:15px;align-items:center">
                                            <?php if ($item['gambar']): ?>
                                                <img src="../assets/sepatu/<?php echo $item['gambar']; ?>"
                                                    style="width:60px;height:60px;object-fit:cover;border-radius:8px">
                                            <?php endif; ?>
                                            <strong><?php echo $item['nama']; ?></strong>
                                        </div>
                                    </td>
                                    <td style="text-align:center;padding:15px">
                                        Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?>
                                    </td>
                                    <td style="text-align:center;padding:15px">
                                        <input type="number" name="qty[<?php echo $item['id']; ?>]"
                                            value="<?php echo $item['qty']; ?>" min="1" max="<?php echo $item['stok']; ?>"
                                            style="width:70px;padding:8px;border:1px solid #ddd;border-radius:8px;text-align:center">
                                    </td>
                                    <td style="text-align:right;padding:15px">
                                        <strong>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></strong>
                                    </td>
                                    <td style="text-align:center;padding:15px">
                                        <a href="../proses/proses.php?aksi=hapus_cart&id=<?php echo $item['id']; ?>"
                                            onclick="return confirm('Hapus produk ini dari keranjang?')"
                                            style="color:red;text-decoration:none">✕</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div style="margin-top:20px;text-align:right">
                        <button type="submit" class="btn btn-outline">Update Keranjang</button>
                    </div>
                </form>

                <div style="margin-top:30px;padding-top:30px;border-top:2px solid #eee">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <h3>Total:</h3>
                        <h3 style="color:#4CAF50">Rp <?php echo number_format($total, 0, ',', '.'); ?></h3>
                    </div>
                    <a href="checkout.php" class="btn" style="display:block;text-align:center;margin-top:20px">
                        Lanjut ke Checkout
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>