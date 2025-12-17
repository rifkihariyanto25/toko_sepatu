<?php
session_start();
include '../config/database.php';

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

/* =====================
   LOGIN
===================== */
if ($aksi == 'login') {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($q);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../user/index.php");
        }
    } else {
        header("Location: ../login.php?error=1");
    }
}

/* =====================
   REGISTER
===================== */ elseif ($aksi == 'register') {

    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Cek password cocok
    if ($password !== $password_confirm) {
        header("Location: ../register.php?error=password");
        exit;
    }

    // Cek email sudah ada
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        header("Location: ../register.php?error=email");
        exit;
    }

    // Hash password dan insert
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $result = mysqli_query($conn, "INSERT INTO users (nama, email, password, role) 
                                    VALUES ('$nama', '$email', '$password_hash', 'user')");

    if ($result) {
        header("Location: ../login.php?register=success");
    } else {
        header("Location: ../register.php?error=1");
    }
}

/* =====================
   LOGOUT
===================== */ elseif ($aksi == 'logout') {
    session_destroy();
    header("Location: ../user/index.php");
}

/* =====================
   TAMBAH KE CART
===================== */ elseif ($aksi == 'tambah_cart') {

    $id_produk = (int)$_POST['id_produk'];
    $qty = (int)$_POST['qty'];

    // Cek stok produk
    $q = mysqli_query($conn, "SELECT stok FROM produk WHERE id='$id_produk'");
    $produk = mysqli_fetch_assoc($q);

    if (!$produk) {
        header("Location: ../user/index.php?error=produk_tidak_ada");
        exit;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Hitung total qty di cart
    $total_cart = isset($_SESSION['cart'][$id_produk]) ? $_SESSION['cart'][$id_produk] : 0;
    $total_qty = $total_cart + $qty;

    // Validasi stok
    if ($total_qty > $produk['stok']) {
        header("Location: ../user/detail.php?id=$id_produk&error=stok_habis");
        exit;
    }

    if (isset($_SESSION['cart'][$id_produk])) {
        $_SESSION['cart'][$id_produk] += $qty;
    } else {
        $_SESSION['cart'][$id_produk] = $qty;
    }

    header("Location: ../user/cart.php?success=tambah");
}

/* =====================
   UPDATE CART
===================== */ elseif ($aksi == 'update_cart') {

    if (isset($_POST['qty'])) {
        foreach ($_POST['qty'] as $id_produk => $qty) {
            if ($qty > 0) {
                $_SESSION['cart'][$id_produk] = (int)$qty;
            } else {
                unset($_SESSION['cart'][$id_produk]);
            }
        }
    }

    header("Location: ../user/cart.php");
}

/* =====================
   HAPUS DARI CART
===================== */ elseif ($aksi == 'hapus_cart') {

    $id_produk = (int)$_GET['id'];

    if (isset($_SESSION['cart'][$id_produk])) {
        unset($_SESSION['cart'][$id_produk]);
    }

    header("Location: ../user/cart.php");
}

/* =====================
   CHECKOUT
===================== */ elseif ($aksi == 'checkout') {

    if (!isset($_SESSION['id_user'])) {
        header("Location: ../login.php");
        exit;
    }

    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        header("Location: ../user/cart.php");
        exit;
    }

    $user_id = (int)$_SESSION['id_user'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $tanggal = date('Y-m-d H:i:s');
    $total = 0;

    // Hitung total dari database
    foreach ($_SESSION['cart'] as $id_produk => $qty) {
        $q = mysqli_query($conn, "SELECT harga FROM produk WHERE id='$id_produk'");
        $produk = mysqli_fetch_assoc($q);
        if ($produk) {
            $total += $produk['harga'] * $qty;
        }
    }

    // Insert order
    mysqli_query($conn, "INSERT INTO orders (user_id, nama, alamat, total, tanggal, status)
                          VALUES ('$user_id', '$nama', '$alamat', '$total', '$tanggal', 'pending')");

    $order_id = mysqli_insert_id($conn);

    // Insert order detail dan kurangi stok
    foreach ($_SESSION['cart'] as $id_produk => $qty) {
        $q = mysqli_query($conn, "SELECT harga, stok FROM produk WHERE id='$id_produk'");
        $produk = mysqli_fetch_assoc($q);
        if ($produk) {
            $harga = $produk['harga'];
            $subtotal = $harga * $qty;

            // Insert order detail
            mysqli_query($conn, "INSERT INTO order_detail (order_id, produk_id, qty, harga, subtotal)
                                  VALUES ('$order_id', '$id_produk', '$qty', '$harga', '$subtotal')");

            // Kurangi stok produk
            $stok_baru = $produk['stok'] - $qty;
            mysqli_query($conn, "UPDATE produk SET stok='$stok_baru' WHERE id='$id_produk'");
        }
    }

    unset($_SESSION['cart']);

    header("Location: ../user/pesanan.php?success=checkout");
}

/* =====================
   TAMBAH PRODUK (ADMIN)
===================== */ elseif ($aksi == 'tambah_produk') {

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../login.php");
        exit;
    }

    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = (int)$_POST['harga'];
    $stok  = (int)$_POST['stok'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = time() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            "../assets/sepatu/" . $gambar
        );
    }

    mysqli_query($conn, "INSERT INTO produk (nama, harga, stok, deskripsi, gambar)
                          VALUES ('$nama', '$harga', '$stok', '$deskripsi', '$gambar')");

    header("Location: ../admin/produk/index.php?success=tambah");
}

/* =====================
   EDIT PRODUK
===================== */ elseif ($aksi == 'edit_produk') {

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../login.php");
        exit;
    }

    $id    = (int)$_POST['id'];
    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = (int)$_POST['harga'];
    $stok  = (int)$_POST['stok'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Cek jika ada upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = time() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            "../assets/sepatu/" . $gambar
        );

        mysqli_query($conn, "UPDATE produk
                              SET nama='$nama', harga='$harga', stok='$stok', 
                                  deskripsi='$deskripsi', gambar='$gambar'
                              WHERE id='$id'");
    } else {
        mysqli_query($conn, "UPDATE produk
                              SET nama='$nama', harga='$harga', stok='$stok', 
                                  deskripsi='$deskripsi'
                              WHERE id='$id'");
    }

    header("Location: ../admin/produk/index.php?success=edit");
}

/* =====================
   HAPUS PRODUK
===================== */ elseif ($aksi == 'hapus_produk') {

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../login.php");
        exit;
    }

    $id = (int)$_GET['id'];

    // Hapus gambar jika ada
    $q = mysqli_query($conn, "SELECT gambar FROM produk WHERE id='$id'");
    $produk = mysqli_fetch_assoc($q);
    if ($produk && $produk['gambar'] && file_exists("../assets/sepatu/" . $produk['gambar'])) {
        unlink("../assets/sepatu/" . $produk['gambar']);
    }

    mysqli_query($conn, "DELETE FROM produk WHERE id='$id'");

    header("Location: ../admin/produk/index.php?success=hapus");
}

/* =====================
   DEFAULT
===================== */ else {
    echo "Aksi tidak dikenal";
}
