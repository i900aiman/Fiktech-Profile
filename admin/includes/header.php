<?php
/**
 * Fiktech Enterprise - Admin Header Component
 */
require_once __DIR__ . '/auth.php';

$activeTab = $activeTab ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIKTECH | Admin Panel</title>
    
    <!-- Admin styles & Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../static/css/admin.css">
    
    <link rel="icon" type="image/x-icon" href="../static/favicon.ico">
</head>
<body>
    
    <!-- Admin header with navigation -->
    <header class="admin-header">
        <div class="admin-logo">
            <i class="fas fa-user-shield" style="color: var(--accent-gold);"></i> FIKTECH <span>ADMIN</span>
        </div>
        
        <div class="admin-nav">
            <a href="dashboard.php" class="admin-nav-link <?= $activeTab === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a href="contacts.php" class="admin-nav-link <?= $activeTab === 'contacts' ? 'active' : '' ?>">Contacts</a>
            
            <!-- Logout Form with CSRF Protection -->
            <form action="logout.php" method="POST" style="margin-left: 10px;">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <button type="submit" class="admin-logout-btn"><i class="fas fa-sign-out-alt" style="margin-right: 5px;"></i> Logout</button>
            </form>
        </div>
    </header>

    <!-- Main Admin Container -->
    <main class="admin-container">
