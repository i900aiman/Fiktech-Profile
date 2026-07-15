<?php
/**
 * Fiktech Enterprise - Admin Dashboard
 */
require_once __DIR__ . '/../includes/contact_storage.php';

$activeTab = 'dashboard';
require_once __DIR__ . '/includes/header.php';

$stats = get_dashboard_stats();
?>
<div class="admin-title-row">
    <div>
        <h2>Dashboard</h2>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Analisis ringkas bagi borang maklum balas Contact Us.</p>
    </div>
    <div style="font-family: 'Orbitron', sans-serif; font-size: 0.9rem; border: 1px solid var(--border-color); padding: 8px 15px; border-radius: 4px;">
        <i class="far fa-calendar-alt text-gold" style="margin-right: 8px;"></i> Portal Admin
    </div>
</div>

<!-- Stats Counter Grid -->
<div class="admin-stats-grid">
    <!-- Stat 1: Total -->
    <div class="admin-stat-card">
        <div class="admin-stat-info">
            <h4>Total Submissions</h4>
            <div class="stat-num"><?= e($stats['total_submissions']) ?></div>
        </div>
        <div class="admin-stat-icon"><i class="fas fa-inbox"></i></div>
    </div>
    
    <!-- Stat 2: Today -->
    <div class="admin-stat-card">
        <div class="admin-stat-info">
            <h4>Submissions Today</h4>
            <div class="stat-num"><?= e($stats['today_submissions']) ?></div>
        </div>
        <div class="admin-stat-icon"><i class="fas fa-calendar-day"></i></div>
    </div>
    
    <!-- Stat 3: New -->
    <div class="admin-stat-card">
        <div class="admin-stat-info">
            <h4>New Submissions</h4>
            <div class="stat-num"><?= e($stats['new_submissions']) ?></div>
        </div>
        <div class="admin-stat-icon"><i class="fas fa-bell"></i></div>
    </div>
    
    <!-- Stat 4: Active Days -->
    <div class="admin-stat-card">
        <div class="admin-stat-info">
            <h4>Active Days (JSON Files)</h4>
            <div class="stat-num"><?= e($stats['active_days_count']) ?></div>
        </div>
        <div class="admin-stat-icon"><i class="fas fa-file-invoice"></i></div>
    </div>
</div>

<!-- Latest Submissions Panel -->
<div class="admin-panel">
    <div class="admin-panel-title">
        <i class="fas fa-list" style="color: var(--accent-gold); margin-right: 10px;"></i> 5 Submission Terbaharu
    </div>
    
    <?php if (!empty($stats['latest_submissions'])): ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats['latest_submissions'] as $item): 
                    $parts = explode('T', $item['submitted_at']);
                    $datePart = $parts[0];
                    $timePart = isset($parts[1]) ? substr(explode('+', $parts[1])[0], 0, 5) : '';
                ?>
                <tr>
                    <td style="white-space: nowrap;">
                        <?= e($datePart) ?> 
                        <span style="font-size:0.85em; color:var(--text-secondary);">
                            <?= e($timePart) ?>
                        </span>
                    </td>
                    <td><strong><?= e($item['full_name']) ?></strong></td>
                    <td><?= e($item['email']) ?></td>
                    <td><?= e($item['subject']) ?></td>
                    <td>
                        <?php if ($item['status'] === 'new'): ?>
                        <span class="badge badge-new">New</span>
                        <?php else: ?>
                        <span class="badge badge-read">Read</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="contact_detail.php?id=<?= urlencode($item['id']) ?>" class="action-btn">
                            <i class="fas fa-eye"></i> View Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div style="text-align: right; margin-top: 20px;">
        <a href="contacts.php" class="action-btn" style="border-color: var(--accent-gold); color: var(--accent-gold);">
            Urus Semua Contact <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
        </a>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-envelope-open-text"></i>
        <p>Tiada sebarang contact submission yang diterima setakat ini.</p>
    </div>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
