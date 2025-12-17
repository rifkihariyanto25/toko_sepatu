<?php
session_start();
include '../../config/database.php';

// Cek login admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Ambil semua produk
$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Data Produk</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>

<body>

    <div class="admin-layout">

        <div class="sidebar">
            <h2>ADMIN</h2>
            <a href="../index.php">Dashboard</a>
            <a href="index.php">Produk</a>
            <a href="../pesanan.php">Pesanan</a>
            <a href="../../proses/proses.php?aksi=logout">Logout</a>
        </div>

        <div class="admin-content">
            <h2>Data Produk</h2>

            <?php if (isset($_GET['success'])): ?>
                <?php if ($_GET['success'] == 'tambah'): ?>
                    <div style="background:#4CAF50;color:white;padding:15px;border-radius:8px;margin:20px 0">
                        ✓ Produk berhasil ditambahkan
                    </div>
                <?php elseif ($_GET['success'] == 'edit'): ?>
                    <div style="background:#2196F3;color:white;padding:15px;border-radius:8px;margin:20px 0">
                        ✓ Produk berhasil diupdate
                    </div>
                <?php elseif ($_GET['success'] == 'hapus'): ?>
                    <div style="background:#f44336;color:white;padding:15px;border-radius:8px;margin:20px 0">
                        ✓ Produk berhasil dihapus
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <a href="tambah.php" class="btn" style="margin-bottom:20px">+ Tambah Produk</a>

            <div class="table">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>

                    <?php if (mysqli_num_rows($query) == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align:center">Belum ada produk</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php while ($produk = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <?php if ($produk['gambar']): ?>
                                        <img src="../../assets/sepatu/<?php echo $produk['gambar']; ?>"
                                            style="width:50px;height:50px;object-fit:cover;border-radius:8px">
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $produk['nama']; ?></td>
                                <td>Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $produk['stok']; ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $produk['id']; ?>" class="btn btn-small">Edit</a>
                                    <a href="../../proses/proses.php?aksi=hapus_produk&id=<?php echo $produk['id']; ?>"
                                        class="btn btn-outline btn-small"
                                        onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
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