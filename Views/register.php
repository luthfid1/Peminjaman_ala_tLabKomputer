<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - NARA BAND</title>
    <link rel="stylesheet" href="Assets/css/style.css">
</head>
<body>

<div class="auth-page-wrapper">
    <div class="auth-card">
        
        <!-- BRAND -->
        <a href="index.php" class="auth-brand-center">
            <div class="brand-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                </svg>
            </div>
            <span class="brand-title">NARA BAND</span>
        </a>

        <h1 class="auth-heading">Buat Akun Baru</h1>
        <p class="auth-subheading">Daftar sebagai peminjam untuk mulai menyewa alat</p>

        <!-- ALERTS -->
        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert-error">
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- FORM REGISTER -->
        <form method="POST" action="index.php?c=auth&a=register">
            <div class="form-group">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" placeholder="Contoh: Naya Pratama" required autofocus value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Pilih username unik" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 5 karakter" required>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Ulangi kata sandi" required>
            </div>

            <button type="submit" class="btn-auth-submit">Daftar</button>
        </form>

        <div class="auth-footer">
            Sudah punya akun? <a href="index.php?c=auth&a=login">Masuk di sini</a>
        </div>

        <a href="index.php" class="back-home-link">Kembali ke Beranda</a>
    </div>
</div>

</body>
</html>
