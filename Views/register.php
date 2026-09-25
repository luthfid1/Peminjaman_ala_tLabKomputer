<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Siswa - Peminjaman Alat Lab Komputer</title>
    <link rel="stylesheet" href="Assets/css/style.css">
</head>
<body>

<div class="auth-page-wrapper" style="padding: 40px 20px;">
    <div class="auth-card" style="max-width: 520px;">
        
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

        <h1 class="auth-heading">Pendaftaran Siswa</h1>
        <p class="auth-subheading">Registrasi akun untuk peminjaman alat laboratorium komputer</p>

        <!-- ALERTS -->
        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert-error">
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- FORM REGISTER -->
        <form method="POST" action="index.php?c=auth&a=register" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama" class="form-label">Nama Lengkap Siswa</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Muhammad Rayhan" required autofocus value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label>
                <input type="text" id="nis" name="nis" class="form-control" placeholder="Contoh: 10238495" required value="<?= htmlspecialchars($_POST['nis'] ?? '') ?>">
            </div>

            <div class="form-row" style="display: flex; gap: 14px;">
                <div class="form-group" style="flex: 1;">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" list="daftar-kelas" id="kelas" name="kelas" class="form-control" placeholder="Pilih / isi kelas" required value="<?= htmlspecialchars($_POST['kelas'] ?? '') ?>">
                    <datalist id="daftar-kelas">
                        <option value="X RPL 1">
                        <option value="X RPL 2">
                        <option value="XI RPL 1">
                        <option value="XI RPL 2">
                        <option value="XII RPL 1">
                        <option value="XII RPL 2">
                        <option value="XII RPL 3">
                        <option value="XII RPL 4">
                        <option value="X TKJ 1">
                        <option value="XI TKJ 1">
                        <option value="XII TKJ 1">
                        <option value="XII TKJ 2">
                    </datalist>
                </div>
                <div class="form-group" style="flex: 1.5;">
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <input type="text" list="daftar-jurusan" id="jurusan" name="jurusan" class="form-control" placeholder="Pilih / isi jurusan" required value="<?= htmlspecialchars($_POST['jurusan'] ?? '') ?>">
                    <datalist id="daftar-jurusan">
                        <option value="Rekayasa Perangkat Lunak">
                        <option value="Teknik Komputer dan Jaringan">
                        <option value="Sistem Informasi Jaringan dan Aplikasi">
                        <option value="Multimedia / Desain Komunikasi Visual">
                    </datalist>
                </div>
            </div>

            <div class="form-group">
                <label for="no_telp" class="form-label">No. HP / WhatsApp</label>
                <input type="text" id="no_telp" name="no_telp" class="form-control" placeholder="Contoh: 081234567890" value="<?= htmlspecialchars($_POST['no_telp'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="foto_kartu_pelajar" class="form-label">Foto Kartu Pelajar <span style="font-weight: 400; color: var(--text-muted);">(Opsional)</span></label>
                <input type="file" id="foto_kartu_pelajar" name="foto_kartu_pelajar" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Username Akun</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Pilih username unik untuk login" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-row" style="display: flex; gap: 14px;">
                <div class="form-group" style="flex: 1;">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 5 karakter" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="confirm_password" class="form-label">Konfirmasi Sandi</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Ulangi sandi" required>
                </div>
            </div>

            <button type="submit" class="btn-auth-submit" style="margin-top: 10px;">Daftar Akun Siswa</button>
        </form>

        <div class="auth-footer" style="margin-top: 20px; text-align: center; font-size: 13px; color: var(--text-muted);">
            Sudah punya akun? <a href="index.php?c=auth&a=login" style="color: var(--teal-dark); font-weight: 700; text-decoration: none;">Masuk di sini</a>
        </div>

        <a href="index.php" class="back-home-link" style="display: block; margin-top: 14px; text-align: center; text-decoration: none; color: var(--text-muted); font-size: 13px;">&larr; Kembali ke Beranda</a>
    </div>
</div>

</body>
</html>
