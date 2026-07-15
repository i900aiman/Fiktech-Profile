/**
 * Fiktech Enterprise - Global JavaScript File
 * Handles: Sticky Header, Hamburger Menu, Smooth Scrolling, Portfolio Filtering & Details Modal
 */

document.addEventListener('DOMContentLoaded', () => {
    initHeader();
    initMobileMenu();
    initPortfolio();
});

/**
 * 1. Sticky Header
 * Adds 'scrolled' class to header when window is scrolled down
 */
function initHeader() {
    const header = document.getElementById('header');
    if (!header) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
}

/**
 * 2. Hamburger Mobile Menu
 * Toggles mobile menu and spans animations
 */
function initMobileMenu() {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');

    if (!hamburger || !navLinks) return;

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        
        // Hamburger cross animation
        const spans = hamburger.querySelectorAll('span');
        if (navLinks.classList.contains('active')) {
            spans[0].style.transform = 'rotate(-45deg) translate(-5px, 6px)';
            spans[1].style.opacity = '0';
            spans[2].style.transform = 'rotate(45deg) translate(-5px, -6px)';
        } else {
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
        }
    });

    // Close menu when clicking outside or on a link
    document.addEventListener('click', (e) => {
        if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
            navLinks.classList.remove('active');
            const spans = hamburger.querySelectorAll('span');
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
        }
    });
}

/**
 * 3. Portfolio Mock Data & Interactions
 */
const PORTFOLIO_DATA = {
    "proj-1": {
        title: "E-Commerce Web Portal",
        category: "Website",
        desc: "Sistem e-dagang berprestasi tinggi yang dibangunkan untuk perniagaan runcit tempatan. Menyokong pembayaran automatik menerusi FPX, pengurusan inventori secara real-time, dan dashboard jualan lengkap.",
        tech: ["HTML5", "CSS3", "JavaScript", "Python Flask", "Tailwind CSS"],
        image: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80"
    },
    "proj-2": {
        title: "SaaS HR Management System",
        category: "Web Application",
        desc: "Aplikasi berasaskan awan untuk pengurusan sumber manusia. Membantu syarikat menguruskan profil pekerja, merekod kehadiran melalui pengecaman wajah secara digital, tuntutan claim, dan kelulusan cuti.",
        tech: ["React.js", "Flask API", "PostgreSQL", "Tailwind CSS", "Docker"],
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80"
    },
    "proj-3": {
        title: "Corporate Network Infrastructure",
        category: "Networking",
        desc: "Penyusunan semula dan menaik taraf infrastruktur rangkaian pejabat korporat bertingkat. Melibatkan pemasangan Cisco switches, router, penyediaan VLAN yang selamat, firewall perlindungan, dan liputan WiFi 6.",
        tech: ["Cisco Switches", "VLAN Segmentation", "Fortigate Firewall", "WLAN Controller"],
        image: "https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=800&q=80"
    },
    "proj-4": {
        title: "Hybrid Cloud Migration Project",
        category: "Networking",
        desc: "Migrasi pangkalan data fizikal syarikat ke persekitaran AWS Hybrid Cloud. Mengurangkan kos penyelenggaraan perkakasan lokal sebanyak 40% dan meningkatkan kebolehsediaan data sehingga 99.99%.",
        tech: ["Amazon Web Services", "EC2", "RDS", "AWS S3", "VPN Gateway"],
        image: "https://images.unsplash.com/photo-1600132806370-bf17e65e942f?auto=format&fit=crop&w=800&q=80"
    },
    "proj-5": {
        title: "Continuous IT Support & Desk",
        category: "IT Support",
        desc: "Outsourcing bantuan IT harian untuk syarikat logistik antarabangsa dengan lebih 150 staf. Memastikan pemulihan perkakasan pantas, bantuan teknikal dalam masa nyata, dan pematuhan patch keselamatan berkala.",
        tech: ["SLA Monitoring", "Active Directory", "MDM Management", "Remote Support"],
        image: "https://images.unsplash.com/photo-1600132806608-231446b2e7af?auto=format&fit=crop&w=800&q=80"
    },
    "proj-6": {
        title: "Corporate Security Audit & Hardening",
        category: "IT Support",
        desc: "Audit keselamatan menyeluruh dan ujian penembusan (penetration testing) untuk institusi kewangan mikro. Menampal kerentanan sistem kritikal dan menyediakan latihan kesedaran cybersecurity kepada pekerja.",
        tech: ["Penetration Testing", "Security Hardening", "OWASP Top 10", "Social Engineering Audit"],
        image: "https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=800&q=80"
    }
};

function initPortfolio() {
    const filters = document.querySelectorAll('.filter-btn');
    const items = document.querySelectorAll('.portfolio-item');
    const modal = document.getElementById('portfolio-modal');
    
    if (filters.length === 0 && items.length === 0) return;

    // Filter Logic
    filters.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active from all
            filters.forEach(f => f.classList.remove('active'));
            // Add to current
            btn.classList.add('active');
            
            const category = btn.getAttribute('data-filter');
            
            items.forEach(item => {
                const itemCat = item.getAttribute('data-category');
                if (category === 'all' || itemCat === category) {
                    item.classList.add('show');
                } else {
                    item.classList.remove('show');
                }
            });
        });
    });

    // Make sure initial display is loaded
    items.forEach(item => item.classList.add('show'));

    // Modal Details Show Logic
    items.forEach(item => {
        item.addEventListener('click', () => {
            const id = item.getAttribute('data-id');
            const data = PORTFOLIO_DATA[id];
            
            if (!data || !modal) return;
            
            // Populate Modal Elements
            const titleEl = modal.querySelector('.modal-title');
            const catEl = modal.querySelector('.modal-cat');
            const descEl = modal.querySelector('.modal-desc');
            const imgEl = modal.querySelector('.modal-img-container img');
            const techEl = modal.querySelector('.modal-tech');
            
            if (titleEl) titleEl.textContent = data.title;
            if (catEl) catEl.textContent = data.category;
            if (descEl) descEl.textContent = data.desc;
            if (imgEl) imgEl.src = data.image;
            
            if (techEl) {
                techEl.innerHTML = '';
                data.tech.forEach(t => {
                    const span = document.createElement('span');
                    span.className = 'portfolio-tag';
                    span.textContent = t;
                    techEl.appendChild(span);
                });
            }
            
            // Show modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden'; // Lock scrolling
        });
    });

    // Modal Close Logic
    const closeBtn = document.querySelector('.close-modal');
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    function closeModal() {
        modal.classList.remove('show');
        document.body.style.overflow = ''; // Unlock scrolling
    }
}
