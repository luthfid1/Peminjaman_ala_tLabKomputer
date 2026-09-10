<?php
$activePage = 'alat';
$pageTitle = 'Kelola Data Alat - SEWANADA';
require_once 'Views/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">INVENTARIS MUSIK</span>
        <h1 class="admin-title">Kelola Data Alat</h1>
        <p class="admin-subtitle">Tambah, ubah, dan pantau ketersediaan stok instrumen musik.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="openModal('modalTambahAlat')">
        + Tambah Alat
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
            <h3 class="inventory-title">Daftar Alat Musik</h3>
            <p class="inventory-sub">Total <?= count($daftarAlat) ?> alat tercatat di sistem.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="alat">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari nama atau kategori..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Harga Sewa</th>
                <th>Stok</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarAlat)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada data alat musik. Klik "+ Tambah Alat" untuk membuat baru.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarAlat as $alat): 
                    $isReady = ($alat['jumlah_stok'] > 0);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name"><?= htmlspecialchars($alat['nama_alat']) ?></td>
                        <td><?= htmlspecialchars($alat['nama_kategori'] ?? 'Tanpa Kategori') ?></td>
                        <td>Rp<?= number_format($alat['harga_sewa'], 0, ',', '.') ?> / hari</td>
                        <td><?= (int)$alat['jumlah_stok'] ?> unit</td>
                        <td>
                            <?php if ($isReady): ?>
                                <span class="status-badge badge-ready">Siap</span>
                            <?php else: ?>
                                <span class="status-badge badge-busy">Dipinjam</span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions-cell">
                            <button type="button" class="btn-action-edit" onclick='editAlat(<?= json_encode($alat) ?>)'>
                                Ubah
                            </button>
                            <a href="index.php?c=admin&a=hapus_alat&id=<?= $alat['id'] ?>" class="btn-action-delete" onclick="return confirm('Yakin ingin menghapus alat ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- MODAL TAMBAH ALAT -->
<div class="modal-overlay" id="modalTambahAlat">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Alat Musik</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalTambahAlat')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=tambah_alat">
            <div class="form-group">
                <label class="form-label">Nama Alat</label>
                <input type="text" name="nama_alat" class="form-control" placeholder="Contoh: Gitar Akustik Yamaha F310" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($daftarKategori as $kat): ?>
                        <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Harga Sewa (per hari)</label>
                <input type="number" name="harga_sewa" class="form-control" placeholder="Contoh: 25000" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah Stok</label>
                <input type="number" name="jumlah_stok" class="form-control" placeholder="Contoh: 3" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label">Spesifikasi / Keterangan</label>
                <textarea name="spesifikasi" class="form-control" rows="3" placeholder="Rincian kondisi fisik, kelengkapan, dll."></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalTambahAlat')">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Alat</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL UBAH ALAT -->
<div class="modal-overlay" id="modalUbahAlat">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ubah Data Alat</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalUbahAlat')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=ubah_alat">
            <input type="hidden" name="id" id="edit_id">

            <div class="form-group">
                <label class="form-label">Nama Alat</label>
                <input type="text" name="nama_alat" id="edit_nama_alat" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" id="edit_kategori_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($daftarKategori as $kat): ?>
                        <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Harga Sewa (per hari)</label>
                <input type="number" name="harga_sewa" id="edit_harga_sewa" class="form-control" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah Stok</label>
                <input type="number" name="jumlah_stok" id="edit_jumlah_stok" class="form-control" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label">Spesifikasi / Keterangan</label>
                <textarea name="spesifikasi" id="edit_spesifikasi" class="form-control" rows="3"></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalUbahAlat')">Batal</button>
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
function editAlat(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_nama_alat').value = data.nama_alat;
    document.getElementById('edit_kategori_id').value = data.kategori_id;
    document.getElementById('edit_harga_sewa').value = parseInt(data.harga_sewa);
    document.getElementById('edit_jumlah_stok').value = data.jumlah_stok;
    document.getElementById('edit_spesifikasi').value = data.spesifikasi || '';
    openModal('modalUbahAlat');
}
</script>

<?php require_once 'Views/admin_footer.php'; ?>
