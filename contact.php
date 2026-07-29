<?php
/**
 * Fiktech Enterprise - Contact Page
 */
$pageTitle = 'Hubungi Kami | FIKTECH ENTERPRISE';
$pageDesc = 'Hubungi jurutera IT kami hari ini untuk konsultasi, sokongan, atau sebut harga projek.';
$activePage = 'contact';

require_once __DIR__ . '/includes/header.php';
?>
<!-- Page Header -->
<section class="section-padding" style="background: linear-gradient(180deg, rgba(212,175,55,0.03) 0%, transparent 100%); padding-top: 150px; padding-bottom: 60px;">
    <div class="container text-center">
        <h1 style="font-size: 3rem; margin-bottom: 15px;"><span class="text-gold">HUBUNGI</span> KAMI</h1>
        <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto;">Hubungi jurutera IT kami hari ini untuk konsultasi, sokongan, atau sebut harga projek.</p>
    </div>
</section>

<!-- Contact Form & Info Layout -->
<section class="section-padding" style="padding-top: 20px;">
    <div class="container contact-layout">
        
        <!-- Left: Contact Details -->
        <div>
            <h2 style="font-size: 1.8rem; margin-bottom: 25px; font-family: var(--font-heading);"><span class="text-gold">Maklumat</span> Hubungan</h2>
            
            <div class="contact-info-list">
                <!-- Address -->
                <div class="info-item">
                    <i class="fas fa-map-location-dot"></i>
                    <div class="info-text">
                        <h4>Alamat Pejabat</h4>
                        <p>No. 2, Jalan Saderi P16, Presint 16, 62150, Putrajaya</p>
                    </div>
                </div>
                
                <!-- Phone -->
                <div class="info-item">
                    <i class="fas fa-phone-volume"></i>
                    <div class="info-text">
                        <h4>Nombor Telefon</h4>
                        <p>Mohd Ameer Fikry (BA): +613-5805761<br>Aiman (Developer): +618-6673843</p>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="info-item">
                    <i class="fas fa-envelope-open-text"></i>
                    <div class="info-text">
                        <h4>Email Rasmi</h4>
                        <p>fiktechsolutions@gmail.com</p>
                    </div>
                </div>
                
                <!-- Working Hours -->
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div class="info-text">
                        <h4>Waktu Operasi</h4>
                        <p>Isnin - Jumaat: 9:00 AM - 6:00 PM<br>Sabtu & Ahad: Bantuan Kecemasan IT Sahaja</p>
                    </div>
                </div>
            </div>
            
            <!-- Map Placeholder -->
           <div class="map-container">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4472.275771714208!2d101.70714737547057!3d2.936349997039937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cdc9af780840ab%3A0x3f3c56b62460adef!2sPPAM%20Saderi!5e1!3m2!1sen!2smy!4v1785309164825!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"
        width="100%" 
        height="450" 
        style="border:0; border-radius: 8px;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>

</div>
        </div>
        
        <!-- Right: Contact Form -->
        <div class="glass-card" style="padding: 40px; border-color: rgba(212,175,55,0.25);">
            <h2 style="font-size: 1.8rem; margin-bottom: 25px; font-family: var(--font-heading);">Hantar <span class="text-gold">Mesej</span></h2>
            
            <!-- Response Msg Box -->
            <div id="form-status-msg" class="form-status"></div>
            
            <form id="contact-form" autocomplete="off">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="form_token" value="<?= e(contact_form_token()) ?>">

                <!-- Honeypot: real visitors never see or fill this field. -->
                <div aria-hidden="true" style="position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden;">
                    <label for="website">Website</label>
                    <input type="text" id="website" name="website" value="" tabindex="-1" autocomplete="off">
                </div>
                
                <!-- Row 1: Name & Email -->
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="full_name">Full Name <span style="color:var(--accent-gold);">*</span></label>
                        <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Ahmad Ali" minlength="3" maxlength="100" autocomplete="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address <span style="color:var(--accent-gold);">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="ahmad@example.com" maxlength="100" autocomplete="email" required>
                    </div>
                </div>
                
                <!-- Row 2: Phone & Company -->
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="phone">Phone Number <span style="color:var(--accent-gold);">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="0123456789" minlength="7" maxlength="20" autocomplete="tel" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="company_name">Company Name <span style="color:var(--text-muted); font-size: 0.8em;">(Optional)</span></label>
                        <input type="text" id="company_name" name="company_name" class="form-control" placeholder="ABC Sdn Bhd" maxlength="100" autocomplete="organization">
                    </div>
                </div>
                
                <!-- Subject -->
                <div class="form-group">
                    <label for="subject">Subject <span style="color:var(--accent-gold);">*</span></label>
                    <input type="text" id="subject" name="subject" class="form-control" placeholder="Inquiry about Website Development" minlength="3" maxlength="150" required>
                </div>
                
                <!-- Service interested selection -->
                <div class="form-group">
                    <label for="service">Service Interested <span style="color:var(--accent-gold);">*</span></label>
                    <select id="service" name="service" class="form-control" required>
                        <option value="" disabled selected>Select a service...</option>
                        <option value="Website Design & Development">Website Design & Development</option>
                        <option value="Mobile Application Development">Mobile Application Development</option>
                        <option value="Custom Web Application">Custom Web Application</option>
                        <option value="IT Support & Maintenance">IT Support & Maintenance</option>
                    </select>
                </div>
                
                <!-- Message -->
                <div class="form-group">
                    <label for="message">Message <span style="color:var(--accent-gold);">*</span></label>
                    <textarea id="message" name="message" class="form-control" placeholder="Tulis mesej anda di sini..." minlength="10" maxlength="2000" required></textarea>
                </div>
                
                <!-- Consent checkbox -->
                <div class="form-group" style="margin-bottom: 30px;">
                    <label class="checkbox-container">
                        <input type="checkbox" id="consent" name="consent" required>
                        <span>Saya bersetuju untuk dihubungi oleh pihak FIKTECH ENTERPRISE melalui e-mel atau telefon.</span>
                    </label>
                </div>
                
                <!-- Submit -->
                <button type="submit" class="btn btn-primary" style="width: 100%; box-shadow: var(--gold-glow);">Hantar Mesej <i class="fas fa-paper-plane" style="margin-left: 8px;"></i></button>
            </form>
        </div>
        
    </div>
</section>
<?php
$pageScripts = '<script src="static/js/contact.js"></script>';
require_once __DIR__ . '/includes/footer.php';
?>
