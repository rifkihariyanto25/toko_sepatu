<?php
session_start();
include '../config/database.php';

// Cek login admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Cek apakah ada parameter id
if (!isset($_GET['id'])) {
    header("Location: pesanan.php");
    exit;
}

$order_id = (int)$_GET['id'];

// Ambil data pesanan
$query = mysqli_query($conn, "SELECT o.*, u.nama as nama_user, u.email 
                               FROM orders o 
                               JOIN users u ON o.user_id = u.id 
                               WHERE o.id = '$order_id'");

$order = mysqli_fetch_assoc($query);

if (!$order) {
    header("Location: pesanan.php");
    exit;
}

// Ambil detail item pesanan
$items = mysqli_query($conn, "SELECT od.*, p.nama as nama_produk, p.gambar 
                               FROM order_detail od 
                               JOIN produk p ON od.produk_id = p.id 
                               WHERE od.order_id = '$order_id'");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Detail Pesanan #<?php echo $order['id']; ?></title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <div class="admin-layout">

        <div class="sidebar">
            <h2>ADMIN</h2>
            <a href="pesanan.php">← Kembali ke Pesanan</a>
        </div>

        <div class="admin-content">
            <h2>Detail Pesanan #<?php echo $order['id']; ?></h2>

            <?php if (isset($_GET['success']) && $_GET['success'] == 'update'): ?>
                <div style="background:#4CAF50;color:white;padding:15px;border-radius:8px;margin:20px 0">
                    ✓ Status pesanan berhasil diupdate
                </div>
            <?php endif; ?>

            <div style="background:white;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin-bottom:20px">
                <h3>Informasi Customer</h3>
                <table style="width:100%;border:none">
                    <tr>
                        <td style="border:none;padding:8px 0"><strong>Nama:</strong></td>
                        <td style="border:none;padding:8px 0"><?php echo $order['nama_user']; ?></td>
                    </tr>
                    <tr>
                        <td style="border:none;padding:8px 0"><strong>Email:</strong></td>
                        <td style="border:none;padding:8px 0"><?php echo $order['email']; ?></td>
                    </tr>
                    <tr>
                        <td style="border:none;padding:8px 0;vertical-align:top"><strong>Alamat:</strong></td>
                        <td style="border:none;padding:8px 0"><?php echo $order['alamat']; ?></td>
                    </tr>
                    <tr>
                        <td style="border:none;padding:8px 0"><strong>Tanggal Order:</strong></td>
                        <td style="border:none;padding:8px 0"><?php echo date('d M Y H:i', strtotime($order['tanggal'])); ?></td>
                    </tr>
                    <tr>
                        <td style="border:none;padding:8px 0"><strong>Status:</strong></td>
                        <td style="border:none;padding:8px 0">
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
                    </tr>
                </table>
            </div>

            <h3>Item Pesanan</h3>
            <div class="table">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>

                    <?php $no = 1; ?>
                    <?php while ($item = mysqli_fetch_assoc($items)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <?php if ($item['gambar']): ?>
                                    <img src="../assets/sepatu/<?php echo $item['gambar']; ?>"
                                        style="width:50px;height:50px;object-fit:cover;border-radius:8px">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo $item['nama_produk']; ?></td>
                            <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                            <td><?php echo $item['qty']; ?></td>
                            <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endwhile; ?>

                    <tr>
                        <td colspan="5" style="text-align:right;font-weight:bold">TOTAL:</td>
                        <td style="font-weight:bold;font-size:16px">
                            Rp <?php echo number_format($order['total'], 0, ',', '.'); ?>
                        </td>
                    </tr>

                </table>
            </div>

            <div style="margin-top:20px">
                <h3>Update Status</h3>
                <form action="../proses/proses.php" method="POST" style="display:flex;gap:10px;align-items:center">
                    <input type="hidden" name="aksi" value="update_status_pesanan">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

                    <select name="status" style="padding:10px;border:1px solid #ddd;border-radius:8px">
                        <option value="pending" <?php echo ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="process" <?php echo ($order['status'] == 'process') ? 'selected' : ''; ?>>Process</option>
                        <option value="done" <?php echo ($order['status'] == 'done') ? 'selected' : ''; ?>>Done</option>
                        <option value="cancel" <?php echo ($order['status'] == 'cancel') ? 'selected' : ''; ?>>Cancel</option>
                    </select>

                    <button class="btn" type="submit">Update Status</button>
                </form>
            </div>

        </div>

    </div>

</body>

</html>