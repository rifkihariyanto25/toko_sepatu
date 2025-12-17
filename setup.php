<?php
include 'config/database.php';

echo "<h2>Setup Database Toko Sepatu</h2>";

// Tabel users
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_users)) {
    echo "✓ Tabel users berhasil dibuat<br>";
} else {
    echo "✗ Error tabel users: " . mysqli_error($conn) . "<br>";
}

// Tabel produk
$sql_produk = "CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT DEFAULT 0,
    deskripsi TEXT,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_produk)) {
    echo "✓ Tabel produk berhasil dibuat<br>";
} else {
    echo "✗ Error tabel produk: " . mysqli_error($conn) . "<br>";
}

// Tabel orders
$sql_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    total INT NOT NULL,
    status ENUM('pending', 'process', 'done', 'cancel') DEFAULT 'pending',
    tanggal DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if (mysqli_query($conn, $sql_orders)) {
    echo "✓ Tabel orders berhasil dibuat<br>";
} else {
    echo "✗ Error tabel orders: " . mysqli_error($conn) . "<br>";
}

// Tabel order_detail
$sql_order_detail = "CREATE TABLE IF NOT EXISTS order_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    produk_id INT NOT NULL,
    qty INT NOT NULL,
    harga INT NOT NULL,
    subtotal INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (produk_id) REFERENCES produk(id)
)";

if (mysqli_query($conn, $sql_order_detail)) {
    echo "✓ Tabel order_detail berhasil dibuat<br>";
} else {
    echo "✗ Error tabel order_detail: " . mysqli_error($conn) . "<br>";
}

echo "<br><h3>Insert Data Dummy</h3>";

// Insert users (password: admin123 dan user123)
$check_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$row = mysqli_fetch_assoc($check_users);

if ($row['total'] == 0) {
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO users (nama, email, password, role) VALUES
    ('Admin', 'admin@toko.com', '$password_hash', 'admin'),
    ('User Demo', 'user@toko.com', '$password_hash', 'user')");

    echo "✓ Data users berhasil ditambahkan<br>";
} else {
    echo "⚠ Data users sudah ada<br>";
}

// Insert produk dummy
$check_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
$row = mysqli_fetch_assoc($check_produk);

if ($row['total'] == 0) {
    echo "<h4>Menambahkan data produk...</h4>";

    $insert = mysqli_query($conn, "INSERT INTO produk (nama, harga, stok, deskripsi, gambar) VALUES
    ('Nike Air Force 1', 1200000, 15, 'Sepatu sneakers klasik dengan desain ikonik yang nyaman untuk penggunaan sehari-hari. Material premium dengan sol yang empuk.', ''),
    ('Adidas Ultraboost Light', 2500000, 10, 'Sepatu lari premium dengan teknologi Boost untuk kenyamanan maksimal. Ringan dan responsif untuk performa terbaik.', ''),
    ('Converse Chuck Taylor All Star', 850000, 20, 'Sepatu casual legend dengan gaya timeless yang cocok untuk berbagai outfit. Desain klasik yang tidak pernah ketinggalan zaman.', ''),
    ('Vans Old Skool', 950000, 18, 'Sepatu skateboard klasik dengan desain side stripe yang iconic. Durable dan stylish untuk aktivitas sehari-hari.', ''),
    ('Nike Dunk High', 1800000, 12, 'Sneakers basketball yang kini menjadi fashion icon. Desain retro dengan colorway yang menarik perhatian.', ''),
    ('Adidas Superstar', 1100000, 25, 'Sepatu sport klasik dengan shell toe yang ikonik. Cocok untuk gaya casual dan streetwear.', ''),
    ('Nike Air Max 90', 1650000, 14, 'Sepatu dengan teknologi Air cushioning untuk kenyamanan maksimal. Desain sporty yang tetap stylish.', ''),
    ('New Balance 550', 1400000, 16, 'Retro basketball sneakers dengan desain vintage yang sedang trending. Kombinasi sempurna antara comfort dan style.', ''),
    ('Adidas Samba OG', 1300000, 22, 'Sepatu futsal klasik yang menjadi fashion staple. Desain minimalis yang mudah dipadukan dengan berbagai outfit.', ''),
    ('Converse Chuck 70 High', 1050000, 19, 'Versi premium dari Chuck Taylor dengan material lebih berkualitas. Insole yang lebih empuk untuk kenyamanan ekstra.', '')");

    if ($insert) {
        echo "✓ 10 produk berhasil ditambahkan<br>";
        echo "<p style='color:#FF9800'>⚠ Gambar produk kosong, silakan upload via Admin Panel</p>";
    } else {
        echo "✗ Error insert produk: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "⚠ Data produk sudah ada (" . $row['total'] . " produk)<br>";
}
echo "<br><h3 style='color:green'>✓ Setup selesai!</h3>";
echo "<p><a href='user/index.php' style='display:inline-block;padding:10px 20px;background:#4CAF50;color:white;text-decoration:none;border-radius:8px'>Buka Website</a></p>";
echo "<p><a href='login.php' style='display:inline-block;padding:10px 20px;background:#2196F3;color:white;text-decoration:none;border-radius:8px'>Login Admin</a></p>";

mysqli_close($conn);
