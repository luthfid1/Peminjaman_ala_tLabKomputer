<?php
$activePage = 'daftar_alat';
$pageTitle = 'Daftar Alat Musik - NARA BAND';
require_once 'Views/peminjam_header.php';
?>

<!-- HEADER ROW -->
<div class="admin-header-row">
    <div>
        <span class="admin-date-label">KATALOG & INSTRUMEN</span>
        <h1 class="admin-title">Daftar Alat Musik</h1>
        <p class="admin-subtitle">Pilih instrumen musik profesional terbaik untuk latihan studio, manggung, atau rekaman. Cek tarif harian dan ajukan pinjaman langsung.</p>
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
        <input type="text" name="search" placeholder="Cari nama instrumen atau kategori..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    </form>

    <!-- CATEGORY PILLS -->
    <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
        <a href="index.php?c=peminjam&a=daftar_alat<?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
           style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none; <?= empty($_GET['kategori']) ? 'background: var(--teal-primary); color: white;' : 'background: #f1f5f9; color: #475569;' ?>">
            Semua (<?= count($daftarAlat) ?>)
        </a>
        <?php foreach ($daftarKategori as $kat): ?>
            <?php $isActive = (isset($_GET['kategori']) && (int)$_GET['kategori'] === (int)$kat['id_kategori']); ?>
            <a href="index.php?c=peminjam&a=daftar_alat&kategori=<?= $kat['id_kategori'] ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
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
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Tidak Ada Alat Musik Ditemukan</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin: 0 0 16px 0;">Coba gunakan kata kunci pencarian lain atau pilih kategori yang berbeda.</p>
        <a href="index.php?c=peminjam&a=daftar_alat" class="btn-action-edit" style="display: inline-block; text-decoration: none;">Reset Filter Pencarian</a>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px;">
        <?php foreach ($daftarAlat as $alat): 
            $stok = (int)($alat['jumlah_stok'] ?? 0);
            $tersedia = ($stok > 0);
            $harga = (float)($alat['harga_sewa'] ?? 0);
        ?>
            <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-xl); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 16px -2px rgba(15, 42, 63, 0.03);">
                <!-- HEADER CARD / ICON -->
                <div style="height: 120px; background: linear-gradient(135deg, rgba(20, 184, 166, 0.08), rgba(15, 118, 110, 0.03)); display: flex; align-items: center; justify-content: center; position: relative; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.06); display: flex; align-items: center; justify-content: center; color: var(--teal-dark);">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 18V5l12-2v13"></path>
                            <circle cx="6" cy="18" r="3"></circle>
                            <circle cx="18" cy="16" r="3"></circle>
                        </svg>
                    </div>
                    <span style="position: absolute; top: 12px; right: 12px; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; <?= $tersedia ? 'background: #dcfce7; color: #15803d;' : 'background: #fee2e2; color: #b91c1c;' ?>">
                        <?= $tersedia ? 'Tersedia ' . $stok . ' Unit' : 'Stok Habis' ?>
                    </span>
                </div>

                <!-- BODY CARD -->
                <div style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
                    <span style="font-size: 11px; font-weight: 700; color: var(--teal-dark); text-transform: uppercase; letter-spacing: 0.5px;">
                        <?= htmlspecialchars($alat['nama_kategori'] ?? 'Instrumen') ?>
                    </span>
                    <h3 style="margin: 6px 0 10px 0; font-size: 16px; font-weight: 700; color: #0f172a; line-height: 1.4;">
                        <?= htmlspecialchars($alat['nama_alat']) ?>
                    </h3>

                    <p style="font-size: 12px; color: var(--text-muted); line-height: 1.5; margin: 0 0 14px 0; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= !empty($alat['spesifikasi']) ? htmlspecialchars($alat['spesifikasi']) : 'Instrumen musik kualitas standar studio rekaman dan panggung.' ?>
                    </p>

                    <!-- HARGA SEWA HARIAN -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 14px; margin-bottom: 16px;">
                        <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; display: block; margin-bottom: 2px;">Harga Sewa Harian:</span>
                        <div style="font-size: 18px; font-weight: 800; color: var(--teal-dark);">
                            Rp <?= number_format($harga, 0, ',', '.') ?>
                            <span style="font-size: 12px; font-weight: 500; color: var(--text-muted);">/ hari</span>
                        </div>
                    </div>

                    <!-- ACTION BUTTON -->
                    <?php if ($tersedia): ?>
                        <button type="button" 
                                class="btn-modal-submit" 
                                style="width: 100%; padding: 10px; font-size: 13px; font-weight: 700; text-align: center; border-radius: 8px;"
                                onclick="bukaModalPinjam(<?= (int)$alat['id_alat'] ?>, '<?= htmlspecialchars(addslashes($alat['nama_alat'])) ?>', <?= $harga ?>, <?= $stok ?>)">
                            Ajukan Peminjaman &rarr;
                        </button>
                    <?php else: ?>
                        <button type="button" 
                                disabled
                                style="width: 100%; padding: 10px; font-size: 13px; font-weight: 600; background: #e2e8f0; color: #94a3b8; border: none; border-radius: 8px; cursor: not-allowed;">
                            Stok Tidak Tersedia
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- MODAL FORM PENGAJUAN PEMINJAMAN DENGAN METODE PEMBAYARAN -->
<div class="modal-overlay" id="modalAjukanPinjam">
    <div class="modal-content" style="max-width: 540px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h3 class="modal-title">Formulir Pengajuan Peminjaman</h3>
            <button type="button" class="modal-close" onclick="tutupModalPinjam()">&times;</button>
        </div>

        <form method="POST" action="index.php?c=peminjam&a=ajukan_peminjaman" enctype="multipart/form-data">
            <input type="hidden" name="id_alat" id="form_id_alat">
            <input type="hidden" id="form_harga_per_hari" value="0">

            <!-- INFO ALAT YANG DIPILIH -->
            <div style="padding: 14px 16px; background: #f0fdfa; border-radius: 10px; border: 1px solid #ccfbf1; margin-bottom: 18px;">
                <div style="font-size: 12px; color: #0f766e; font-weight: 500;">Instrumen yang Dipinjam:</div>
                <div style="font-size: 16px; font-weight: 800; color: #115e59; margin-top: 2px;" id="form_nama_alat">-</div>
                <div style="font-size: 13px; color: #0f766e; margin-top: 4px;">
                    Tarif Sewa: <strong id="form_tarif_text">Rp 0 / hari</strong> &bull; Sisa Stok: <strong id="form_stok_text">0 unit</strong>
                </div>
            </div>

            <!-- JUMLAH UNIT & DURASI -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label class="form-label">Jumlah Unit <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="jumlah" id="form_jumlah" class="form-control" value="1" min="1" required onchange="hitungTotalSewa()" oninput="hitungTotalSewa()">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai Pinjam <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_pinjam" id="form_tgl_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required onchange="hitungTotalSewa()">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Rencana Kembali <span style="color:#ef4444;">*</span></label>
                <input type="date" name="tanggal_kembali" id="form_tgl_kembali" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required onchange="hitungTotalSewa()">
            </div>

            <!-- TOTAL BIAYA SEWA (KALKULASI OTOMATIS) -->
            <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px; padding: 14px 16px; margin: 16px 0;">
                <div style="display: flex; justify-content: space-between; font-size: 13px; color: #475569; margin-bottom: 4px;">
                    <span>Durasi Peminjaman:</span>
                    <strong id="kalkulasi_durasi">3 Hari</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: 6px;">
                    <span style="font-size: 14px; font-weight: 700; color: #0f172a;">Total Biaya Sewa:</span>
                    <span style="font-size: 20px; font-weight: 800; color: var(--teal-dark);" id="kalkulasi_total">Rp 0</span>
                </div>
            </div>

            <!-- PILIHAN METODE PEMBAYARAN -->
            <div class="form-group">
                <label class="form-label">Pilih Metode Pembayaran <span style="color:#ef4444;">*</span></label>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 6px;">
                    <!-- OFFLINE: DI TEMPAT -->
                    <label style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; display: flex; flex-direction: column; cursor: pointer; transition: all 0.2s;" id="label_metode_offline">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <input type="radio" name="metode_pembayaran" value="di_tempat" checked onchange="toggleMetodePembayaran(this.value)">
                            <strong style="font-size: 13px; color: #0f172a;">Offline (Di Tempat)</strong>
                        </div>
                        <span style="font-size: 11px; color: var(--text-muted); margin-left: 24px;">Bayar tunai di studio saat mengambil alat</span>
                    </label>

                    <!-- ONLINE: WEBSITE -->
                    <label style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; display: flex; flex-direction: column; cursor: pointer; transition: all 0.2s;" id="label_metode_online">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <input type="radio" name="metode_pembayaran" value="website" onchange="toggleMetodePembayaran(this.value)">
                            <strong style="font-size: 13px; color: #0f172a;">Online (Website)</strong>
                        </div>
                        <span style="font-size: 11px; color: var(--text-muted); margin-left: 24px;">Transfer Bank BCA atau QRIS</span>
                    </label>
                </div>
            </div>

            <!-- INSTRUKSI TRANSFER & UPLOAD BUKTI (JIKA ONLINE) -->
            <div id="section_pembayaran_online" style="display: none; background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 16px; margin-bottom: 18px;">
                <div style="font-size: 12px; font-weight: 700; color: #92400e; margin-bottom: 6px;">Instruksi Pembayaran Transfer:</div>
                <div style="font-size: 13px; color: #78350f; line-height: 1.5; margin-bottom: 12px;">
                    Silakan transfer total biaya sewa ke rekening resmi studio:
                    <div style="background: white; border: 1px solid #fcd34d; border-radius: 8px; padding: 10px 12px; margin-top: 6px;">
                        <div>Bank: <strong>BCA (Bank Central Asia)</strong></div>
                        <div>No. Rekening: <strong style="font-size: 15px; color: #0f172a;">8720-1928-31</strong></div>
                        <div>Atas Nama: <strong>NARA BAND STUDIO</strong></div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 12px; color: #78350f;">Upload Bukti Transfer / Pembayaran (Foto/PDF):</label>
                    <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,.pdf" style="background: white;">
                    <small style="color: #92400e; font-size: 11px; display: block; margin-top: 4px;">
                        *Jika belum sempat mentransfer sekarang, Anda tetap dapat mengupload bukti pembayaran nanti pada menu Peminjaman Saya.
                    </small>
                </div>
            </div>

            <!-- MODAL FOOTER -->
            <div class="modal-footer" style="margin-top: 24px;">
                <button type="button" class="btn-modal-cancel" onclick="tutupModalPinjam()">Batal</button>
                <button type="submit" class="btn-modal-submit">Kirim Pengajuan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalPinjam(idAlat, namaAlat, hargaSewa, stok) {
    document.getElementById('form_id_alat').value = idAlat;
    document.getElementById('form_harga_per_hari').value = hargaSewa;
    document.getElementById('form_nama_alat').textContent = namaAlat;
    document.getElementById('form_tarif_text').textContent = 'Rp ' + Number(hargaSewa).toLocaleString('id-ID') + ' / hari';
    document.getElementById('form_stok_text').textContent = stok + ' unit';
    
    const inputJumlah = document.getElementById('form_jumlah');
    inputJumlah.max = stok;
    inputJumlah.value = 1;

    hitungTotalSewa();
    document.getElementById('modalAjukanPinjam').classList.add('active');
}

function tutupModalPinjam() {
    document.getElementById('modalAjukanPinjam').classList.remove('active');
}

function hitungTotalSewa() {
    const harga = parseFloat(document.getElementById('form_harga_per_hari').value) || 0;
    const jumlah = parseInt(document.getElementById('form_jumlah').value) || 1;
    const tglPinjam = document.getElementById('form_tgl_pinjam').value;
    const tglKembali = document.getElementById('form_tgl_kembali').value;

    let hari = 1;
    if (tglPinjam && tglKembali) {
        const d1 = new Date(tglPinjam);
        const d2 = new Date(tglKembali);
        const diffTime = d2.getTime() - d1.getTime();
        hari = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (hari <= 0) hari = 1;
    }

    const total = harga * jumlah * hari;
    document.getElementById('kalkulasi_durasi').textContent = hari + ' Hari (' + jumlah + ' unit)';
    document.getElementById('kalkulasi_total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function toggleMetodePembayaran(metode) {
    const sectionOnline = document.getElementById('section_pembayaran_online');
    const labelOffline = document.getElementById('label_metode_offline');
    const labelOnline = document.getElementById('label_metode_online');

    if (metode === 'website') {
        sectionOnline.style.display = 'block';
        labelOnline.style.borderColor = 'var(--teal-primary)';
        labelOnline.style.background = '#f0fdfa';
        labelOffline.style.borderColor = '#e2e8f0';
        labelOffline.style.background = 'transparent';
    } else {
        sectionOnline.style.display = 'none';
        labelOffline.style.borderColor = 'var(--teal-primary)';
        labelOffline.style.background = '#f0fdfa';
        labelOnline.style.borderColor = '#e2e8f0';
        labelOnline.style.background = 'transparent';
    }
}

// Inisialisasi highlight radio default
toggleMetodePembayaran('di_tempat');
</script>

<?php require_once 'Views/peminjam_footer.php'; ?>
