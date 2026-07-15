<?php
/**
 * Fiktech Enterprise - Global Configuration
 * Parses .env file, sets up secure sessions, and handles CSRF protection.
 */

// 1. Parse .env file if it exists
function loadEnv($dir) {
    $envPath = $dir . '/.env';
    if (!file_exists($envPath)) {
        return;
    }
    
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);
            
            // Remove wrapping quotes if present
            if (preg_match('/^"(.+)"$/', $val, $matches)) {
                $val = $matches[1];
            } elseif (preg_match('/^\'(.+)\'$/', $val, $matches)) {
                $val = $matches[1];
            }
            
            $_ENV[$key] = $val;
            putenv("$key=$val");
        }
    }
}

// Load env from project root folder
loadEnv(dirname(__DIR__));

// Helper to retrieve env variables with default fallback
function get_env($key, $default = null) {
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    $val = getenv($key);
    return $val !== false ? $val : $default;
}

// 2. Setup Secure Sessions
if (session_status() === PHP_SESSION_NONE) {
    $secure = false;
    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1)) {
        $secure = true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        $secure = true;
    } elseif (strtolower(get_env('SESSION_COOKIE_SECURE', 'false')) === 'true') {
        $secure = true;
    }
    
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
}

// 3. CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token() {
    return $_SESSION['csrf_token'] ?? '';
}

function csrf_verify($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Signed timestamp used to reject submissions posted impossibly fast by bots.
function contact_form_token() {
    $timestamp = time();
    $secret = get_env('SECRET_KEY', session_id());
    $signature = hash_hmac('sha256', (string) $timestamp, $secret . csrf_token());
    return $timestamp . '.' . $signature;
}

function contact_form_token_verify($token, $minimumAge = 3, $maximumAge = 7200) {
    if (!is_string($token) || !preg_match('/^(\d{10})\.([a-f0-9]{64})$/', $token, $matches)) {
        return false;
    }

    $timestamp = (int) $matches[1];
    $age = time() - $timestamp;
    if ($age < $minimumAge || $age > $maximumAge) {
        return false;
    }

    $secret = get_env('SECRET_KEY', session_id());
    $expected = hash_hmac('sha256', (string) $timestamp, $secret . csrf_token());
    return hash_equals($expected, $matches[2]);
}

// Helper to prevent Session Fixation (call this on successful login)
function regenerate_session() {
    session_regenerate_id(true);
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Helper to print safe HTML strings
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
