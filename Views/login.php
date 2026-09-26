<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Peminjaman Alat Lab Komputer</title>
    <link rel="stylesheet" href="Assets/css/style.css">
</head>
<body>

<div class="auth-page-wrapper">
    <div class="auth-card">
        
        <!-- BRAND -->
        <a href="index.php" class="auth-brand-center">
            <div class="brand-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
            <span class="brand-title">LAB KOMPUTER</span>
        </a>

        <h1 class="auth-heading">Selamat Datang</h1>
        <p class="auth-subheading">Sistem Peminjaman Alat Laboratorium Komputer</p>

        <!-- ALERTS -->
        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert-error">
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="auth-alert auth-alert-success">
                <span><?= htmlspecialchars($success) ?></span>
            </div>
        <?php endif; ?>

        <!-- ACTIVE SESSION NOTICE -->
        <?php if (isset($_SESSION['user'])): ?>
            <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 12px; margin-bottom: 16px; color: #334155; display: flex; justify-content: space-between; align-items: center;">
                <span>Sesi aktif: <strong><?= htmlspecialchars($_SESSION['user']['nama'] ?? $_SESSION['user']['nama_lengkap']) ?></strong> (<?= htmlspecialchars($_SESSION['user']['role']) ?>)</span>
                <a href="index.php?c=auth&a=logout" style="color: #b91c1c; font-weight: 700; text-decoration: none;">Keluar &rarr;</a>
            </div>
        <?php endif; ?>

        <!-- FORM LOGIN -->
        <form method="POST" action="index.php?c=auth&a=login">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Masukan Username" required autofocus value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan kata sandi" required>
            </div>

            <button type="submit" class="btn-auth-submit">Masuk ke Sistem</button>
        </form>

        <div class="auth-footer" style="margin-top: 18px; text-align: center; font-size: 13px; color: var(--text-muted);">
            Belum punya akun? <a href="index.php?c=auth&a=register" style="color: var(--teal-dark); font-weight: 700; text-decoration: none;">Daftar sekarang</a>
        </div>

        <a href="index.php" class="back-home-link" style="display: block; margin-top: 14px; text-align: center; text-decoration: none; color: var(--text-muted); font-size: 13px;">&larr; Kembali ke Beranda</a>
    </div>
</div>

</body>
</html>
