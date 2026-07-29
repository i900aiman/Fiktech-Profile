<?php
/**
 * Fiktech Enterprise - Portfolio Page
 */
$pageTitle = 'Portfolio Kami | FIKTECH ENTERPRISE';
$pageDesc = 'Penerokaan projek-projek teknologi maklumat dan transformasi rangkaian yang berjaya kami selesaikan.';
$activePage = 'portfolio';

require_once __DIR__ . '/includes/header.php';
?>
<!-- Page Header -->
<section class="section-padding" style="background: linear-gradient(180deg, rgba(212,175,55,0.03) 0%, transparent 100%); padding-top: 150px; padding-bottom: 60px;">
    <div class="container text-center">
        <h1 style="font-size: 3rem; margin-bottom: 15px;"><span class="text-gold">PORTFOLIO</span> KAMI</h1>
        <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto;">Penerokaan projek-projek teknologi maklumat dan transformasi rangkaian yang berjaya kami selesaikan.</p>
    </div>
</section>

<!-- Portfolio Grid & Filter Section -->
<section class="section-padding">
    <div class="container">
        
        <!-- Category Filters -->
        <div class="portfolio-filters">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="Web Application">Web Application</button>
            <button class="filter-btn" data-filter="Produk Kami">Produk Kami</button>
        </div>
        
        <!-- Responsive Grid -->
        <div class="portfolio-grid">
            
            
    <!-- Project 1 -->
    <div class="portfolio-item show" data-category="Web Application" data-id="proj-1">
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
    <div class="portfolio-item show" data-category="Web Application" data-id="proj-2">
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
    <div class="portfolio-item show" data-category="Web Application" data-id="proj-3">
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

     <!-- Project 4 -->
    <div class="portfolio-item show" data-category="Produk Kami" data-id="proj-4">
        <div class="glass-card portfolio-card">
            <div class="portfolio-img">
                <img src="/static/images/kms_logo.jpeg" alt="Ejaraah" class="portfolio-card-img">
            </div>
            <div class="portfolio-info">
                <span class="portfolio-cat">Web Application</span>
                <h3>Kelas Mengaji Saderi</h3>
                <p>Sistem pengurusan mengaji bagi memudahkan pengajaran</p>
                <div class="portfolio-tags">
                    <span class="portfolio-tag">React.js</span>
                    <span class="portfolio-tag">Flask API</span>
                </div>
            </div>
        </div>
    </div>
            

     <!-- Project 5 -->
    <div class="portfolio-item show" data-category="Produk Kami" data-id="proj-5">
        <div class="glass-card portfolio-card">
            <div class="portfolio-img">
                <img src="/static/images/atk_logo.jpeg" alt="Ejaraah" class="portfolio-card-img">
            </div>
            <div class="portfolio-info">
                <span class="portfolio-cat">Web Application</span>
                <h3>Akademi Tarung Kalimah</h3>
                <p>Sistem pengurusan silat</p>
                <div class="portfolio-tags">
                    <span class="portfolio-tag">React.js</span>
                    <span class="portfolio-tag">Flask API</span>
                </div>
            </div>
        </div>
    </div>
            
            <!-- Project 4 -->
            <!-- <div class="portfolio-item" data-category="Web Application" data-id="proj-4">
                <div class="glass-card portfolio-card">
                    <div class="portfolio-img">
                        <div class="portfolio-img-placeholder">
                            <i class="fas fa-cloud-arrow-up"></i>
                            <span style="font-family: var(--font-heading); font-size: 0.8rem;">Hybrid Cloud Migration Project</span>
                        </div>
                    </div>
                    <div class="portfolio-info">
                        <span class="portfolio-cat">Networking</span>
                        <h3>Hybrid Cloud Migration Project</h3>
                        <p>Migrasi pangkalan data fizikal tempatan syarikat ke persekitaran awan AWS Hybrid yang selamat.</p>
                        <div class="portfolio-tags">
                            <span class="portfolio-tag">AWS Cloud</span>
                            <span class="portfolio-tag">EC2 & RDS</span>
                            <span class="portfolio-tag">S3 Storage</span>
                        </div>
                    </div>
                </div>
            </div> -->
            
            <!-- Project 5 -->
            <!-- <div class="portfolio-item" data-category="IT Support" data-id="proj-5">
                <div class="glass-card portfolio-card">
                    <div class="portfolio-img">
                        <div class="portfolio-img-placeholder">
                            <i class="fas fa-headset"></i>
                            <span style="font-family: var(--font-heading); font-size: 0.8rem;">Continuous IT Support & Helpdesk</span>
                        </div>
                    </div>
                    <div class="portfolio-info">
                        <span class="portfolio-cat">IT Support</span>
                        <h3>Continuous IT Support & Helpdesk</h3>
                        <p>Penyediaan khidmat bantuan teknikal (helpdesk) dan penyelenggaraan sistem operasi 150+ staf logistik.</p>
                        <div class="portfolio-tags">
                            <span class="portfolio-tag">Active Directory</span>
                            <span class="portfolio-tag">MDM</span>
                            <span class="portfolio-tag">Remote Support</span>
                        </div>
                    </div>
                </div>
            </div> -->
            
            <!-- Project 6 -->
            <!-- <div class="portfolio-item" data-category="IT Support" data-id="proj-6">
                <div class="glass-card portfolio-card">
                    <div class="portfolio-img">
                        <div class="portfolio-img-placeholder">
                            <i class="fas fa-shield-halved"></i>
                            <span style="font-family: var(--font-heading); font-size: 0.8rem;">Corporate Security Audit</span>
                        </div>
                    </div>
                    <div class="portfolio-info">
                        <span class="portfolio-cat">IT Support</span>
                        <h3>Corporate Security Audit</h3>
                        <p>Audit keselamatan siber mendalam, ujian penembusan (penetration testing), dan pengukuhan sistem server.</p>
                        <div class="portfolio-tags">
                            <span class="portfolio-tag">Penetration Testing</span>
                            <span class="portfolio-tag">OWASP Security</span>
                        </div>
                    </div>
                </div>
            </div> -->
            
        </div>
    </div>
</section>

<!-- Detail Modal Structure (Common) -->
<div class="modal" id="portfolio-modal">
    <div class="modal-content">
        <button class="close-modal">&times;</button>
        <div class="modal-body">
            <div class="modal-img-container">
                <img src="" alt="Project detail image">
            </div>
            <div class="modal-meta">
                <span class="portfolio-cat modal-cat" style="margin-bottom: 0;">Category</span>
            </div>
            <h3 class="modal-title">Project Title</h3>
            <p class="modal-desc" style="color: var(--text-secondary); margin-bottom: 30px;">Detailed project description...</p>
            <h4 style="font-family: var(--font-heading); font-size: 0.9rem; margin-bottom: 12px; text-transform: uppercase;">Teknologi Yang Digunakan:</h4>
            <div class="modal-tech">
                <!-- Tags will be dynamically injected here by JS -->
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
