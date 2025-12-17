-- Database: toko_sepatu
CREATE DATABASE IF NOT EXISTS toko_sepatu;
USE toko_sepatu;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel produk
CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT DEFAULT 0,
    deskripsi TEXT,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    total INT NOT NULL,
    status ENUM('pending', 'process', 'done', 'cancel') DEFAULT 'pending',
    tanggal DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabel order_detail
CREATE TABLE IF NOT EXISTS order_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    produk_id INT NOT NULL,
    qty INT NOT NULL,
    harga INT NOT NULL,
    subtotal INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (produk_id) REFERENCES produk(id)
);

-- Insert data dummy users
-- Password: admin123 dan user123
INSERT INTO users (nama, email, password, role) VALUES
('Admin', 'admin@toko.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('User Demo', 'user@toko.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Insert data dummy produk
INSERT INTO produk (nama, harga, stok, deskripsi, gambar) VALUES
('Nike Air Force 1', 1200000, 15, 'Sepatu sneakers klasik dengan desain ikonik yang nyaman untuk penggunaan sehari-hari. Material premium dengan sol yang empuk.', 'nike-air-force.png'),
('Adidas Ultraboost Light', 2500000, 10, 'Sepatu lari premium dengan teknologi Boost untuk kenyamanan maksimal. Ringan dan responsif untuk performa terbaik.', 'adidas-ultraboost.jpg'),
('Converse Chuck Taylor All Star', 850000, 20, 'Sepatu casual legend dengan gaya timeless yang cocok untuk berbagai outfit. Desain klasik yang tidak pernah ketinggalan zaman.', 'converse-chuck.jpg'),
('Vans Old Skool', 950000, 18, 'Sepatu skateboard klasik dengan desain side stripe yang iconic. Durable dan stylish untuk aktivitas sehari-hari.', 'vans-oldskool.jpg'),
('Nike Dunk High', 1800000, 12, 'Sneakers basketball yang kini menjadi fashion icon. Desain retro dengan colorway yang menarik perhatian.', 'nike-dunk-high.png'),
('Adidas Superstar', 1100000, 25, 'Sepatu sport klasik dengan shell toe yang ikonik. Cocok untuk gaya casual dan streetwear.', 'adidas-superstar.jpg'),
('Nike Air Max 90', 1650000, 14, 'Sepatu dengan teknologi Air cushioning untuk kenyamanan maksimal. Desain sporty yang tetap stylish.', 'nike-airmax90.png'),
('New Balance 550', 1400000, 16, 'Retro basketball sneakers dengan desain vintage yang sedang trending. Kombinasi sempurna antara comfort dan style.', 'newbalance-550.jpg'),
('Adidas Samba OG', 1300000, 22, 'Sepatu futsal klasik yang menjadi fashion staple. Desain minimalis yang mudah dipadukan dengan berbagai outfit.', 'adidas-samba.jpg'),
('Converse Chuck 70 High', 1050000, 19, 'Versi premium dari Chuck Taylor dengan material lebih berkualitas. Insole yang lebih empuk untuk kenyamanan ekstra.', 'converse-70s.jpg');
