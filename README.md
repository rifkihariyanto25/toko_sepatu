# Website Toko Sepatu

Website e-commerce sederhana untuk toko sepatu dengan fitur:

- User bisa cek produk tanpa login
- User harus login untuk checkout
- Admin bisa CRUD (Create, Read, Update, Delete) produk

## Cara Install

1. **Import Database**

   - Buka phpMyAdmin
   - Import file `database.sql`
   - Database `toko_sepatu` akan otomatis dibuat

2. **Konfigurasi Database**

   - File konfigurasi sudah ada di `config/database.php`
   - Default setting:
     - Host: localhost
     - User: root
     - Password: (kosong)
     - Database: toko_sepatu

3. **Jalankan Website**
   - Pastikan XAMPP sudah running (Apache & MySQL)
   - Akses: `http://localhost/Joki/Sepatu/`

## Login Credentials

### Admin

- Email: `admin@toko.com`
- Password: `admin123`

### User

- Email: `user@toko.com`
- Password: `user123`

## Fitur Website

### User (Tanpa Login)

- ✅ Melihat semua produk
- ✅ Melihat detail produk
- ✅ Menambahkan produk ke keranjang
- ✅ Melihat keranjang belanja
- ❌ Checkout (harus login dulu)

### User (Sudah Login)

- ✅ Semua fitur user tanpa login
- ✅ Checkout dan buat pesanan
- ✅ Logout

### Admin

- ✅ Login sebagai admin
- ✅ Dashboard admin
- ✅ **CREATE** - Tambah produk baru
- ✅ **READ** - Lihat semua produk
- ✅ **UPDATE** - Edit produk
- ✅ **DELETE** - Hapus produk
- ✅ Upload gambar produk
- ✅ Logout

## Struktur Folder

```
Sepatu/
├── admin/              # Panel admin
│   ├── index.php      # Dashboard admin
│   └── produk/        # Kelola produk
│       ├── index.php  # List produk
│       ├── tambah.php # Tambah produk
│       ├── edit.php   # Edit produk
│       └── hapus.php  # Hapus produk
├── assets/            # Asset website
│   ├── style.css     # CSS styling
│   └── sepatu/       # Folder gambar produk
├── config/           # Konfigurasi
│   └── database.php  # Koneksi database
├── proses/           # Proses backend
│   └── proses.php    # Handler semua aksi
├── user/             # Halaman user
│   ├── index.php     # Halaman utama/produk
│   ├── detail.php    # Detail produk
│   ├── cart.php      # Keranjang
│   └── checkout.php  # Checkout
├── index.php         # Redirect ke user
├── login.php         # Halaman login
└── database.sql      # File SQL database
```

## Cara Menggunakan

### Sebagai User

1. Buka website
2. Browse produk yang tersedia
3. Klik "Lihat Detail" untuk melihat detail produk
4. Tambahkan ke keranjang
5. Klik "Cart" untuk melihat keranjang
6. Klik "Lanjut ke Checkout"
7. Jika belum login, akan diminta login
8. Isi data pengiriman dan konfirmasi pesanan

### Sebagai Admin

1. Login dengan akun admin
2. Masuk ke menu "Produk"
3. **Tambah Produk**: Klik "+ Tambah Produk"
4. **Edit Produk**: Klik tombol "Edit" pada produk
5. **Hapus Produk**: Klik tombol "Hapus" (akan konfirmasi)
6. Upload gambar produk saat tambah/edit

## Catatan

- Password sudah terenkripsi menggunakan `password_hash()` PHP
- Upload gambar produk disimpan di folder `assets/sepatu/`
- Session digunakan untuk login dan keranjang belanja
- SQL Injection sudah diproteksi dengan `mysqli_real_escape_string()`
