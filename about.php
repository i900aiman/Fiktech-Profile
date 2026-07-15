<?php
/**
 * Fiktech Enterprise - About Us Page
 */
$pageTitle = 'Tentang Kami | FIKTECH ENTERPRISE';
$pageDesc = 'Ketahui latar belakang, visi, misi, dan nilai teras yang memacu FIKTECH ENTERPRISE.';
$activePage = 'about';

require_once __DIR__ . '/includes/header.php';
?>
<!-- Page Header -->
<section class="section-padding" style="background: linear-gradient(180deg, rgba(212,175,55,0.03) 0%, transparent 100%); padding-top: 150px; padding-bottom: 60px;">
    <div class="container text-center">
        <h1 style="font-size: 3rem; margin-bottom: 15px;"><span class="text-gold">MENGENAI</span> KAMI</h1>
        <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto;">Ketahui latar belakang, visi, misi, dan nilai teras yang memacu FIKTECH ENTERPRISE.</p>
    </div>
</section>

<!-- Company History -->
<section class="section-padding">
    <div class="container about-grid">
        <div class="about-text">
            <h3>Latar Belakang</h3>
            <h2>Komitmen Kami Terhadap Kecemerlangan Teknologi</h2>
            <p>
                Ditubuhkan pada tahun 2018, **FIKTECH ENTERPRISE** bermula sebagai sebuah firma perunding rangkaian kecil di Cyberjaya. Melalui kerja keras, kepakaran teknikal yang tinggi, dan kepercayaan pelanggan, kami telah berkembang menjadi sebuah syarikat penyedia solusi IT korporat yang holistik.
            </p>
            <p>
                Hari ini, kami berbangga dapat melayani ratusan pelanggan merentasi pelbagai sektor seperti kewangan, runcit, pembuatan, logistik dan agensi kerajaan. Fokus kami adalah menyediakan penyelesaian berasaskan awan, reka bentuk aplikasi web, keselamatan siber, serta pemasangan infrastruktur perkakasan IT yang kukuh.
            </p>
        </div>
        <div class="glass-card" style="padding: 40px; border-color: rgba(212,175,55,0.25);">
            <div style="text-align: center; margin-bottom: 25px;">
                <i class="fas fa-building text-gold" style="font-size: 3.5rem; text-shadow: var(--gold-glow);"></i>
            </div>
            <h3 style="font-family: var(--font-heading); font-size: 1.3rem; margin-bottom: 15px; text-align: center;">Profil Korporat</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; color: var(--text-secondary);">
                <tr>
                    <td style="padding: 10px 0; font-weight: bold; color: #FFF; width: 40%;">Nama Syarikat</td>
                    <td style="padding: 10px 0;">FIKTECH ENTERPRISE</td>
                </tr>
                <tr style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 10px 0; font-weight: bold; color: #FFF;">Tahun Ditubuhkan</td>
                    <td style="padding: 10px 0;">2018</td>
                </tr>
                <tr style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 10px 0; font-weight: bold; color: #FFF;">Lokasi Ibu Pejabat</td>
                    <td style="padding: 10px 0;">Cyberjaya, Selangor</td>
                </tr>
                <tr style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 10px 0; font-weight: bold; color: #FFF;">Fokus Industri</td>
                    <td style="padding: 10px 0;">IT & Cloud Solutions, Networking, Cybersecurity</td>
                </tr>
            </table>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="section-padding" style="background-color: var(--secondary-bg);">
    <div class="container vision-mision">
        <div class="glass-card vm-card">
            <i class="fas fa-eye"></i>
            <h3>Visi Kami</h3>
            <p style="color: var(--text-secondary); line-height: 1.8;">
                Menjadi sebuah syarikat penyedia penyelesaian digital dan IT bersepadu yang paling dipercayai di Malaysia, terkenal dengan inovasi produk, rekaan premium, serta komitmen keselamatan data bertaraf dunia.
            </p>
        </div>
        
        <div class="glass-card vm-card">
            <i class="fas fa-bullseye"></i>
            <h3>Misi Kami</h3>
            <p style="color: var(--text-secondary); line-height: 1.8;">
                Membantu syarikat PKS dan korporat melaksanakan transformasi digital secara selamat dan berkesan menerusi sistem tersuai, sokongan teknikal yang responsif, dan prasarana rangkaian yang berintegriti tinggi.
            </p>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <h2>Nilai Teras Syarikat</h2>
            <p>Prinsip utama yang menjadi tunjang budaya kerja dan penyampaian perkhidmatan kami.</p>
        </div>
        
        <div class="values-grid">
            <div class="glass-card value-card">
                <i class="fas fa-circle-check"></i>
                <h4>Integriti</h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary);">Sentiasa jujur dan telus dalam setiap transaksi perniagaan dan pemasangan perkakasan.</p>
            </div>
            
            <div class="glass-card value-card">
                <i class="fas fa-lightbulb"></i>
                <h4>Inovasi</h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary);">Meneroka idea, teknologi, dan penyelesaian baharu bagi memudahkan operasi perniagaan.</p>
            </div>
            
            <div class="glass-card value-card">
                <i class="fas fa-shield"></i>
                <h4>Keselamatan</h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary);">Mengutamakan aspek perlindungan data dan privasi maklumat sulit pelanggan kami.</p>
            </div>
            
            <div class="glass-card value-card">
                <i class="fas fa-users"></i>
                <h4>Fokus Pelanggan</h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary);">Memastikan keperluan pelanggan dipenuhi dengan perkhidmatan sokongan teknikal pantas.</p>
            </div>
        </div>
    </div>
</section>

<!-- Timeline -->
<section class="section-padding" style="background-color: var(--secondary-bg);">
    <div class="container">
        <div class="section-header">
            <h2>Timeline Syarikat</h2>
            <p>Sorotan perkembangan dan pencapaian utama kami sepanjang tahun bertapak.</p>
        </div>
        
        <div class="timeline">
            <!-- Timeline Item 1 -->
            <div class="timeline-item left-item">
                <div class="timeline-content">
                    <div class="timeline-date">2018</div>
                    <h4 style="margin-bottom: 8px;">Penubuhan Syarikat</h4>
                    <p style="font-size: 0.88rem; color: var(--text-secondary);">Mula bertapak di Cyberjaya sebagai firma kecil konsultan rangkaian (networking consultancy) dengan 3 orang staf pengasas.</p>
                </div>
            </div>
            
            <!-- Timeline Item 2 -->
            <div class="timeline-item right-item">
                <div class="timeline-content">
                    <div class="timeline-date">2020</div>
                    <h4 style="margin-bottom: 8px;">Pelancaran Servis Cloud & Web</h4>
                    <p style="font-size: 0.88rem; color: var(--text-secondary);">Memperluaskan perkhidmatan merangkumi pembangunan laman web dan solusi migrasi awan (cloud solutions) bagi memenuhi pasaran pandemik digital.</p>
                </div>
            </div>
            
            <!-- Timeline Item 3 -->
            <div class="timeline-item left-item">
                <div class="timeline-content">
                    <div class="timeline-date">2023</div>
                    <h4 style="margin-bottom: 8px;">Menembusi Pasaran Enterprise</h4>
                    <p style="font-size: 0.88rem; color: var(--text-secondary);">Berjaya mendapat kontrak sokongan IT jangka panjang dengan beberapa syarikat logistik dan kewangan mikro antarabangsa di Malaysia.</p>
                </div>
            </div>
            
            <!-- Timeline Item 4 -->
            <div class="timeline-item right-item">
                <div class="timeline-content">
                    <div class="timeline-date">2026</div>
                    <h4 style="margin-bottom: 8px;">Integrasi Rangkaian & Keselamatan Siber Pintar</h4>
                    <p style="font-size: 0.88rem; color: var(--text-secondary);">Menawarkan audit keselamatan siber yang mendalam dan perancangan transformasi digital pintar untuk menyokong pertumbuhan mampan pelanggan.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
