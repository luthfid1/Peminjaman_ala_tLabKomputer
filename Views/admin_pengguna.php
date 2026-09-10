<?php
$activePage = 'pengguna';
$pageTitle = 'Kelola Pengguna - SEWANADA';
require_once 'Views/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">AKSES & OTORISASI</span>
        <h1 class="admin-title">Kelola Pengguna</h1>
        <p class="admin-subtitle">Atur akun admin, petugas perpustakaan musik, dan siswa/peminjam.</p>
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
            <h3 class="inventory-title">Daftar Pengguna Sistem</h3>
            <p class="inventory-sub">Total <?= count($daftarPengguna) ?> akun pengguna terdaftar.</p>
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
            <input type="text" name="search" placeholder="Cari nama atau username..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
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
                        Belum ada data pengguna.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPengguna as $u): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name"><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td>
                            <span class="badge-role badge-role-<?= htmlspecialchars($u['role']) ?>">
                                <?= ucfirst(htmlspecialchars($u['role'])) ?>
                            </span>
                        </td>
                        <td class="table-actions-cell">
                            <button type="button" class="btn-action-edit" onclick='editUser(<?= json_encode($u) ?>)'>
                                Ubah
                            </button>
                            <?php if ($u['id_users'] != $_SESSION['user']['id_users']): ?>
                                <a href="index.php?c=admin&a=hapus_pengguna&id=<?= $u['id_users'] ?>" class="btn-action-delete" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
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
                <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: Dimas Setiawan" required>
            </div>

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Pilih username unik" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 5 karakter" required>
            </div>

            <div class="form-group">
                <label class="form-label">Hak Akses (Role)</label>
                <select name="role" class="form-control" required>
                    <option value="peminjam">Peminjam (Siswa/Anggota)</option>
                    <option value="petugas">Petugas</option>
                    <option value="admin">Administrator</option>
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
            <input type="hidden" name="id_users" id="edit_user_id">

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="edit_user_nama" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" id="edit_user_username" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Hak Akses (Role)</label>
                <select name="role" id="edit_user_role" class="form-control" required>
                    <option value="peminjam">Peminjam (Siswa/Anggota)</option>
                    <option value="petugas">Petugas</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin ganti">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalUbahUser')">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('active');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}
function editUser(data) {
    document.getElementById('edit_user_id').value = data.id_users;
    document.getElementById('edit_user_nama').value = data.nama_lengkap;
    document.getElementById('edit_user_username').value = data.username;
    document.getElementById('edit_user_role').value = data.role;
    openModal('modalUbahUser');
}
</script>

<?php require_once 'Views/admin_footer.php'; ?>
