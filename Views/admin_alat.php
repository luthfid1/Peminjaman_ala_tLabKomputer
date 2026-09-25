<?php
$activePage = 'alat';
$pageTitle = 'Kelola Data Alat Lab - Pengelola Lab';
require_once __DIR__ . '/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">INVENTARIS LABORATORIUM</span>
        <h1 class="admin-title">Kelola Data Alat Lab</h1>
        <p class="admin-subtitle">Tambah, ubah, hapus, dan pantau ketersediaan stok perangkat keras & alat laboratorium komputer.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="openModal('modalTambahAlat')">
        + Tambah Alat Lab
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
            <h3 class="inventory-title">Daftar Alat Laboratorium Komputer</h3>
            <p class="inventory-sub">Total <?= count($daftarAlat) ?> item alat terdaftar di lab.</p>
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
            <input type="text" name="search" placeholder="Cari nama, kode, atau kategori..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Jumlah Stok</th>
                <th>Kondisi</th>
                <th>Deskripsi</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarAlat)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada data alat laboratorium. Klik "+ Tambah Alat Lab" untuk menambah data baru.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarAlat as $alat): 
                    $isReady = ($alat['jumlah'] > 0);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span style="font-family: monospace; font-size: 12px; font-weight: 700; background: #e2e8f0; padding: 2px 6px; border-radius: 4px;"><?= htmlspecialchars($alat['kode']) ?></span></td>
                        <td class="col-instrument-name"><?= htmlspecialchars($alat['nama_alat']) ?></td>
                        <td><?= htmlspecialchars($alat['nama_kategori'] ?? 'Tanpa Kategori') ?></td>
                        <td>
                            <strong><?= (int)$alat['jumlah'] ?></strong> unit
                            <?php if ($isReady): ?>
                                <span class="status-badge badge-ready" style="margin-left: 6px; font-size: 11px;">Tersedia</span>
                            <?php else: ?>
                                <span class="status-badge badge-busy" style="margin-left: 6px; font-size: 11px;">Habis</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #e0f2fe; color: #0369a1;">
                                <?= htmlspecialchars($alat['kondisi'] ?? 'Baik') ?>
                            </span>
                        </td>
                        <td style="max-width: 250px; font-size: 13px; color: var(--text-muted);"><?= htmlspecialchars($alat['deskripsi'] ?? '-') ?></td>
                        <td class="table-actions-cell">
                            <button type="button" class="btn-action-edit" onclick='editAlat(<?= json_encode($alat) ?>)'>
                                Ubah
                            </button>
                            <a href="index.php?c=admin&a=hapus_alat&id=<?= $alat['id'] ?>" class="btn-action-delete" onclick="return confirm('Yakin ingin menghapus alat ini dari inventaris lab?')">
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
            <h3 class="modal-title">Tambah Alat Laboratorium</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalTambahAlat')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=tambah_alat">
            <div class="form-group">
                <label class="form-label">Kode Alat</label>
                <input type="text" name="kode" class="form-control" placeholder="Contoh: ALT-NET-001" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Alat</label>
                <input type="text" name="nama_alat" class="form-control" placeholder="Contoh: MikroTik RouterBoard RB750r2" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="id_kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($daftarKategori as $kat): ?>
                        <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Jumlah Unit (Stok)</label>
                    <input type="number" name="jumlah" class="form-control" min="0" value="1" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi" class="form-control" required>
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi / Spesifikasi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi spesifikasi alat..."></textarea>
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
            <h3 class="modal-title">Ubah Data Alat Laboratorium</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalUbahAlat')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=ubah_alat">
            <input type="hidden" name="id" id="edit_id">

            <div class="form-group">
                <label class="form-label">Kode Alat</label>
                <input type="text" name="kode" id="edit_kode" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Alat</label>
                <input type="text" name="nama_alat" id="edit_nama_alat" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="id_kategori" id="edit_id_kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($daftarKategori as $kat): ?>
                        <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Jumlah Unit (Stok)</label>
                    <input type="number" name="jumlah" id="edit_jumlah" class="form-control" min="0" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi" id="edit_kondisi" class="form-control" required>
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi / Spesifikasi</label>
                <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalUbahAlat')">Batal</button>
                <button type="submit" class="btn-modal-submit">Perbarui Data</button>
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
function editAlat(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_kode').value = data.kode || '';
    document.getElementById('edit_nama_alat').value = data.nama_alat;
    document.getElementById('edit_id_kategori').value = data.id_kategori;
    document.getElementById('edit_jumlah').value = data.jumlah;
    document.getElementById('edit_kondisi').value = data.kondisi || 'Baik';
    document.getElementById('edit_deskripsi').value = data.deskripsi || '';
    openModal('modalUbahAlat');
}
</script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
