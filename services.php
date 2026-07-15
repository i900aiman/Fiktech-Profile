<?php
/**
 * Fiktech Enterprise - Services Page
 */
$pageTitle = 'Perkhidmatan Kami | FIKTECH ENTERPRISE';
$pageDesc = 'Kami menyediakan penyelesaian teknologi maklumat hujung-ke-hujung bagi memenuhi sebarang cabaran korporat anda.';
$activePage = 'services';

require_once __DIR__ . '/includes/header.php';
?>
<!-- Page Header -->
<section class="section-padding" style="background: linear-gradient(180deg, rgba(212,175,55,0.03) 0%, transparent 100%); padding-top: 150px; padding-bottom: 60px;">
    <div class="container text-center">
        <h1 style="font-size: 3rem; margin-bottom: 15px;"><span class="text-gold">PERKHIDMATAN</span> KAMI</h1>
        <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto;">Kami menyediakan penyelesaian teknologi maklumat hujung-ke-hujung bagi memenuhi sebarang cabaran korporat anda.</p>
    </div>
</section>

<!-- Services Detail Grid -->
<section class="section-padding">
    <div class="container">
        <div class="services-grid three-cols">
            
            <!-- Service 1 -->
            <div class="glass-card service-card">
                <i class="fas fa-code"></i>
                <h3>Website Design & Development</h3>
                <p>
                    Laman web korporat premium yang responsif untuk telefon pintar, tablet dan desktop. Rekabentuk yang bersih, mesra carian SEO dan dioptimumkan untuk kelajuan muat turun yang pantas.
                </p>
                <a href="contact.php" class="card-link">Request Quote <i class="fas fa-chevron-right"></i></a>
            </div>

              
            <!-- Service 8 -->
            <div class="glass-card service-card">
                <i class="fas fa fa-mobile"></i>
                <h3>Mobile Application Development</h3>
                <p>
                    Bina Aplikasi Telefon idaman anda.
                </p>
                <a href="contact.php" class="card-link">Request Quote <i class="fas fa-chevron-right"></i></a>
            </div>
            
            <!-- Service 2 -->
            <div class="glass-card service-card">
                <i class="fas fa-laptop-code"></i>
                <h3>Custom Web Application</h3>
                <p>
                    Membangunkan aplikasi perniagaan berasaskan web tersuai seperti portal pelanggan, sistem invois, HRMS, CRM, dan lain-lain sistem mengikut aliran kerja khusus syarikat anda.
                </p>
                <a href="contact.php" class="card-link">Request Quote <i class="fas fa-chevron-right"></i></a>
            </div>
            
            <!-- Service 3 -->
            <div class="glass-card service-card">
                <i class="fas fa-headset"></i>
                <h3>IT Support & Maintenance</h3>
                <p>
                    Perkhidmatan penyelenggaraan komputer korporat secara bulanan, pemantauan status server, pemasangan perisian antirusus, pembersihan malware, dan talian bantuan bantuan teknikal.
                </p>
                <a href="contact.php" class="card-link">Request Quote <i class="fas fa-chevron-right"></i></a>
            </div>
            
         
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section-padding" style="background-color: var(--secondary-bg);">
    <div class="container">
        <div class="cta-banner" style="margin-top: 0;">
            <h2>Mempunyai Keperluan IT Yang Unik?</h2>
            <p>Berbincang dengan pakar perunding teknologi kami hari ini untuk merangka solusi tersuai yang bersesuaian dengan perniagaan anda.</p>
            <a href="contact.php" class="btn btn-primary">Dapatkan Quotation Percuma</a>
        </div>
    </div>
</section>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
