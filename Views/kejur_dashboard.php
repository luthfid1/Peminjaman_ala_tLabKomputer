<?php
$activePage = 'dashboard';
$pageTitle = 'Dashboard Monitoring - Ketua Jurusan';
require_once __DIR__ . '/kejur_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label"><?= htmlspecialchars($tglStr) ?></span>
        <h1 class="admin-title">Monitoring Ketua Jurusan</h1>
        <p class="admin-subtitle">Pemantauan inventaris laboratorium komputer, rekapitulasi data peminjam, serta arsip laporan sirkulasi.</p>
    </div>
</div>

<!-- 4 METRIC CARDS -->
<div class="admin-metrics-grid">
    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Peminjam Terdaftar</span>
            <div class="metric-icon-wrap" style="background: rgba(15, 118, 110, 0.12); color: var(--teal-dark);">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $totalSiswa ?></div>
        <div class="metric-sub">Siswa / peminjam lab</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Alat Laboratorium</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $totalAlat ?></div>
        <div class="metric-sub">Perangkat inventaris</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Total Peminjaman</span>
            <div class="metric-icon-wrap" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $totalPeminjaman ?></div>
        <div class="metric-sub">Seluruh transaksi diajukan</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Total Pengembalian</span>
            <div class="metric-icon-wrap" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="9 11 12 14 22 4"></polyline>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $totalPengembalian ?></div>
        <div class="metric-sub">Transaksi selesai dipulihkan</div>
    </div>
</div>

<!-- 4 MENU LAPORAN UTAMA SESUAI USE CASE -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 28px;">
    <a href="index.php?c=kejur&a=laporan_peminjam" style="background: white; border: 1px solid var(--border-color); border-radius: 12px; padding: 18px 20px; text-decoration: none; display: flex; align-items: center; gap: 14px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(15, 118, 110, 0.1); color: var(--teal-dark); display: flex; align-items: center; justify-content: center;">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
            </svg>
        </div>
        <div>
            <strong style="color: var(--primary-navy); font-size: 14px; display: block;">Laporan Peminjam</strong>
            <span style="font-size: 12px; color: var(--text-muted);">Data siswa & kelas &rarr;</span>
        </div>
    </a>

    <a href="index.php?c=kejur&a=laporan_alat" style="background: white; border: 1px solid var(--border-color); border-radius: 12px; padding: 18px 20px; text-decoration: none; display: flex; align-items: center; gap: 14px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(14, 165, 233, 0.1); color: #0284c7; display: flex; align-items: center; justify-content: center;">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
            </svg>
        </div>
        <div>
            <strong style="color: var(--primary-navy); font-size: 14px; display: block;">Laporan Alat Lab</strong>
            <span style="font-size: 12px; color: var(--text-muted);">Inventaris & stok fisik &rarr;</span>
        </div>
    </a>

    <a href="index.php?c=kejur&a=laporan_peminjaman" style="background: white; border: 1px solid var(--border-color); border-radius: 12px; padding: 18px 20px; text-decoration: none; display: flex; align-items: center; gap: 14px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(245, 158, 11, 0.1); color: #d97706; display: flex; align-items: center; justify-content: center;">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
        </div>
        <div>
            <strong style="color: var(--primary-navy); font-size: 14px; display: block;">Laporan Peminjaman</strong>
            <span style="font-size: 12px; color: var(--text-muted);">Log sirkulasi keluar &rarr;</span>
        </div>
    </a>

    <a href="index.php?c=kejur&a=laporan_pengembalian" style="background: white; border: 1px solid var(--border-color); border-radius: 12px; padding: 18px 20px; text-decoration: none; display: flex; align-items: center; gap: 14px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); color: #059669; display: flex; align-items: center; justify-content: center;">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="9 11 12 14 22 4"></polyline>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
        </div>
        <div>
            <strong style="color: var(--primary-navy); font-size: 14px; display: block;">Laporan Pengembalian</strong>
            <span style="font-size: 12px; color: var(--text-muted);">Kondisi kembali & denda &rarr;</span>
        </div>
    </a>
</div>

<!-- SECTION: PERANGKAT SEDANG AKTIF DIPINJAM -->
<section class="inventory-section-card" style="margin-bottom: 24px;">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Perangkat Lab yang Sedang Digunakan Siswa</h3>
            <p class="inventory-sub">Total <?= count($alatAktifDipinjam) ?> unit perangkat berada di luar ruang laboratorium saat ini.</p>
        </div>
        <a href="index.php?c=kejur&a=laporan_peminjaman&status_filter=dipinjam" style="font-size: 13px; font-weight: 600; color: var(--teal-dark); text-decoration: none;">
            Lihat Detail &rarr;
        </a>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Siswa</th>
                <th>Alat Lab Dipinjam</th>
                <th>Jml</th>
                <th>Waktu Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($alatAktifDipinjam)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Saat ini tidak ada alat lab yang sedang dipinjam. Seluruh inventaris berada lengkap di laboratorium.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($alatAktifDipinjam as $a): 
                    $wktKembali = !empty($a['waktu_rencana_kembali']) ? date('H:i', strtotime($a['waktu_rencana_kembali'])) . ' WIB' : '-';
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--primary-teal);"><?= htmlspecialchars($a['kode_peminjaman'] ?? '-') ?></div>
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($a['nama_peminjam'] ?? '-') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($a['kelas'] ?? '-') ?></div>
                        </td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($a['nama_alat'] ?? '-') ?>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($a['kode_alat'] ?? '') ?></div>
                        </td>
                        <td><strong><?= (int)($a['jumlah'] ?? 1) ?></strong> unit</td>
                        <td style="white-space: nowrap;"><?= !empty($a['waktu_pinjam']) ? date('H:i', strtotime($a['waktu_pinjam'])) . ' WIB' : '-' ?></td>
                        <td style="white-space: nowrap;"><strong style="color: var(--teal-dark);"><?= $wktKembali ?></strong></td>
                        <td>
                            <span class="status-badge badge-ready"><?= ucfirst(htmlspecialchars($a['status'])) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/kejur_footer.php'; ?>
