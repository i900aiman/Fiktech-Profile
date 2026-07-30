<?php
/**
 * Fiktech Enterprise - Global Footer Component
 */
?>
    </main>

    <!-- Footer Area -->
    <footer>
        <div class="container footer-grid">
            <div class="footer-col">
                <h4 style="font-family: var(--font-heading); color: #fff;">FIKTECH</h4>
                <p>Membantu memandu arah perniagaan anda ke era digital dengan penyelesaian teknologi moden, selamat, dan bertaraf premium.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/fiktechsolutions/" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.youtube.com/@FikTechSolutions" class="social-link" aria-label="Youtube"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.instagram.com/fiktechsolutions/" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="footer-col">
                <h4>Pautan Pantas</h4>
                <ul>
                    <li><a href="index.php">Utama (Home)</a></li>
                    <li><a href="about.php">Mengenai Kami (About)</a></li>
                    <li><a href="services.php">Perkhidmatan (Services)</a></li>
                    <li><a href="portfolio.php">Portfolio</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Perkhidmatan Kami</h4>
                <ul>
                    <li><a href="services.php">Web Development</a></li>
                    <li><a href="services.php">IT Support & Maintenance</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Hubungi Kami</h4>
                <p><i class="fas fa-map-marker-alt text-gold" style="margin-right: 8px;"></i> No. 2, Jalan Saderi P16, Presint 16, 62150, Putrajaya</p>
                <p><i class="fas fa-phone text-gold" style="margin-right: 8px;"></i>+6013-5805761/<br>+6018-6673843</p>
                <p><i class="fas fa-envelope text-gold" style="margin-right: 8px;"></i>fiktechsolutions<br>@gmail.com</p>
            </div>
        </div>
        
        <div class="container footer-bottom">
            <p>&copy; 2026 FIKTECH ENTERPRISE. Hak Cipta Terpelihara. | Powering Your Digital Future</p>
            <p style="font-size: 0.8rem;"><a href="admin/login.php" style="color: var(--text-muted);"><i class="fas fa-lock" style="margin-right: 5px;"></i>Admin Panel</a></p>
        </div>
    </footer>

    <!-- Global JavaScript File -->
    <script src="static/js/main.js"></script>
    <?php if (isset($pageScripts)) echo $pageScripts; ?>
</body>
</html>
