<?php
$activePage = 'log';
$pageTitle = 'Log Aktivitas Pengguna - SEWANADA';
require_once 'Views/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">AUDIT SISTEM</span>
        <h1 class="admin-title">Log Aktivitas Pengguna</h1>
        <p class="admin-subtitle">Catatan rekam jejak aktivitas login, peminjaman, dan pengelolaan data oleh seluruh pengguna.</p>
    </div>
</div>

<!-- INVENTORY CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Riwayat Aktivitas</h3>
            <p class="inventory-sub">Total <?= count($daftarLog) ?> rekaman aktivitas sistem.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="log">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari aktivitas atau nama..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Pengguna</th>
                <th>Role</th>
                <th>Aktivitas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarLog)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada aktivitas yang tercatat.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarLog as $log): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td style="white-space: nowrap;"><?= htmlspecialchars($log['waktu_format'] ?? $log['waktu']) ?></td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($log['nama_lengkap'] ?? 'Sistem / Anonim') ?>
                            <div style="font-size: 12px; font-weight: 400; color: var(--text-muted);">
                                @<?= htmlspecialchars($log['username'] ?? '-') ?>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($log['role'])): ?>
                                <span class="badge-role badge-role-<?= htmlspecialchars($log['role']) ?>">
                                    <?= ucfirst(htmlspecialchars($log['role'])) ?>
                                </span>
                            <?php else: ?>
                                <span class="badge-role badge-role-peminjam">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($log['aktivitas']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once 'Views/admin_footer.php'; ?>
