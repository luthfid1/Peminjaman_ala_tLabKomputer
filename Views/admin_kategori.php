<?php
$activePage = 'kategori';
$pageTitle = 'Kelola Kategori Alat - SEWANADA';
require_once 'Views/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">KLASIFIKASI</span>
        <h1 class="admin-title">Kelola Kategori</h1>
        <p class="admin-subtitle">Atur pengelompokan kategori alat musik seperti Gitar, Drum, Keyboard, dll.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="openModal('modalTambahKategori')">
        + Tambah Kategori
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
            <h3 class="inventory-title">Daftar Kategori</h3>
            <p class="inventory-sub">Total <?= count($daftarKategori) ?> kategori terdaftar.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="kategori">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari kategori..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Tanggal Dibuat</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarKategori)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada data kategori. Klik "+ Tambah Kategori" untuk membuat baru.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarKategori as $kat): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name"><?= htmlspecialchars($kat['nama_kategori']) ?></td>
                        <td><?= htmlspecialchars($kat['created_at'] ?? '-') ?></td>
                        <td class="table-actions-cell">
                            <button type="button" class="btn-action-edit" onclick='editKategori(<?= json_encode($kat) ?>)'>
                                Ubah
                            </button>
                            <a href="index.php?c=admin&a=hapus_kategori&id=<?= $kat['id'] ?>" class="btn-action-delete" onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- MODAL TAMBAH KATEGORI -->
<div class="modal-overlay" id="modalTambahKategori">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Kategori Baru</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalTambahKategori')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=tambah_kategori">
            <div class="form-group">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Gitar & Bass" required>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalTambahKategori')">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL UBAH KATEGORI -->
<div class="modal-overlay" id="modalUbahKategori">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ubah Kategori</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalUbahKategori')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=ubah_kategori">
            <input type="hidden" name="id" id="edit_kat_id">

            <div class="form-group">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="edit_kat_nama" class="form-control" required>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalUbahKategori')">Batal</button>
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
function editKategori(data) {
    document.getElementById('edit_kat_id').value = data.id;
    document.getElementById('edit_kat_nama').value = data.nama_kategori;
    openModal('modalUbahKategori');
}
</script>

<?php require_once 'Views/admin_footer.php'; ?>
