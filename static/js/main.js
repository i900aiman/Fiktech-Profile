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
        title: "Jodoh Murni",
        category: "Web Application",
        desc: "Jodoh Murni adalah aplikasi untuk mencari Jodoh seperti Monogami dan Poligami",
        tech: ["HTML5", "CSS3", "JavaScript", "Python Flask", "Tailwind CSS"],
        image: "/static/images/JodohMurni.png",
        url: "https://jodohmurni.com"
    },
    "proj-2": {
        title: "9Hours HR Management System",
        category: "Web Application",
        desc: "Aplikasi berasaskan awan untuk pengurusan sumber manusia. Membantu syarikat menguruskan profil pekerja, merekod kehadiran melalui pengecaman wajah secara digital, tuntutan claim, dan kelulusan cuti.",
        tech: ["React.js", "Flask API", "PostgreSQL", "Tailwind CSS", "Docker"],
        image: "/static/images/9hours_logo.jpeg",
        url: "https://9hours.my"
    },
    "proj-3": {
        title: "Ejaarah",
        category: "Web Application",
        desc: "Sistem surau yang memberikan perkhidmatan seperi Khairat Kematian.",
        tech: ["Cisco Switches", "VLAN Segmentation", "Fortigate Firewall", "WLAN Controller"],
        image: "/static/images/saderi_logo.jpeg",
        url: "https://ejaarah.my"
    },
    "proj-4": {
        title: "Kelas Mengaji Saderi",
        category: "Web Application",
        desc: "Sistem surau yang memberikan perkhidmatan seperi Khairat Kematian.",
        tech: ["Amazon Web Services", "EC2", "RDS", "AWS S3", "VPN Gateway"],
        image: "/static/images/kms_logo.jpeg",
            // Tak letak url = button tak akan muncul
    },
    "proj-5": {
        title: "Akademi Tarung Kalimah",
        category: "Web Application",
        desc: "Outsourcing bantuan IT harian untuk syarikat logistik antarabangsa dengan lebih 150 staf. Memastikan pemulihan perkakasan pantas, bantuan teknikal dalam masa nyata, dan pematuhan patch keselamatan berkala.",
        tech: ["SLA Monitoring", "Active Directory", "MDM Management", "Remote Support"],
        image: "/static/images/atk_logo.jpeg",
        url: "https://ejaarah.my"
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
    const visitBtn = modal.querySelector('.visit-btn');
    
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

            if (data.url) {
    // Check if button already exists, if yes remove it
    const existingBtn = modal.querySelector('.visit-btn-container');
    if (existingBtn) {
        existingBtn.remove();
    }
    
    // Create button container
    const btnContainer = document.createElement('div');
    btnContainer.className = 'visit-btn-container';
    btnContainer.style.cssText = 'margin-top: 2rem;';
    
    // Create button/link element
    const visitLink = document.createElement('a');
    visitLink.href = data.url;
    visitLink.target = '_blank';
    visitLink.rel = 'noopener noreferrer';
    visitLink.className = 'visit-btn';
    visitLink.style.cssText = `
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: #fbbf24;
        color: #0a0a0a;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        font-family: var(--font-heading);
        transition: all 0.3s ease;
    `;
    visitLink.onmouseover = function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 4px 12px rgba(251, 191, 36, 0.4)';
    };
    visitLink.onmouseout = function() {
        this.style.transform = 'none';
        this.style.boxShadow = 'none';
    };
    
    // Create icon (optional - guna FontAwesome)
    const icon = document.createElement('i');
    icon.className = 'fas fa-external-link-alt';
    icon.style.fontSize = '0.9rem';
    
    // Create text
    const text = document.createElement('span');
    text.textContent = 'Visit Website';
    
    // Append icon and text to link
    visitLink.appendChild(icon);
    visitLink.appendChild(text);
    
    // Append link to container
    btnContainer.appendChild(visitLink);
    
    // Append container to modal body (desc element parent)
    if (descEl && descEl.parentNode) {
        descEl.parentNode.appendChild(btnContainer);
    }
}
            
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
