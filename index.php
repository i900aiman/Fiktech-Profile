<?php
/**
 * Fiktech Enterprise - Home Page
 */
$pageTitle = 'FIKTECH ENTERPRISE | Powering Your Digital Future';
$pageDesc = 'FIKTECH ENTERPRISE menyediakan perkhidmatan IT bertaraf premium, rangkaian komputer, penyelesaian cloud, reka bentuk web dan konsultasi cybersecurity.';
$activePage = 'home';

require_once __DIR__ . '/includes/header.php';
?>
<!-- Hero Section -->
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-content">
            <h3>Penyedia Solusi IT & Rangkaian</h3>
            <h1>FIKTECH <br><span class="text-gold">ENTERPRISE</span></h1>
            <p style="font-size: 1.2rem; color: #FFF; font-family: var(--font-heading); margin-bottom: 15px; letter-spacing: 0.5px;">“Powering Your Digital Future”</p>
            <p>Kami menyediakan Servis Pembangunan Sistem Web-based & Mobile Apps untuk memudahkan pengurusan Bisnes Anda.</p>
            <div class="hero-buttons">
                <a href="services.php" class="btn btn-primary">Tentang Bisnes Kami<i class="fas fa-arrow-right"></i></a>
                <a href="contact.php" class="btn btn-secondary">Hubungi Kami</a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="tech-sphere">
                <div class="floating-node node-1"></div>
                <div class="floating-node node-2"></div>
                <div class="floating-node node-3"></div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-bar">
    <div class="container stats-grid">
        <div class="stat-item">
            <h3>3</h3>
            <p>Projek Selesai</p>
        </div>
        <div class="stat-item">
            <h3>98%</h3>
            <p>Kepuasan Pelanggan</p>
        </div>
        <div class="stat-item">
            <h3>2</h3>
            <p>Kontrak Aktif</p>
        </div>
        <div class="stat-item">
            <h3>6+</h3>
            <p>Tahun Pengalaman</p>
        </div>
    </div>
</section>

<!-- Company Summary Section -->
<section class="section-padding">
    <div class="container company-grid">
        <div>
            <h2 style="font-size: 2.2rem; margin-bottom: 20px; font-family: var(--font-heading);"><span class="text-gold">Masa Depan</span> Perniagaan Anda Bermula Di Sini</h2>
            <p style="color: var(--text-secondary); margin-bottom: 20px;">
                Ditubuhkan dengan visi untuk menjadi peneraju transformasi digital, **FIKTECH ENTERPRISE** menawarkan khidmat konsultasi teknologi bersepadu. Kami memahami bahawa setiap perniagaan mempunyai cabaran unik, oleh itu kami menyediakan sistem custom yang bersesuaian dengan matlamat korporat anda.
            </p>
           
            <a href="about.php" class="btn btn-secondary">Lebih Lanjut Mengenai Kami</a>
        </div>
        <div class="glass-card" style="padding: 40px; text-align: center; border-color: rgba(212,175,55,0.25);">
            <i class="fas fa-shield-halved" style="font-size: 4rem; color: var(--accent-gold); margin-bottom: 25px; text-shadow: var(--gold-glow);"></i>
            <h3 style="font-size: 1.5rem; margin-bottom: 15px;">Integriti & Keselamatan</h3>
            <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.7;">
                Kami menjamin kebolehpercayaan dan tahap perlindungan data tertinggi di dalam setiap sistem yang kami pasang, mengikut piawaian industri antarabangsa terkemuka.
            </p>
        </div>
    </div>
</section>

<!-- Featured Services Section -->
<section class="section-padding" style="background-color: var(--secondary-bg);">
    <div class="container">
        <div class="section-header">
            <h2>Perkhidmatan Utama</h2>
            <p>Penyelesaian IT menyeluruh yang dirangka khas untuk memacu pertumbuhan perniagaan anda.</p>
        </div>
        
        <div class="services-grid">
            <!-- Service 1 -->
            <div class="glass-card service-card">
                <i class="fas fa-code"></i>
                <h3>Website Design & Development</h3>
                <p>Website korporat yang responsif, pantas, mesra SEO dan mempunyai rekaan bertaraf premium.</p>
                <a href="services.php" class="card-link">Learn More <i class="fas fa-chevron-right"></i></a>
            </div>
            
            <!-- Service 2 -->
            <div class="glass-card service-card">
                <i class="fas fa-laptop-code"></i>
                <h3>Custom Web Application</h3>
                <p>Pembangunan sistem aplikasi berasaskan web mengikut keperluan spesifik perniagaan anda.</p>
                <a href="services.php" class="card-link">Learn More <i class="fas fa-chevron-right"></i></a>
            </div>
            
            <!-- Service 3 -->
            <div class="glass-card service-card">
                <i class="fas fa fa-mobile"></i>
                <h3>Mobile Application Development</h3>
                <p> Bina Aplikasi Telefon idaman anda.</p>
                <a href="services.php" class="card-link">Learn More <i class="fas fa-chevron-right"></i></a>
            </div>
            
            <!-- Service 4 -->
            <div class="glass-card service-card">
                <i class="fas fa-headset"></i>
                <h3>IT Support & Maintenance</h3>
                <p> Perkhidmatan penyelenggaraan komputer korporat secara bulanan, pemantauan status server, pemasangan perisian antirusus, pembersihan malware, dan talian bantuan bantuan teknikal.</p>
                <a href="services.php" class="card-link">Learn More <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 50px;">
            <a href="services.php" class="btn btn-secondary">Lihat Semua Perkhidmatan</a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <h2>Kenapa Memilih Kami</h2>
            <p>Komitmen kami adalah untuk memberikan servis bertaraf dunia dengan hasil yang berkualiti.</p>
        </div>
        
        <div class="features-grid">
            <div class="glass-card feature-card">
                <i class="fas fa-user-tie"></i>
                <h3>Kepakaran Profesional</h3>
                <p>Pasukan kami terdiri daripada jurutera IT bertauliah tinggi dengan pengalaman bertahun-tahun dalam industri teknologi.</p>
            </div>
            
            <div class="glass-card feature-card">
                <i class="fas fa-rocket"></i>
                <h3>Teknologi Terkini</h3>
                <p>Kami sentiasa mengemas kini kaedah kerja dan alatan kami menggunakan standard teknologi moden terbaharu.</p>
            </div>
            
            <div class="glass-card feature-card">
                <i class="fas fa-headset"></i>
                <h3>Sokongan 24/7</h3>
                <p>Talian bantuan teknikal kami sentiasa aktif bagi memantau dan membaiki isu rangkaian anda pada bila-bila masa.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Portfolio Section -->
<section class="section-padding" style="background-color: var(--secondary-bg);">
    <div class="container">
        <div class="section-header">
            <h2>Portfolio Terpilih</h2>
            <p>Imbasan beberapa projek terkini yang telah kami jayakan untuk rakan kongsi kami.</p>
        </div>

        
        
<div class="portfolio-grid">

    <!-- Project 1 -->
    <div class="portfolio-item show" data-category="Networking" data-id="proj-1" style="display: block;">
        <div class="glass-card portfolio-card">
            <div class="portfolio-img">
                <img src="/static/images/JodohMurni.png" alt="Jodoh Murni" class="portfolio-card-img">
            </div>
            <div class="portfolio-info">
                <span class="portfolio-cat">Web Application</span>
                <h3>Jodoh Murni</h3>
                <p>Jodoh Murni adalah aplikasi untuk mencari Jodoh seperti Monogami dan Poligami</p>
                <div class="portfolio-tags">
                    <span class="portfolio-tag">Cisco Switches</span>
                    <span class="portfolio-tag">Firewall</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Project 2 -->
    <div class="portfolio-item show" data-category="Web Application" data-id="proj-2" style="display: block;">
        <div class="glass-card portfolio-card">
            <div class="portfolio-img">
                <img src="/static/images/9hours_logo.jpeg" alt="9 Hours" class="portfolio-card-img">
            </div>
            <div class="portfolio-info">
                <span class="portfolio-cat">Web Application</span>
                <h3>9Hours HR Management System</h3>
                <p>Aplikasi awan untuk merekod kehadiran staff secara digital, claim, dan urusan cuti pekerja.</p>
                <div class="portfolio-tags">
                    <span class="portfolio-tag">React.js</span>
                    <span class="portfolio-tag">Flask API</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Project 3 -->
    <div class="portfolio-item show" data-category="Web Application" data-id="proj-3" style="display: block;">
        <div class="glass-card portfolio-card">
            <div class="portfolio-img">
                <img src="/static/images/saderi_logo.jpeg" alt="Ejaraah" class="portfolio-card-img">
            </div>
            <div class="portfolio-info">
                <span class="portfolio-cat">Web Application</span>
                <h3>Ejaraah</h3>
                <p>Sistem surau yang memberikan perkhidmatan seperi Khairat Kematian.</p>
                <div class="portfolio-tags">
                    <span class="portfolio-tag">React.js</span>
                    <span class="portfolio-tag">Flask API</span>
                </div>
            </div>
        </div>
    </div>

</div>
        
        <div style="text-align: center; margin-top: 50px;">
            <a href="portfolio.php" class="btn btn-secondary">Lihat Semua Portfolio</a>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section-padding">
    <div class="container">
        <div class="cta-banner">
            <h2>Bersedia Membawa Perniagaan Anda Ke Arah Digital?</h2>
            <p>Hubungi jurutera teknologi kami hari ini untuk konsultasi percuma dan ketahui bagaimana FIKTECH boleh membantu anda.</p>
            <a href="contact.php" class="btn btn-primary" style="box-shadow: var(--gold-glow);">Hubungi Kami Sekarang <i class="fas fa-envelope" style="margin-left: 8px;"></i></a>
        </div>
    </div>
</section>

<!-- Modal for portfolio detail (injected by main.js) -->
<div class="modal" id="portfolio-modal">
    <div class="modal-content">
        <button class="close-modal">&times;</button>
        <div class="modal-body">
            <div class="modal-img-container">
                <img src="" alt="Project detail picture" id="modal-project-img">
            </div>
            <div class="modal-meta">
                <span class="portfolio-cat modal-cat" style="margin-bottom: 0;">Category</span>
            </div>
            <h3 class="modal-title">Project Title</h3>
            <p class="modal-desc">Detailed project description goes here...</p>
            <h4 style="font-family: var(--font-heading); font-size: 0.9rem; margin-bottom: 12px; text-transform: uppercase;">Teknologi Yang Digunakan:</h4>
            <div class="modal-tech">
                <!-- Tags will be dynamically injected here -->
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
