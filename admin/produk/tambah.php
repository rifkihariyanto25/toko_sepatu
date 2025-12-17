<?php
session_start();

// Cek login admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>

<body>

    <div class="admin-layout">

        <div class="sidebar">
            <h2>ADMIN</h2>
            <a href="index.php">← Kembali</a>
        </div>

        <div class="admin-content">
            <h2>Tambah Produk</h2>

            <form action="../../proses/proses.php" method="POST" enctype="multipart/form-data" class="form-box">
                <input type="hidden" name="aksi" value="tambah_produk">

                <input type="text" name="nama" placeholder="Nama Sepatu" required>
                <input type="number" name="harga" placeholder="Harga" required>
                <input type="number" name="stok" placeholder="Stok" required>
                <textarea name="deskripsi" placeholder="Deskripsi Produk" rows="4"
                    style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px"></textarea>
                <input type="file" name="gambar" accept="image/*">

                <button class="btn" style="width:100%">Simpan</button>
            </form>
        </div>

    </div>

</body>

</html>