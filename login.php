<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="form-box">
        <div class="eyebrow" style="text-align:center">Welcome back</div>
        <h2>Masuk ke akun</h2>

        <?php if (isset($_GET['error'])): ?>
            <p class="notice notice-error">Email atau password salah!</p>
        <?php endif; ?>

        <?php if (isset($_GET['checkout'])): ?>
            <p class="notice" style="background:#fff8df;color:#8f7115">Silakan login untuk checkout</p>
        <?php endif; ?>

        <?php if (isset($_GET['register']) && $_GET['register'] == 'success'): ?>
            <p class="notice">✓ Registrasi berhasil! Silakan login.</p>
        <?php endif; ?>

        <form action="proses/proses.php" method="POST">
            <input type="hidden" name="aksi" value="login">

            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button class="btn" style="width:100%">Masuk</button>
        </form>

        <p style="text-align:center;margin-top:20px">
            Belum punya akun? <a href="register.php">Daftar disini</a>
        </p>
        <p style="text-align:center">
            <a href="user/index.php">← Kembali ke Beranda</a>
        </p>
    </div>

</body>

</html>