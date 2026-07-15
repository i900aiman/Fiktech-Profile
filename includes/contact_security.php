<?php
/**
 * Lightweight, dependency-free abuse protection for the public contact form.
 */

function contact_client_ip() {
    // REMOTE_ADDR is intentionally used instead of spoofable forwarding headers.
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

function contact_rate_limit($maximumAttempts = 5, $windowSeconds = 600) {
    $directory = DATA_DIR . '/.rate_limits';
    if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
        // Fail open if storage is temporarily unavailable; submissions still have
        // CSRF, timing, honeypot and content validation protection.
        return ['allowed' => true, 'retry_after' => 0];
    }

    $secret = get_env('SECRET_KEY', 'fiktech-contact-rate-limit');
    $key = hash_hmac('sha256', contact_client_ip(), $secret);
    $filepath = $directory . '/' . $key . '.json';
    $now = time();

    $fp = fopen($filepath, 'c+');
    if (!$fp) {
        return ['allowed' => true, 'retry_after' => 0];
    }

    flock($fp, LOCK_EX);
    rewind($fp);
    $stored = stream_get_contents($fp);
    $attempts = json_decode($stored ?: '[]', true);
    if (!is_array($attempts)) {
        $attempts = [];
    }

    $attempts = array_values(array_filter($attempts, static function ($timestamp) use ($now, $windowSeconds) {
        return is_int($timestamp) && $timestamp > ($now - $windowSeconds);
    }));

    $allowed = count($attempts) < $maximumAttempts;
    $retryAfter = 0;
    if ($allowed) {
        $attempts[] = $now;
    } elseif (!empty($attempts)) {
        $retryAfter = max(1, $windowSeconds - ($now - min($attempts)));
    }

    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($attempts));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    return ['allowed' => $allowed, 'retry_after' => $retryAfter];
}

function contact_content_looks_like_spam($data) {
    $subject = isset($data['subject']) && is_scalar($data['subject']) ? (string) $data['subject'] : '';
    $message = isset($data['message']) && is_scalar($data['message']) ? (string) $data['message'] : '';
    $content = $subject . "\n" . $message;

    preg_match_all('~(?:https?://|www\.)~i', $content, $links);
    if (count($links[0]) > 3) {
        return true;
    }

    if (preg_match('/(?:\[url=|<a\s|href\s*=)/i', $content)) {
        return true;
    }

    if (preg_match('/(.)\1{14,}/u', $content)) {
        return true;
    }

    return false;
}
