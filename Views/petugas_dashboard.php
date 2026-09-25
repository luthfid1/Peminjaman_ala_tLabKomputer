<?php
$activePage = 'dashboard';
$pageTitle = 'Dashboard Petugas Laboran - Lab Komputer';
require_once __DIR__ . '/petugas_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label"><?= htmlspecialchars($tglStr) ?></span>
        <h1 class="admin-title">Pusat Kendali Petugas Lab</h1>
        <p class="admin-subtitle">Verifikasi pengajuan peminjaman alat, monitoring pengembalian, dan pastikan kelancaran sirkulasi perangkat laboratorium.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="index.php?c=petugas&a=pengembalian" class="btn-add-instrument">
            + Catat Pengembalian
        </a>
    </div>
</div>

<!-- 4 METRIC CARDS -->
<div class="admin-metrics-grid">
    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Menunggu Persetujuan</span>
            <div class="metric-icon-wrap" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $totalMenunggu ?></div>
        <div class="metric-sub" style="color: #b45309;">Perlu diverifikasi segera</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Sedang Dipinjam</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $totalAktif ?></div>
        <div class="metric-sub">Peminjaman aktif berjalan</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Total Pengembalian</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="9 11 12 14 22 4"></polyline>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $totalPengembalian ?></div>
        <div class="metric-sub">Transaksi selesai</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Total Alat Lab</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $totalAlat ?></div>
        <div class="metric-sub">Item inventaris lab</div>
    </div>
</div>

<!-- SECTION: PERMOHONAN MENUNGGU PERSETUJUAN -->
<section class="inventory-section-card" style="margin-bottom: 24px;">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title" style="display: flex; align-items: center; gap: 8px;">
                Permohonan Pinjam Menunggu Persetujuan
                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; padding: 0 6px; border-radius: 999px; background: #f59e0b; color: #fff; font-size: 11px; font-weight: 700; line-height: 1;">
                    <?= count($permohonanMenunggu) ?>
                </span>
            </h3>
            <p class="inventory-sub">Pengajuan peminjaman dari siswa yang memerlukan konfirmasi petugas laboran.</p>
        </div>
        <a href="index.php?c=petugas&a=peminjaman" style="font-size: 13px; font-weight: 600; color: var(--teal-dark); text-decoration: none;">
            Kelola Semua &rarr;
        </a>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Siswa</th>
                <th>Alat Lab Diminta</th>
                <th>Keperluan</th>
                <th>Waktu Pinjam</th>
                <th>Rencana Kembali</th>
                <th style="text-align: right;">Aksi Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($permohonanMenunggu)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada permohonan pinjam baru yang menunggu persetujuan saat ini.
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
                            <?= htmlspecialchars($p['nama_alat'] ?? 'Alat Lab') ?>
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
                            <a href="index.php?c=petugas&a=setujui_peminjaman&id=<?= $p['id'] ?>" class="btn-action-edit" style="background: #10b981; color: white;" onclick="return confirm('Setujui permohonan peminjaman alat ini?')">
                                ✓ Setujui
                            </a>
                            <a href="index.php?c=petugas&a=tolak_peminjaman&id=<?= $p['id'] ?>" class="btn-action-delete" onclick="return confirm('Tolak permohonan peminjaman alat ini?')">
                                ✕ Tolak
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- SECTION: ALAT SEDANG DIPINJAM -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Alat Lab Sedang Dipinjam Aktif</h3>
            <p class="inventory-sub">Daftar perangkat yang saat ini berada di luar ruang laboratorium komputer.</p>
        </div>
        <a href="index.php?c=petugas&a=pengembalian" class="btn-action-edit" style="font-size: 12px; text-decoration: none;">
            Proses Pengembalian
        </a>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Siswa</th>
                <th>Alat Lab</th>
                <th>Jml</th>
                <th>Waktu Pinjam</th>
                <th>Tenggat Rencana</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($peminjamanAktif)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada alat yang sedang dipinjam saat ini. Semua perangkat aman di laboratorium.
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
                                <?= $wktKembali ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge badge-ready">
                                <?= ucfirst(htmlspecialchars($pa['status'])) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/petugas_footer.php'; ?>
