<?php
/**
 * Fiktech Enterprise - Global Header Component
 */
require_once __DIR__ . '/config.php';

// Safe fallbacks for page metadata
$title = isset($pageTitle) ? $pageTitle : 'FIKTECH ENTERPRISE | Powering Your Digital Future';
$description = isset($pageDesc) ? $pageDesc : 'FIKTECH ENTERPRISE menyediakan perkhidmatan IT bertaraf premium, rangkaian komputer, penyelesaian cloud, reka bentuk web dan konsultasi cybersecurity.';
$activePage = isset($activePage) ? $activePage : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    
    <!-- SEO Meta Tags -->
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?= e($title) ?>">
    <meta property="og:description" content="<?= e($description) ?>">
    <meta property="og:type" content="website">
    
    <!-- Styling & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="static/css/style.css">
    
    <!-- Favicon Placeholder -->
    <link rel="icon" type="image/x-icon" href="static/favicon.ico">
</head>
<body>
    <!-- Subtle Circuit and Particle Background Elements -->
    <div class="circuit-bg"></div>

    <!-- Header Navigation -->
    <header id="header">
        <div class="container navbar-container">
            <div class="logo">
                <a href="index.php">
                <!-- <img src="static/images/fiktech_logo2.jpeg" alt="FIKTECH Logo" style="margin-right: 10px; height: 30px; vertical-align: middle;">FIKTECH -->
                                <img src="static/images/fiktech_logo.png" alt="FIKTECH Logo" style="height: 70px; vertical-align: middle; margin-right: 10px;">FIKTECH

                </a>
            </div>
            
            <nav>
                <ul class="nav-links" id="nav-links">
                    <li class="<?= $activePage === 'home' ? 'active' : '' ?>"><a href="index.php">Home</a></li>
                    <li class="<?= $activePage === 'about' ? 'active' : '' ?>"><a href="about.php">About Us</a></li>
                    <li class="<?= $activePage === 'services' ? 'active' : '' ?>"><a href="services.php">Services</a></li>
                    <li class="<?= $activePage === 'portfolio' ? 'active' : '' ?>"><a href="portfolio.php">Portfolio</a></li>
                    <li class="<?= $activePage === 'contact' ? 'active' : '' ?>"><a href="contact.php">Contact Us</a></li>
                </ul>
            </nav>
            
            <button class="hamburger" id="hamburger" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
