<?php
/**
 * Fiktech Enterprise - 404 Error Page
 */
$pageTitle = 'Ralat | Halaman Tidak Dijumpai';
$activePage = '';

require_once __DIR__ . '/includes/header.php';
?>
<section class="container error-page-container">
    <div class="error-code">404</div>
    <h2>HALAMAN TIDAK DIJUMPAI</h2>
    <p>
        Maaf, halaman yang anda cuba layari tidak wujud, telah dipindahkan, atau dipadam secara kekal. Sila kembali ke halaman utama.
    </p>
    <div style="display: flex; gap: 20px;">
        <a href="index.php" class="btn btn-primary"><i class="fas fa-home" style="margin-right: 8px;"></i> Laman Utama</a>
        <a href="contact.php" class="btn btn-secondary">Hubungi Kami</a>
    </div>
</section>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
