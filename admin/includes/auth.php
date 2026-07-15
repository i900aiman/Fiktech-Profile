<?php
/**
 * Fiktech Enterprise - Admin Authentication Guard
 */
require_once __DIR__ . '/../../includes/config.php';

if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
