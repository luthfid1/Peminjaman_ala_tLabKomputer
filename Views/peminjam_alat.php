<?php
$activePage = 'daftar_alat';
$pageTitle = 'Katalog Alat Lab Komputer - Ruang Siswa';
require_once __DIR__ . '/peminjam_header.php';
?>

<!-- HEADER ROW -->
<div class="admin-header-row">
    <div>
        <span class="admin-date-label">KATALOG LABORATORIUM KOMPUTER</span>
        <h1 class="admin-title">Katalog Alat Lab</h1>
        <p class="admin-subtitle">Pilih perangkat jaringan, workstation PC, atau peralatan komputer untuk praktikum sekolah dan ajukan peminjaman secara langsung.</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="index.php?c=peminjam&a=peminjaman" class="btn-action-edit" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; font-size: 13px; font-weight: 600; text-decoration: none; border-radius: 8px;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
            Peminjaman Saya
        </a>
    </div>
</div>

<!-- SEARCH & CATEGORY FILTER -->
<div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 18px 24px; margin-bottom: 28px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
    <!-- SEARCH FORM -->
    <form method="GET" action="index.php" class="search-box" style="margin: 0; min-width: 280px; flex: 1; max-width: 450px;">
        <input type="hidden" name="c" value="peminjam">
        <input type="hidden" name="a" value="daftar_alat">
        <?php if (!empty($_GET['kategori'])): ?>
            <input type="hidden" name="kategori" value="<?= (int)$_GET['kategori'] ?>">
        <?php endif; ?>
        <span class="search-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </span>
        <input type="text" name="search" placeholder="Cari nama alat lab atau kode..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    </form>

    <!-- CATEGORY PILLS -->
    <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
        <a href="index.php?c=peminjam&a=daftar_alat<?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
           style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none; <?= empty($_GET['kategori']) ? 'background: var(--teal-primary); color: white;' : 'background: #f1f5f9; color: #475569;' ?>">
            Semua (<?= count($daftarAlat) ?>)
        </a>
        <?php foreach ($daftarKategori as $kat): ?>
            <?php $isActive = (isset($_GET['kategori']) && (int)$_GET['kategori'] === (int)$kat['id']); ?>
            <a href="index.php?c=peminjam&a=daftar_alat&kategori=<?= $kat['id'] ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
               style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none; <?= $isActive ? 'background: var(--teal-primary); color: white;' : 'background: #f1f5f9; color: #475569;' ?>">
                <?= htmlspecialchars($kat['nama_kategori']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- INSTRUMENTS GRID -->
<?php if (empty($daftarAlat)): ?>
    <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 60px 20px; text-align: center;">
        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 12px auto; color: #94a3b8;">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Tidak Ada Alat Lab Ditemukan</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin: 0 0 16px 0;">Coba gunakan kata kunci pencarian lain atau pilih kategori yang berbeda.</p>
        <a href="index.php?c=peminjam&a=daftar_alat" class="btn-action-edit" style="display: inline-block; text-decoration: none;">Reset Filter</a>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px;">
        <?php foreach ($daftarAlat as $alat): 
            $stok = (int)($alat['jumlah'] ?? 0);
            $tersedia = ($stok > 0);
        ?>
            <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-xl); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 16px -2px rgba(15, 42, 63, 0.03);">
                <!-- HEADER CARD / ICON -->
                <div style="height: 110px; background: linear-gradient(135deg, rgba(20, 184, 166, 0.08), rgba(15, 118, 110, 0.03)); display: flex; align-items: center; justify-content: center; position: relative; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 52px; height: 52px; border-radius: 50%; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.06); display: flex; align-items: center; justify-content: center; color: var(--teal-dark);">
                        <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    </div>
                    <span style="position: absolute; top: 12px; right: 12px; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; <?= $tersedia ? 'background: #dcfce7; color: #15803d;' : 'background: #fee2e2; color: #b91c1c;' ?>">
                        <?= $tersedia ? 'Tersedia ' . $stok . ' Unit' : 'Stok Habis' ?>
                    </span>
                    <span style="position: absolute; top: 12px; left: 12px; font-family: monospace; font-size: 11px; font-weight: 700; background: #e2e8f0; color: #334155; padding: 2px 6px; border-radius: 4px;">
                        <?= htmlspecialchars($alat['kode'] ?? '-') ?>
                    </span>
                </div>

                <!-- BODY CARD -->
                <div style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
                    <span style="font-size: 11px; font-weight: 700; color: var(--teal-dark); text-transform: uppercase; letter-spacing: 0.5px;">
                        <?= htmlspecialchars($alat['nama_kategori'] ?? 'Umum') ?>
                    </span>
                    <h3 style="margin: 6px 0 8px 0; font-size: 16px; font-weight: 700; color: #0f172a; line-height: 1.4;">
                        <?= htmlspecialchars($alat['nama_alat']) ?>
                    </h3>
                    <p style="font-size: 12px; color: var(--text-muted); line-height: 1.5; margin: 0 0 16px 0; flex-grow: 1;">
                        <?= htmlspecialchars($alat['deskripsi'] ?? 'Perangkat laboratorium komputer sekolah.') ?>
                    </p>

                    <div style="background: #f8fafc; border-radius: 8px; padding: 10px 12px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                        <span style="color: var(--text-muted);">Kondisi:</span>
                        <strong style="color: #0369a1;"><?= htmlspecialchars($alat['kondisi'] ?? 'Baik') ?></strong>
                    </div>

                    <?php if ($tersedia): ?>
                        <button type="button" class="btn-add-instrument" style="width: 100%; text-align: center; justify-content: center; padding: 10px;" 
                                onclick='openPinjamModal(<?= json_encode($alat) ?>)'>
                            Pinjam Alat Lab
                        </button>
                    <?php else: ?>
                        <button type="button" disabled style="width: 100%; padding: 10px; background: #e2e8f0; color: #94a3b8; border: none; border-radius: 8px; font-weight: 600; cursor: not-allowed;">
                            Tidak Tersedia
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- MODAL FORM PINJAM ALAT -->
<div class="modal-overlay" id="modalPinjamAlat">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Formulir Peminjaman Alat Lab</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalPinjamAlat')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=peminjam&a=ajukan_peminjaman">
            <input type="hidden" name="id_alat" id="pinjam_id_alat">

            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 18px;">
                <div style="font-size: 11px; font-weight: 700; color: #166534; text-transform: uppercase;">Perangkat Dipilih:</div>
                <strong style="font-size: 15px; color: #14532d;" id="pinjam_nama_alat">-</strong>
                <div style="font-size: 12px; color: #166534; margin-top: 2px;" id="pinjam_info_stok">Stok: -</div>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Waktu Mulai Pinjam (WIB)</label>
                    <input type="time" name="waktu_pinjam" class="form-control" value="<?= date('H:i') ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Rencana Pengembalian (WIB)</label>
                    <input type="time" name="waktu_rencana_kembali" class="form-control" value="<?= date('H:i', strtotime('+2 hours')) ?>" required>
                </div>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Jumlah Unit</label>
                    <input type="number" name="jumlah" id="pinjam_jumlah" class="form-control" min="1" max="1" value="1" required>
                </div>
                <div class="form-group" style="flex: 2;">
                    <label class="form-label">Jenis Peminjaman</label>
                    <select name="jenis_peminjaman" class="form-control" required>
                        <option value="Praktek Lab">Praktek Jam Pelajaran Lab</option>
                        <option value="Tugas Projek">Pengerjaan Tugas / Projek Sekolah</option>
                        <option value="Ujian Praktik">Ujian Praktik Kompetensi (UKK)</option>
                        <option value="Kegiatan Ekskul">Kegiatan Ekstrakurikuler Komputer</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keperluan / Keterangan</label>
                <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Praktikum konfigurasi routing jaringan MikroTik di Lab RPL 2" required></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalPinjamAlat')">Batal</button>
                <button type="submit" class="btn-modal-submit">Kirim Pengajuan</button>
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
function openPinjamModal(alat) {
    document.getElementById('pinjam_id_alat').value = alat.id;
    document.getElementById('pinjam_nama_alat').textContent = alat.nama_alat + ' (' + (alat.kode || '') + ')';
    document.getElementById('pinjam_info_stok').textContent = 'Stok tersedia di laboratorium: ' + alat.jumlah + ' unit';
    
    var jumlahInput = document.getElementById('pinjam_jumlah');
    jumlahInput.max = alat.jumlah;
    jumlahInput.value = 1;

    openModal('modalPinjamAlat');
}
</script>

<?php require_once __DIR__ . '/peminjam_footer.php'; ?>
