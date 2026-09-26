<?php
$activePage = 'dashboard';
$pageTitle = 'Dashboard Petugas Laboran - Lab Komputer';
require_once __DIR__ . '/petugas_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label"><?= htmlspecialchars($tglStr) ?></span>
        <h1 class="admin-title">Dashboard Petugas Laboran</h1>
        <p class="admin-subtitle">Akses operasional laboran untuk menyetujui peminjaman, memantau pengembalian alat, dan mencetak laporan sirkulasi.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="index.php?c=petugas&a=laporan" class="btn-action-edit" style="text-decoration: none; padding: 10px 16px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            🖨 Cetak Laporan
        </a>
        <a href="index.php?c=petugas&a=pengembalian" class="btn-add-instrument">
            + Catat Pengembalian
        </a>
    </div>
</div>

<!-- 3 KARTU USE CASE PETUGAS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 28px;">
    <!-- KARTU 1: MENYETUJUI PEMINJAMAN -->
    <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 22px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: var(--shadow-sm); border-left: 4px solid #f59e0b;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--primary-navy);">Menyetujui Peminjaman</h3>
                <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(245, 158, 11, 0.12); color: #d97706; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
            <p style="margin: 0 0 16px 0; font-size: 12px; color: var(--text-muted);">Verifikasi dan beri persetujuan permohonan pinjam alat lab dari siswa.</p>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 14px;">
            <div>
                <span style="font-size: 26px; font-weight: 800; color: #d97706;"><?= $totalMenunggu ?></span>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Permohonan menunggu</span>
            </div>
            <a href="index.php?c=petugas&a=peminjaman" class="btn-action-edit" style="font-size: 12px; text-decoration: none; padding: 6px 12px;">
                Proses Sekarang &rarr;
            </a>
        </div>
    </div>

    <!-- KARTU 2: MEMANTAU PENGEMBALIAN -->
    <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 22px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: var(--shadow-sm); border-left: 4px solid var(--primary-teal);">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--primary-navy);">Memantau Pengembalian</h3>
                <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(20, 184, 166, 0.12); color: var(--teal-dark); display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="1 4 1 10 7 10"></polyline>
                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                    </svg>
                </div>
            </div>
            <p style="margin: 0 0 16px 0; font-size: 12px; color: var(--text-muted);">Pantau tenggat waktu, catat pengembalian alat, denda, dan verifikasi kondisi fisik.</p>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 14px;">
            <div>
                <span style="font-size: 26px; font-weight: 800; color: var(--primary-navy);"><?= $totalAktif ?></span>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Perangkat aktif di luar lab</span>
            </div>
            <a href="index.php?c=petugas&a=pengembalian" class="btn-action-edit" style="font-size: 12px; text-decoration: none; padding: 6px 12px;">
                Pantau & Catat &rarr;
            </a>
        </div>
    </div>

    <!-- KARTU 3: MENCETAK LAPORAN -->
    <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 22px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: var(--shadow-sm); border-left: 4px solid #0284c7;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--primary-navy);">Mencetak Laporan</h3>
                <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(2, 132, 199, 0.12); color: #0284c7; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
            </div>
            <p style="margin: 0 0 16px 0; font-size: 12px; color: var(--text-muted);">Cetak arsip fisik berkas sirkulasi peminjaman & pengembalian laboratorium.</p>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 14px;">
            <div>
                <span style="font-size: 26px; font-weight: 800; color: #0284c7;"><?= $totalPengembalian ?></span>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Total arsip transaksi selesai</span>
            </div>
            <a href="index.php?c=petugas&a=laporan" class="btn-action-edit" style="font-size: 12px; text-decoration: none; padding: 6px 12px;">
                Buka & Cetak &rarr;
            </a>
        </div>
    </div>
</div>

<!-- SECTION 1: PERMOHONAN MENUNGGU PERSETUJUAN -->
<section class="inventory-section-card" style="margin-bottom: 24px;">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title" style="display: flex; align-items: center; gap: 8px;">
                Permohonan Pinjam Menunggu Persetujuan
                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; padding: 0 6px; border-radius: 999px; background: #f59e0b; color: #fff; font-size: 11px; font-weight: 700; line-height: 1;">
                    <?= count($permohonanMenunggu) ?>
                </span>
            </h3>
            <p class="inventory-sub">Pengajuan pinjam yang membutuhkan verifikasi dan persetujuan langsung dari petugas laboran.</p>
        </div>
        <a href="index.php?c=petugas&a=peminjaman" style="font-size: 13px; font-weight: 600; color: var(--teal-dark); text-decoration: none;">
            Kelola Semua &rarr;
        </a>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Kode & Siswa</th>
                <th>Alat Lab Diminta</th>
                <th>Keperluan / Jenis</th>
                <th>Waktu Pinjam</th>
                <th>Rencana Kembali</th>
                <th style="text-align: right;">Aksi Persetujuan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($permohonanMenunggu)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Saat ini tidak ada permohonan pinjam baru yang menunggu persetujuan.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($permohonanMenunggu as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--primary-teal);"><?= htmlspecialchars($p['kode_peminjaman'] ?? '-') ?></div>
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($p['nama_peminjam'] ?? 'Siswa') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted);">NIS: <?= htmlspecialchars($p['nis'] ?? '-') ?> &bull; <?= htmlspecialchars($p['kelas'] ?? '') ?></div>
                        </td>
                        <td class="col-instrument-name">
                            <strong><?= htmlspecialchars($p['nama_alat'] ?? 'Alat Lab') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= (int)($p['jumlah'] ?? 1) ?> unit &bull; <?= htmlspecialchars($p['nama_kategori'] ?? '') ?></div>
                        </td>
                        <td>
                            <span style="font-size: 12px; color: var(--text-secondary);"><?= htmlspecialchars($p['keperluan'] ?? '-') ?></span>
                        </td>
                        <td style="white-space: nowrap;"><?= !empty($p['waktu_pinjam']) ? date('H:i', strtotime($p['waktu_pinjam'])) . ' WIB' : '-' ?></td>
                        <td style="white-space: nowrap;">
                            <strong style="color: var(--teal-dark);"><?= !empty($p['waktu_rencana_kembali']) ? date('H:i', strtotime($p['waktu_rencana_kembali'])) . ' WIB' : '-' ?></strong>
                        </td>
                        <td class="table-actions-cell">
                            <a href="index.php?c=petugas&a=setujui_peminjaman&id=<?= $p['id'] ?>" class="btn-action-edit" style="background: #10b981; color: white;" onclick="return confirm('Setujui permohonan pinjam ini?')">
                                ✓ Setujui
                            </a>
                            <a href="index.php?c=petugas&a=tolak_peminjaman&id=<?= $p['id'] ?>" class="btn-action-delete" onclick="return confirm('Tolak permohonan pinjam ini?')">
                                ✕ Tolak
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- SECTION 2: PEMANTAUAN PENGEMBALIAN & PEMINJAMAN AKTIF -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Pemantauan Pengembalian Alat Lab</h3>
            <p class="inventory-sub">Daftar perangkat yang sedang dipinjam siswa dan harus dipantau pengembaliannya.</p>
        </div>
        <a href="index.php?c=petugas&a=pengembalian" class="btn-action-edit" style="font-size: 12px; text-decoration: none;">
            + Catat Pengembalian
        </a>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Kode & Siswa</th>
                <th>Alat Lab</th>
                <th>Jml</th>
                <th>Waktu Pinjam</th>
                <th>Tenggat Rencana</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($peminjamanAktif)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada alat yang sedang dipinjam saat ini. Semua alat berada di laboratorium.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($peminjamanAktif as $pa): 
                    $wktKembali = !empty($pa['waktu_rencana_kembali']) ? date('H:i', strtotime($pa['waktu_rencana_kembali'])) . ' WIB' : '-';
                    $terlambat = !empty($pa['waktu_rencana_kembali']) && (strtotime($pa['waktu_rencana_kembali']) < strtotime(date('H:i:s')));
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--primary-teal);"><?= htmlspecialchars($pa['kode_peminjaman'] ?? '-') ?></div>
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($pa['nama_peminjam'] ?? 'Siswa') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted);">NIS: <?= htmlspecialchars($pa['nis'] ?? '-') ?> &bull; <?= htmlspecialchars($pa['kelas'] ?? '') ?></div>
                        </td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($pa['nama_alat'] ?? 'Alat Lab') ?>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($pa['kode_alat'] ?? '') ?></div>
                        </td>
                        <td><strong><?= (int)($pa['jumlah'] ?? 1) ?></strong> unit</td>
                        <td style="white-space: nowrap;"><?= !empty($pa['waktu_pinjam']) ? date('H:i', strtotime($pa['waktu_pinjam'])) . ' WIB' : '-' ?></td>
                        <td style="white-space: nowrap;">
                            <?php if ($terlambat): ?>
                                <span style="color: #ef4444; font-weight: 700;">
                                    <?= $wktKembali ?>
                                    <span style="display:block; font-size:11px; font-weight:400;">⚠ Terlewat</span>
                                </span>
                            <?php else: ?>
                                <strong style="color: var(--teal-dark);"><?= $wktKembali ?></strong>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge badge-ready">
                                <?= ucfirst(htmlspecialchars($pa['status'])) ?>
                            </span>
                        </td>
                        <td class="table-actions-cell">
                            <a href="index.php?c=petugas&a=pengembalian" class="btn-action-edit" style="font-size: 12px; text-decoration: none;">
                                Proses Kembali
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/petugas_footer.php'; ?>
