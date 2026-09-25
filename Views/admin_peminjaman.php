<?php
$activePage = 'peminjaman';
$pageTitle = 'Kelola Peminjaman Alat - Pengelola Lab';
require_once __DIR__ . '/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">SIRKULASI LABORATORIUM</span>
        <h1 class="admin-title">Kelola Data Peminjaman</h1>
        <p class="admin-subtitle">Catat peminjaman langsung di lab, kelola permohonan peminjaman siswa, dan pantau tenggat pengembalian.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="openModal('modalTambahPeminjaman')">
        + Catat Peminjaman Lab
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
            <h3 class="inventory-title">Daftar Peminjaman Alat Lab</h3>
            <p class="inventory-sub">Total <?= count($daftarPeminjaman) ?> catatan peminjaman dalam sistem.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="peminjaman">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari kode, nama siswa, alat..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Siswa</th>
                <th>Alat Lab</th>
                <th>Jml</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Keperluan</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPeminjaman)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada transaksi peminjaman alat lab. Klik "+ Catat Peminjaman Lab" untuk membuat baru.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPeminjaman as $p): 
                    $status = strtolower($p['status']);
                    $statusClass = 'badge-busy';
                    $statusText = ucfirst($status);

                    if ($status === 'disetujui' || $status === 'dipinjam') {
                        $statusClass = 'badge-ready';
                    } elseif ($status === 'dikembalikan') {
                        $statusClass = 'badge-ready';
                        $statusText = 'Selesai';
                    } elseif ($status === 'ditolak') {
                        $statusClass = 'badge-busy';
                    }
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--primary-teal);"><?= htmlspecialchars($p['kode_peminjaman']) ?></div>
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($p['nama_peminjam'] ?? 'Siswa') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted);">NIS: <?= htmlspecialchars($p['nis'] ?? '-') ?> &bull; <?= htmlspecialchars($p['kelas'] ?? '') ?></div>
                        </td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($p['nama_alat'] ?? 'Alat Lab') ?>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($p['nama_kategori'] ?? '') ?></div>
                        </td>
                        <td><strong><?= (int)$p['jumlah'] ?></strong> unit</td>
                        <td><?= date('d M Y', strtotime($p['tanggal_pinjam'])) ?></td>
                        <td><strong style="color: #0369a1;"><?= date('d M Y', strtotime($p['tanggal_rencana_kembali'])) ?></strong></td>
                        <td style="max-width: 180px; font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($p['keperluan'] ?? '-') ?></td>
                        <td>
                            <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                        </td>
                        <td class="table-actions-cell">
                            <button type="button" class="btn-action-edit" onclick='editPeminjaman(<?= json_encode($p) ?>)'>
                                Ubah
                            </button>
                            <a href="index.php?c=admin&a=hapus_peminjaman&id=<?= $p['id'] ?>" class="btn-action-delete" onclick="return confirm('Yakin ingin menghapus data peminjaman ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- MODAL TAMBAH PEMINJAMAN -->
<div class="modal-overlay" id="modalTambahPeminjaman">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Catat Peminjaman Alat Lab</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalTambahPeminjaman')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=tambah_peminjaman">
            <div class="form-group">
                <label class="form-label">Peminjam (Siswa)</label>
                <select name="id_peminjam" class="form-control" required>
                    <option value="">-- Pilih Siswa / Peminjam --</option>
                    <?php foreach ($daftarPeminjam as $pm): ?>
                        <option value="<?= $pm['id'] ?>"><?= htmlspecialchars($pm['nama']) ?> (NIS: <?= htmlspecialchars($pm['nis']) ?> - <?= htmlspecialchars($pm['kelas']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Alat Laboratorium</label>
                <select name="id_alat" class="form-control" required>
                    <option value="">-- Pilih Alat Lab --</option>
                    <?php foreach ($daftarAlat as $al): ?>
                        <option value="<?= $al['id'] ?>"><?= htmlspecialchars($al['nama_alat']) ?> (Stok: <?= (int)$al['jumlah'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Jumlah Pinjam</label>
                    <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Jenis Peminjaman</label>
                    <select name="jenis_peminjaman" class="form-control" required>
                        <option value="Praktek Lab">Praktek Lab</option>
                        <option value="Tugas Akhir / UKK">Tugas Akhir / UKK</option>
                        <option value="Kegiatan Ekstrakurikuler">Kegiatan Ekstrakurikuler</option>
                        <option value="Lomba Kejuruan (LKS)">Lomba Kejuruan (LKS)</option>
                    </select>
                </div>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Tanggal Rencana Kembali</label>
                    <input type="date" name="tanggal_rencana_kembali" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keperluan Peminjaman</label>
                <textarea name="keperluan" class="form-control" rows="2" placeholder="Contoh: Praktikum konfigurasi router mikroTik"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Status Peminjaman</label>
                <select name="status" class="form-control" required>
                    <option value="disetujui">Disetujui (Langsung Aktif)</option>
                    <option value="dipinjam">Dipinjam (Sedang Digunakan)</option>
                    <option value="menunggu">Menunggu Persetujuan</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalTambahPeminjaman')">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL UBAH PEMINJAMAN -->
<div class="modal-overlay" id="modalUbahPeminjaman">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ubah Data Peminjaman</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalUbahPeminjaman')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=ubah_peminjaman">
            <input type="hidden" name="id" id="edit_pmj_id">

            <div class="form-group">
                <label class="form-label">Peminjam (Siswa)</label>
                <select name="id_peminjam" id="edit_pmj_id_peminjam" class="form-control" required>
                    <?php foreach ($daftarPeminjam as $pm): ?>
                        <option value="<?= $pm['id'] ?>"><?= htmlspecialchars($pm['nama']) ?> (NIS: <?= htmlspecialchars($pm['nis']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Alat Laboratorium</label>
                <select name="id_alat" id="edit_pmj_id_alat" class="form-control" required>
                    <?php foreach ($daftarAlat as $al): ?>
                        <option value="<?= $al['id'] ?>"><?= htmlspecialchars($al['nama_alat']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Jumlah Pinjam</label>
                    <input type="number" name="jumlah" id="edit_pmj_jumlah" class="form-control" min="1" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Jenis Peminjaman</label>
                    <select name="jenis_peminjaman" id="edit_pmj_jenis" class="form-control" required>
                        <option value="Praktek Lab">Praktek Lab</option>
                        <option value="Tugas Akhir / UKK">Tugas Akhir / UKK</option>
                        <option value="Kegiatan Ekstrakurikuler">Kegiatan Ekstrakurikuler</option>
                        <option value="Lomba Kejuruan (LKS)">Lomba Kejuruan (LKS)</option>
                    </select>
                </div>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="edit_pmj_tgl_pinjam" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Tanggal Rencana Kembali</label>
                    <input type="date" name="tanggal_rencana_kembali" id="edit_pmj_tgl_rencana" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keperluan Peminjaman</label>
                <textarea name="keperluan" id="edit_pmj_keperluan" class="form-control" rows="2"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Status Peminjaman</label>
                <select name="status" id="edit_pmj_status" class="form-control" required>
                    <option value="menunggu">Menunggu Persetujuan</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="dikembalikan">Dikembalikan</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalUbahPeminjaman')">Batal</button>
                <button type="submit" class="btn-modal-submit">Perbarui Peminjaman</button>
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
function editPeminjaman(data) {
    document.getElementById('edit_pmj_id').value = data.id;
    document.getElementById('edit_pmj_id_peminjam').value = data.id_peminjam;
    document.getElementById('edit_pmj_id_alat').value = data.id_alat || '';
    document.getElementById('edit_pmj_jumlah').value = data.jumlah || 1;
    document.getElementById('edit_pmj_jenis').value = data.jenis_peminjaman || 'Praktek Lab';
    document.getElementById('edit_pmj_tgl_pinjam').value = data.tanggal_pinjam;
    document.getElementById('edit_pmj_tgl_rencana').value = data.tanggal_rencana_kembali;
    document.getElementById('edit_pmj_keperluan').value = data.keperluan || '';
    document.getElementById('edit_pmj_status').value = data.status || 'menunggu';
    openModal('modalUbahPeminjaman');
}
</script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
