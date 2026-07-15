<?php
/**
 * Fiktech Enterprise - 500 Error Page
 */
$pageTitle = 'Ralat | Masalah Dalaman Server';
$activePage = '';

require_once __DIR__ . '/includes/header.php';
?>
<section class="container error-page-container">
    <div class="error-code">500</div>
    <h2>RALAT DALAMAN SERVER</h2>
    <p>
        Maaf, sistem mengalami sedikit gangguan teknikal. Jurutera IT kami telah dimaklumkan secara automatik dan sedang membaiki isu ini dengan segera. Sila cuba layari semula sebentar lagi.
    </p>
    <div style="display: flex; gap: 20px;">
        <a href="index.php" class="btn btn-primary"><i class="fas fa-home" style="margin-right: 8px;"></i> Laman Utama</a>
        <a href="contact.php" class="btn btn-secondary">Hubungi Kami</a>
    </div>
</section>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
