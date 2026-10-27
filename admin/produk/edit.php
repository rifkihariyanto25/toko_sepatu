<?php
session_start();
include '../../config/database.php';

// Cek login admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Cek apakah ada parameter id
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

// Ambil data produk
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
    <title>Edit Produk</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>

<body>

    <div class="admin-layout">

        <div class="sidebar">
            <h2>ADMIN</h2>
            <a href="index.php">← Kembali</a>
        </div>

        <div class="admin-content">
            <h2>Edit Produk</h2>

            <form action="../../proses/proses.php" method="POST" enctype="multipart/form-data" class="form-box">
                <input type="hidden" name="aksi" value="edit_produk">
                <input type="hidden" name="id" value="<?php echo $produk['id']; ?>">

                <input type="text" name="nama" value="<?php echo $produk['nama']; ?>" required>
                <input type="number" name="harga" value="<?php echo $produk['harga']; ?>" required>
                <input type="number" name="stok" value="<?php echo $produk['stok']; ?>" required>
                <textarea name="deskripsi" rows="4"
                    style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px"><?php echo $produk['deskripsi']; ?></textarea>

                <?php if ($produk['gambar']): ?>
                    <div style="margin:10px 0">
                        <img src="../../assets/sepatu/<?php echo $produk['gambar']; ?>"
                            style="width:150px;border-radius:8px">
                        <p style="font-size:14px;color:#666">Gambar saat ini</p>
                    </div>
                <?php endif; ?>

                <input type="file" name="gambar" accept="image/*">
                <p style="font-size:12px;color:#666">Kosongkan jika tidak ingin mengubah gambar</p>

                <button class="btn" style="width:100%">Update</button>
            </form>
        </div>

    </div>

</body>

</html>