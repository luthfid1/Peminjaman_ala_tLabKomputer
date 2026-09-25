<?php
$activePage = 'pengguna';
$pageTitle = 'Kelola Pengguna - Pengelola Lab';
require_once __DIR__ . '/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">AKSES & OTORISASI</span>
        <h1 class="admin-title">Kelola Data Pengguna</h1>
        <p class="admin-subtitle">Atur akun Pengelola Lab, Ketua Jurusan, Petugas Laboran, dan Siswa Peminjam.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="openModal('modalTambahUser')">
        + Tambah Pengguna
    </button>
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

<!-- INVENTORY CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Daftar Akun Pengguna</h3>
            <p class="inventory-sub">Total <?= count($daftarPengguna) ?> akun terdaftar di sistem.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="pengguna">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari nama, username, atau role..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Hak Akses (Role)</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPengguna)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada data Pengguna.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPengguna as $u): 
                    $roleLabel = ucfirst($u['role']);
                    if ($u['role'] === 'admin') $roleLabel = 'Admin (Ketua Jurusan)';
                    if ($u['role'] === 'pengelola') $roleLabel = 'Admin (Pengelola Lab)';
                    if ($u['role'] === 'petugas') $roleLabel = 'Petugas (Laboran)';
                    if ($u['role'] === 'peminjam') $roleLabel = 'Peminjam (Siswa)';
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name"><?= htmlspecialchars($u['nama']) ?></td>
                        <td><code>@<?= htmlspecialchars($u['username']) ?></code></td>
                        <td>
                            <span class="badge-role badge-role-<?= htmlspecialchars($u['role']) ?>" style="
                                padding: 4px 10px;
                                border-radius: 999px;
                                font-size: 11px;
                                font-weight: 700;
                                background: <?= ($u['role'] === 'pengelola' || $u['role'] === 'admin') ? 'var(--primary-navy)' : 'var(--teal-bg)' ?>;
                                color: <?= ($u['role'] === 'pengelola' || $u['role'] === 'admin') ? '#ffffff' : 'var(--teal-dark)' ?>;
                            ">
                                <?= htmlspecialchars($roleLabel) ?>
                            </span>
                        </td>
                        <td class="table-actions-cell">
                            <button type="button" class="btn-action-edit" onclick='editUser(<?= json_encode($u) ?>)'>
                                Ubah
                            </button>
                            <?php 
                                $currUserId = $_SESSION['user']['id_user'] ?? $_SESSION['user']['id_users'] ?? 0;
                                if ($u['id_user'] != $currUserId): 
                            ?>
                                <a href="index.php?c=admin&a=hapus_pengguna&id=<?= $u['id_user'] ?>" class="btn-action-delete" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    Hapus
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- MODAL TAMBAH USER -->
<div class="modal-overlay" id="modalTambahUser">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Pengguna Baru</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalTambahUser')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=tambah_pengguna">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso, S.Kom" required>
            </div>

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Contoh: budi_lab" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>

            <div class="form-group">
                <label class="form-label">Hak Akses (Role)</label>
                <select name="role" class="form-control" required>
                    <option value="pengelola">Admin (Pengelola Lab)</option>
                    <option value="admin">Admin (Ketua Jurusan)</option>
                    <option value="petugas">Petugas (Laboran)</option>
                    <option value="peminjam">Peminjam (Siswa)</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalTambahUser')">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL UBAH USER -->
<div class="modal-overlay" id="modalUbahUser">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ubah Data Pengguna</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalUbahUser')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=ubah_pengguna">
            <input type="hidden" name="id_user" id="edit_user_id">

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" id="edit_user_nama" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" id="edit_user_username" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password baru jika ingin mengganti">
            </div>

            <div class="form-group">
                <label class="form-label">Hak Akses (Role)</label>
                <select name="role" id="edit_user_role" class="form-control" required>
                    <option value="pengelola">Admin (Pengelola Lab)</option>
                    <option value="admin">Admin (Ketua Jurusan)</option>
                    <option value="petugas">Petugas (Laboran)</option>
                    <option value="peminjam">Peminjam (Siswa)</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalUbahUser')">Batal</button>
                <button type="submit" class="btn-modal-submit">Perbarui Pengguna</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function editUser(data) {
    document.getElementById('edit_user_id').value = data.id_user;
    document.getElementById('edit_user_nama').value = data.nama;
    document.getElementById('edit_user_username').value = data.username;
    document.getElementById('edit_user_role').value = data.role;
    openModal('modalUbahUser');
}
</script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
