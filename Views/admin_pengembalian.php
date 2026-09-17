<?php
$activePage = 'pengembalian';
$pageTitle = 'Kelola Pengembalian Alat - SEWANADA';
require_once 'Views/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">TRANSAKSI & SIRKULASI</span>
        <h1 class="admin-title">Kelola Pengembalian</h1>
        <p class="admin-subtitle">Catat pengembalian alat musik (offline & web), cek denda keterlambatan/kerusakan, dan pulihkan stok inventaris.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="openModal('modalTambahPengembalian')">
        + Catat Pengembalian
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
            <h3 class="inventory-title">Riwayat Pengembalian Alat</h3>
            <p class="inventory-sub">Total <?= count($daftarPengembalian) ?> transaksi pengembalian tercatat.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="pengembalian">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari peminjam, alat, atau catatan..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam & Kontak</th>
                <th>Alat Musik</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Dikembalikan</th>
                <th>Denda</th>
                <th>Keterangan / Kondisi</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPengembalian)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada riwayat pengembalian alat. Klik "+ Catat Pengembalian" untuk memproses pengembalian alat yang sedang dipinjam.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPengembalian as $pg): 
                    $hasDenda = ($pg['denda'] > 0);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($pg['nama_lengkap'] ?? 'User') ?>
                            <div style="font-size: 12px; font-weight: 400; color: var(--text-muted);">
                                @<?= htmlspecialchars($pg['username'] ?? '-') ?>
                                <?php if (!empty($pg['no_hp'])): ?>
                                    &bull; 📞 <?= htmlspecialchars($pg['no_hp']) ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($pg['nama_alat'] ?? 'Instrumen') ?>
                            <div style="font-size: 12px; font-weight: 400; color: var(--text-muted);">
                                <?= htmlspecialchars($pg['nama_kategori'] ?? 'Kategori') ?> &bull; <?= (int)($pg['jumlah'] ?? 1) ?> unit
                            </div>
                        </td>
                        <td><?= !empty($pg['tanggal_pinjam']) ? date('d M Y', strtotime($pg['tanggal_pinjam'])) : '-' ?></td>
                        <td>
                            <strong style="color: var(--teal-dark);"><?= !empty($pg['tanggal_pengembalian']) ? date('d M Y', strtotime($pg['tanggal_pengembalian'])) : '-' ?></strong>
                            <?php if (!empty($pg['tanggal_kembali'])): ?>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                    Tenggat: <?= date('d M Y', strtotime($pg['tanggal_kembali'])) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($hasDenda): ?>
                                <span style="display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 700; background: #fee2e2; color: #b91c1c;">
                                    Rp <?= number_format($pg['denda'], 0, ',', '.') ?>
                                </span>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 13px;">Rp 0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= !empty($pg['keterangan']) ? htmlspecialchars($pg['keterangan']) : '<span style="color: var(--text-muted);">-</span>' ?>
                        </td>
                        <td class="table-actions-cell">
                            <button type="button" class="btn-action-edit" onclick='editPengembalian(<?= json_encode($pg) ?>)'>
                                Ubah
                            </button>
                            <a href="index.php?c=admin&a=hapus_pengembalian&id=<?= $pg['id_pengembalian'] ?>" class="btn-action-delete" onclick="return confirm('Yakin ingin menghapus catatan pengembalian ini? Status peminjaman akan dikembalikan menjadi dipinjam.')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- MODAL TAMBAH PENGEMBALIAN -->
<div class="modal-overlay" id="modalTambahPengembalian">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Catat Pengembalian Alat</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Denda keterlambatan (<strong>Rp 10.000 / hari / unit</strong>) dihitung otomatis oleh sistem.</p>
            </div>
            <button type="button" class="modal-close" onclick="closeModal('modalTambahPengembalian')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=tambah_pengembalian">
            <div class="form-group">
                <label class="form-label">Pilih Transaksi Peminjaman Aktif <span style="color:#ef4444;">*</span></label>
                <select name="id_peminjaman" id="tambah_id_peminjaman" class="form-control" required onchange="hitungDendaTambah()">
                    <option value="">-- Pilih Transaksi yang Sedang Dipinjam --</option>
                    <?php if (empty($peminjamanAktif)): ?>
                        <option value="" disabled>Tidak ada barang yang sedang dipinjam saat ini</option>
                    <?php else: ?>
                        <?php foreach ($peminjamanAktif as $pa): ?>
                            <option value="<?= $pa['id_peminjaman'] ?>" 
                                    data-kembali="<?= $pa['tanggal_kembali'] ?>"
                                    data-jumlah="<?= (int)($pa['jumlah'] ?? 1) ?>"
                                    data-alat="<?= htmlspecialchars($pa['nama_alat']) ?>"
                                    data-peminjam="<?= htmlspecialchars($pa['nama_lengkap']) ?>">
                                [ID #<?= $pa['id_peminjaman'] ?>] <?= htmlspecialchars($pa['nama_lengkap']) ?> - <?= htmlspecialchars($pa['nama_alat']) ?> (<?= (int)$pa['jumlah'] ?> unit &bull; Tenggat: <?= date('d M Y', strtotime($pa['tanggal_kembali'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Tanggal Pengembalian Aktual <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_pengembalian" id="tambah_tgl_pengembalian" class="form-control" value="<?= date('Y-m-d') ?>" required onchange="hitungDendaTambah()">
                </div>

                <div class="form-group">
                    <label class="form-label">Denda Kerusakan / Tambahan (Rp)</label>
                    <input type="number" name="denda_tambahan" id="tambah_denda_tambahan" class="form-control" value="0" min="0" placeholder="0" oninput="hitungDendaTambah()">
                    <small style="color: var(--text-muted); font-size: 11px;">Jika alat lecet/rusak (di luar telat).</small>
                </div>
            </div>

            <!-- STATUS & KALKULASI OTOMATIS -->
            <div id="tambah_kalkulasi_box" style="display: none; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 13px; font-weight: 600; color: #334155;">Status Pengembalian:</span>
                    <span id="tambah_status_badge" style="font-size: 12px; font-weight: 700; padding: 3px 8px; border-radius: 4px;"></span>
                </div>
                <div style="font-size: 12px; color: #64748b; line-height: 1.6;">
                    <div>Tenggat Kembali: <strong id="tambah_info_tenggat" style="color:#0f172a;">-</strong></div>
                    <div>Denda Keterlambatan: <strong id="tambah_info_denda_telat" style="color:#0f172a;">Rp 0</strong> <span id="tambah_rincian_telat" style="font-size: 11px; color:#64748b;"></span></div>
                    <div style="margin-top: 6px; padding-top: 6px; border-top: 1px dashed #cbd5e1; font-size: 14px; font-weight: 700; color: #0f172a; display: flex; justify-content: space-between;">
                        <span>Total Denda Final:</span>
                        <span id="tambah_total_denda_text" style="color: #b91c1c;">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan / Kondisi Alat</label>
                <textarea name="keterangan" id="tambah_keterangan" class="form-control" rows="2" placeholder="Contoh: Alat dikembalikan dalam kondisi lengkap dan berfungsi baik."></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalTambahPengembalian')">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL UBAH PENGEMBALIAN -->
<div class="modal-overlay" id="modalUbahPengembalian">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Ubah Data Pengembalian</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Koreksi tanggal pengembalian, denda, atau kondisi alat.</p>
            </div>
            <button type="button" class="modal-close" onclick="closeModal('modalUbahPengembalian')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=ubah_pengembalian">
            <input type="hidden" name="id_pengembalian" id="edit_pg_id">
            <input type="hidden" id="edit_pg_tgl_kembali_asli">
            <input type="hidden" id="edit_pg_jumlah_asli">

            <div class="form-group">
                <label class="form-label">Peminjam & Alat</label>
                <input type="text" id="edit_pg_info" class="form-control" readonly style="background: #f1f5f9; color: var(--text-muted); font-weight: 600;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Tanggal Pengembalian Aktual <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_pengembalian" id="edit_pg_tanggal" class="form-control" required onchange="hitungDendaEdit()">
                </div>

                <div class="form-group">
                    <label class="form-label">Denda Kerusakan / Lainnya (Rp)</label>
                    <input type="number" name="denda_tambahan" id="edit_pg_denda_tambahan" class="form-control" min="0" value="0" oninput="hitungDendaEdit()">
                </div>
            </div>

            <!-- STATUS & KALKULASI OTOMATIS EDIT -->
            <div id="edit_kalkulasi_box" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 13px; font-weight: 600; color: #334155;">Status Pengembalian:</span>
                    <span id="edit_status_badge" style="font-size: 12px; font-weight: 700; padding: 3px 8px; border-radius: 4px;"></span>
                </div>
                <div style="font-size: 12px; color: #64748b; line-height: 1.6;">
                    <div>Tenggat Kembali: <strong id="edit_info_tenggat" style="color:#0f172a;">-</strong></div>
                    <div>Denda Keterlambatan: <strong id="edit_info_denda_telat" style="color:#0f172a;">Rp 0</strong> <span id="edit_rincian_telat" style="font-size: 11px; color:#64748b;"></span></div>
                    <div style="margin-top: 6px; padding-top: 6px; border-top: 1px dashed #cbd5e1; font-size: 14px; font-weight: 700; color: #0f172a; display: flex; justify-content: space-between;">
                        <span>Total Denda:</span>
                        <span id="edit_total_denda_text" style="color: #b91c1c;">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan / Kondisi Alat</label>
                <textarea name="keterangan" id="edit_pg_keterangan" class="form-control" rows="2"></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalUbahPengembalian')">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
const TARIF_DENDA_PER_HARI = 10000;

function openModal(id) {
    document.getElementById(id).classList.add('active');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function formatRupiah(number) {
    return 'Rp ' + Number(number).toLocaleString('id-ID');
}

function hitungDendaTambah() {
    const select = document.getElementById('tambah_id_peminjaman');
    const box = document.getElementById('tambah_kalkulasi_box');
    const selectedOption = select.options[select.selectedIndex];
    
    if (!selectedOption || !selectedOption.value) {
        box.style.display = 'none';
        return;
    }

    const tglKembaliStr = selectedOption.getAttribute('data-kembali');
    const jumlah = parseInt(selectedOption.getAttribute('data-jumlah') || 1);
    const tglPengembalianStr = document.getElementById('tambah_tgl_pengembalian').value;
    const dendaTambahan = parseFloat(document.getElementById('tambah_denda_tambahan').value || 0);

    if (!tglKembaliStr || !tglPengembalianStr) {
        box.style.display = 'none';
        return;
    }

    box.style.display = 'block';
    
    const tglKembali = new Date(tglKembaliStr + 'T00:00:00');
    const tglPengembalian = new Date(tglPengembalianStr + 'T00:00:00');
    
    // Format tanggal tenggat
    const optionsDate = { day: '2-digit', month: 'short', year: 'numeric' };
    document.getElementById('tambah_info_tenggat').innerText = tglKembali.toLocaleDateString('id-ID', optionsDate);

    const diffTime = tglPengembalian.getTime() - tglKembali.getTime();
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

    const badge = document.getElementById('tambah_status_badge');
    const infoTelat = document.getElementById('tambah_info_denda_telat');
    const rincianTelat = document.getElementById('tambah_rincian_telat');
    const totalDendaText = document.getElementById('tambah_total_denda_text');

    let dendaKeterlambatan = 0;

    if (diffDays > 0) {
        dendaKeterlambatan = diffDays * TARIF_DENDA_PER_HARI * jumlah;
        badge.style.background = '#fee2e2';
        badge.style.color = '#b91c1c';
        badge.innerText = 'TERLAMBAT ' + diffDays + ' HARI';
        rincianTelat.innerText = '(' + diffDays + ' hari × ' + formatRupiah(TARIF_DENDA_PER_HARI) + ' × ' + jumlah + ' unit)';
    } else {
        badge.style.background = '#dcfce7';
        badge.style.color = '#15803d';
        badge.innerText = 'TEPAT WAKTU';
        rincianTelat.innerText = '(Tidak ada keterlambatan)';
    }

    infoTelat.innerText = formatRupiah(dendaKeterlambatan);
    const totalDenda = dendaKeterlambatan + Math.max(0, dendaTambahan);
    totalDendaText.innerText = formatRupiah(totalDenda);
}

function editPengembalian(data) {
    document.getElementById('edit_pg_id').value = data.id_pengembalian;
    document.getElementById('edit_pg_info').value = (data.nama_lengkap || 'Peminjam') + ' - ' + (data.nama_alat || 'Alat') + ' (' + (data.jumlah || 1) + ' unit)';
    document.getElementById('edit_pg_tanggal').value = data.tanggal_pengembalian;
    document.getElementById('edit_pg_tgl_kembali_asli').value = data.tanggal_kembali || '';
    document.getElementById('edit_pg_jumlah_asli').value = data.jumlah || 1;
    document.getElementById('edit_pg_denda_tambahan').value = 0;
    document.getElementById('edit_pg_keterangan').value = data.keterangan || '';
    
    hitungDendaEdit();
    openModal('modalUbahPengembalian');
}

function hitungDendaEdit() {
    const tglKembaliStr = document.getElementById('edit_pg_tgl_kembali_asli').value;
    const jumlah = parseInt(document.getElementById('edit_pg_jumlah_asli').value || 1);
    const tglPengembalianStr = document.getElementById('edit_pg_tanggal').value;
    const dendaTambahan = parseFloat(document.getElementById('edit_pg_denda_tambahan').value || 0);

    if (!tglKembaliStr || !tglPengembalianStr) return;

    const tglKembali = new Date(tglKembaliStr + 'T00:00:00');
    const tglPengembalian = new Date(tglPengembalianStr + 'T00:00:00');

    const optionsDate = { day: '2-digit', month: 'short', year: 'numeric' };
    document.getElementById('edit_info_tenggat').innerText = tglKembali.toLocaleDateString('id-ID', optionsDate);

    const diffTime = tglPengembalian.getTime() - tglKembali.getTime();
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

    const badge = document.getElementById('edit_status_badge');
    const infoTelat = document.getElementById('edit_info_denda_telat');
    const rincianTelat = document.getElementById('edit_rincian_telat');
    const totalDendaText = document.getElementById('edit_total_denda_text');

    let dendaKeterlambatan = 0;

    if (diffDays > 0) {
        dendaKeterlambatan = diffDays * TARIF_DENDA_PER_HARI * jumlah;
        badge.style.background = '#fee2e2';
        badge.style.color = '#b91c1c';
        badge.innerText = 'TERLAMBAT ' + diffDays + ' HARI';
        rincianTelat.innerText = '(' + diffDays + ' hari × ' + formatRupiah(TARIF_DENDA_PER_HARI) + ' × ' + jumlah + ' unit)';
    } else {
        badge.style.background = '#dcfce7';
        badge.style.color = '#15803d';
        badge.innerText = 'TEPAT WAKTU';
        rincianTelat.innerText = '(Tidak ada keterlambatan)';
    }

    infoTelat.innerText = formatRupiah(dendaKeterlambatan);
    const totalDenda = dendaKeterlambatan + Math.max(0, dendaTambahan);
    totalDendaText.innerText = formatRupiah(totalDenda);
}
</script>

<?php require_once 'Views/admin_footer.php'; ?>
