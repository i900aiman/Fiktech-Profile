<?php
/**
 * Fiktech Enterprise - Admin Contacts Management
 */
require_once __DIR__ . '/../includes/contact_storage.php';

$activeTab = 'contacts';
require_once __DIR__ . '/includes/header.php';

$statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;
$dateFilter = isset($_GET['date']) && $_GET['date'] !== '' ? $_GET['date'] : null;
$searchQuery = isset($_GET['search']) && $_GET['search'] !== '' ? $_GET['search'] : null;

// Get available files for the dropdown filter
$stats = get_dashboard_stats();
$availableDates = $stats['available_dates'] ?? [];

$submissions = get_all_incoming($statusFilter, $dateFilter, $searchQuery);
?>
<div class="admin-title-row">
    <div>
        <h2>Urus Contact Submissions</h2>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Senarai penuh mesej perhubungan daripada bakal pelanggan.</p>
    </div>
    <a href="dashboard.php" class="back-link" style="margin-bottom: 0;">
        <i class="fas fa-chevron-left"></i> Kembali ke Dashboard
    </a>
</div>

<!-- Filters Form -->
<form class="admin-filters-form" method="GET" action="contacts.php">
    <!-- Search Query -->
    <div class="filter-group" style="flex-grow: 2; min-width: 250px;">
        <label for="search">Carian Cepat</label>
        <input type="text" id="search" name="search" class="filter-control" placeholder="Cari nama, email, telefon atau subjek..." value="<?= e($searchQuery) ?>">
    </div>
    
    <!-- Status Filter -->
    <div class="filter-group" style="min-width: 150px;">
        <label for="status">Status</label>
        <select id="status" name="status" class="filter-control">
            <option value="">Semua (All)</option>
            <option value="new" <?= $statusFilter === 'new' ? 'selected' : '' ?>>New</option>
            <option value="read" <?= $statusFilter === 'read' ? 'selected' : '' ?>>Read</option>
        </select>
    </div>
    
    <!-- Date Filter (JSON Files) -->
    <div class="filter-group" style="min-width: 180px;">
        <label for="date">Tarikh Fail</label>
        <select id="date" name="date" class="filter-control">
            <option value="">Semua Tarikh</option>
            <?php foreach ($availableDates as $dateFile): ?>
            <option value="<?= e($dateFile) ?>" <?= $dateFilter === $dateFile ? 'selected' : '' ?>>
                <?= e(str_replace('.json', '', $dateFile)) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <!-- Action buttons -->
    <button type="submit" class="filter-submit-btn"><i class="fas fa-filter"></i> Filter</button>
    <a href="contacts.php" class="filter-clear-btn"><i class="fas fa-rotate-left"></i> Clear</a>
</form>

<!-- Table Panel -->
<div class="admin-panel">
    <?php if (!empty($submissions)): ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $item): 
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
                    <td><?= e($item['phone']) ?></td>
                    <td><?= e($item['service']) ?></td>
                    <td>
                        <?php if ($item['status'] === 'new'): ?>
                        <span class="badge badge-new">New</span>
                        <?php else: ?>
                        <span class="badge badge-read">Read</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space: nowrap; text-align: center;">
                        <a href="contact_detail.php?id=<?= urlencode($item['id']) ?>" class="action-btn" title="View Detail">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        
                        <!-- Toggle Status Button (AJAX-enabled via admin.js) -->
                        <?php if ($item['status'] === 'new'): ?>
                        <button type="button" class="action-btn action-btn-status toggle-status-btn" data-id="<?= e($item['id']) ?>" data-current-status="new" data-csrf="<?= csrf_token() ?>">
                            <i class="fas fa-envelope-open"></i> Mark Read
                        </button>
                        <?php else: ?>
                        <button type="button" class="action-btn toggle-status-btn" data-id="<?= e($item['id']) ?>" data-current-status="read" data-csrf="<?= csrf_token() ?>">
                            <i class="fas fa-envelope"></i> Mark New
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <p>Tiada data contact submission dijumpai berdasarkan filter carian anda.</p>
    </div>
    <?php endif; ?>
</div>
<?php
$adminScripts = '<script src="../static/js/admin.js"></script>';
require_once __DIR__ . '/includes/footer.php';
?>
