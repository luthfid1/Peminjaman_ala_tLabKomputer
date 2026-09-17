<?php
$activePage = 'profil';
$pageTitle = 'Profil Saya - NARA BAND';
require_once 'Views/peminjam_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">PENGATURAN AKUN</span>
        <h1 class="admin-title">Profil Saya</h1>
        <p class="admin-subtitle">Perbarui data diri, nomor kontak, alamat, dan kata sandi akun Anda.</p>
    </div>
</div>

<!-- ALERTS -->
<?php if (!empty($message)): ?>
    <div class="auth-alert auth-alert-success">
        <span><?= htmlspecialchars($message) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="auth-alert auth-alert-error">
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 24px; align-items: start;">
    
    <!-- PROFILE AVATAR CARD -->
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; text-align: center;">
        <div style="width: 84px; height: 84px; border-radius: 50%; background: linear-gradient(135deg, var(--teal-primary), var(--teal-dark)); color: white; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 700; margin: 0 auto 16px auto; box-shadow: 0 4px 10px rgba(20, 184, 166, 0.3);">
            <?php
                $nama = $user['nama_lengkap'] ?? 'Peminjam';
                $parts = explode(' ', trim($nama));
                $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                echo htmlspecialchars($initials);
            ?>
        </div>
        <h3 style="margin: 0 0 4px 0; font-size: 18px; color: #0f172a;"><?= htmlspecialchars($user['nama_lengkap'] ?? '-') ?></h3>
        <p style="margin: 0 0 16px 0; color: var(--text-muted); font-size: 13px;">@<?= htmlspecialchars($user['username'] ?? '-') ?></p>

        <div style="background: #f8fafc; border-radius: 8px; padding: 12px; font-size: 12px; text-align: left; color: #475569; line-height: 1.6;">
            <div style="margin-bottom: 6px;">
                <span style="color: var(--text-muted);">Peran:</span> 
                <strong style="color: var(--teal-dark); text-transform: capitalize;"><?= htmlspecialchars($user['role'] ?? 'peminjam') ?></strong>
            </div>
            <div style="margin-bottom: 6px;">
                <span style="color: var(--text-muted);">No. HP:</span> 
                <strong><?= !empty($user['no_hp']) ? htmlspecialchars($user['no_hp']) : '-' ?></strong>
            </div>
            <div>
                <span style="color: var(--text-muted);">Alamat:</span> 
                <strong><?= !empty($user['Alamat']) ? htmlspecialchars($user['Alamat']) : '-' ?></strong>
            </div>
        </div>
    </div>

    <!-- PROFILE EDIT FORM -->
    <div class="inventory-section-card" style="margin-bottom: 0;">
        <div class="inventory-header">
            <div>
                <h3 class="inventory-title">Ubah Informasi Pribadi</h3>
                <p class="inventory-sub">Pastikan data kontak dan alamat Anda selalu valid untuk keperluan administrasi peminjaman.</p>
            </div>
        </div>

        <form method="POST" action="index.php?c=peminjam&a=ubah_profil" style="padding: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Username <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Nomor HP / WhatsApp <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 08123456789" value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Domisili <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="alamat" class="form-control" placeholder="Alamat lengkap tempat tinggal" value="<?= htmlspecialchars($user['Alamat'] ?? '') ?>" required>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

            <div style="margin-bottom: 12px;">
                <h4 style="margin: 0 0 4px 0; font-size: 14px; color: #0f172a;">Ganti Kata Sandi (Opsional)</h4>
                <p style="margin: 0; font-size: 12px; color: var(--text-muted);">Biarkan kosong jika Anda tidak ingin mengubah kata sandi akun.</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Kata Sandi Baru</label>
                    <input type="password" name="password_baru" class="form-control" placeholder="Minimal 5 karakter">
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi kata sandi baru">
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-modal-submit" style="padding: 10px 24px;">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

</div>

<?php require_once 'Views/peminjam_footer.php'; ?>
