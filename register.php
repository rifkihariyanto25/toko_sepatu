<!DOCTYPE html>
<html>

<head>
    <title>Register - Daftar Akun</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="form-box">
        <div class="eyebrow" style="text-align:center">Start your journey</div>
        <h2>Buat akun baru</h2>

        <?php if (isset($_GET['error'])): ?>
            <p class="notice notice-error">
                <?php
                if ($_GET['error'] == 'email') echo 'Email sudah digunakan!';
                elseif ($_GET['error'] == 'password') echo 'Password tidak cocok!';
                else echo 'Registrasi gagal!';
                ?>
            </p>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <p class="notice">Registrasi berhasil! Silakan login.</p>
        <?php endif; ?>

        <form action="proses/proses.php" method="POST">
            <input type="hidden" name="aksi" value="register">

            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required minlength="6">
            <input type="password" name="password_confirm" placeholder="Konfirmasi Password" required minlength="6">

            <button class="btn" style="width:100%">Daftar</button>
        </form>

        <p style="text-align:center;margin-top:20px">
            Sudah punya akun? <a href="login.php">Login disini</a>
        </p>
        <p style="text-align:center">
            <a href="user/index.php">← Kembali ke Beranda</a>
        </p>
    </div>

</body>

</html>