<?php
/**
 * Fiktech Enterprise - JSON Storage Management (Thread-Safe)
 * Handles incoming submissions, outgoing email history, and SMTP settings.
 */

// Define directory paths
if (!defined('DATA_DIR')) define('DATA_DIR', dirname(__DIR__) . '/data');
if (!defined('CONTACTS_DIR')) define('CONTACTS_DIR', DATA_DIR . '/contacts');
if (!defined('INCOMING_DIR')) define('INCOMING_DIR', CONTACTS_DIR . '/incoming');
if (!defined('OUTGOING_DIR')) define('OUTGOING_DIR', CONTACTS_DIR . '/outgoing');
if (!defined('SETTINGS_FILE')) define('SETTINGS_FILE', DATA_DIR . '/settings.json');

// Ensure necessary directories exist
function ensure_directories_exist() {
    if (!file_exists(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }
    if (!file_exists(CONTACTS_DIR)) {
        mkdir(CONTACTS_DIR, 0755, true);
    }
    if (!file_exists(INCOMING_DIR)) {
        mkdir(INCOMING_DIR, 0755, true);
    }
    if (!file_exists(OUTGOING_DIR)) {
        mkdir(OUTGOING_DIR, 0755, true);
    }
}

// Generate simple UUID v4
function generate_uuid() {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// Get DateTime in KL timezone
function get_kl_now() {
    $timezone = new DateTimeZone('Asia/Kuala_Lumpur');
    return new DateTime('now', $timezone);
}

// Get filename for date (e.g. 13-7-2026.json)
function get_filename_for_date($datetime) {
    return $datetime->format('j-n-Y') . '.json';
}

// Safe thread-locked JSON read
function safe_read_json($filepath) {
    if (!file_exists($filepath)) {
        return [];
    }
    
    $fp = fopen($filepath, 'r');
    if (!$fp) {
        return [];
    }
    
    // Acquire a shared reader lock
    flock($fp, LOCK_SH);
    $size = filesize($filepath);
    $content = $size > 0 ? fread($fp, $size) : '';
    flock($fp, LOCK_UN);
    fclose($fp);
    
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

// Safe thread-locked atomic write
function safe_write_json($filepath, $data) {
    $tempFile = $filepath . '.' . uniqid('tmp', true);
    
    $fp = fopen($tempFile, 'w');
    if (!$fp) {
        return false;
    }
    
    // Lock the temp file exclusively
    flock($fp, LOCK_EX);
    $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    fwrite($fp, $content);
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    
    // Atomic rename/replace
    if (!rename($tempFile, $filepath)) {
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        return false;
    }
    return true;
}

/**
 * 1. Incoming submissions storage
 */
function save_incoming_submission($cleaned_data) {
    ensure_directories_exist();
    
    $now = get_kl_now();
    $filename = get_filename_for_date($now);
    $filepath = INCOMING_DIR . '/' . $filename;
    
    $record = [
        "id" => generate_uuid(),
        "submitted_at" => $now->format(DateTime::ATOM),
        "full_name" => $cleaned_data['full_name'],
        "email" => $cleaned_data['email'],
        "phone" => $cleaned_data['phone'],
        "company_name" => $cleaned_data['company_name'] ?? '',
        "subject" => $cleaned_data['subject'],
        "service" => $cleaned_data['service'],
        "message" => $cleaned_data['message'],
        "consent" => $cleaned_data['consent'],
        "status" => "new"
    ];
    
    // Lock and update
    $fp = fopen($filepath, 'c+');
    if ($fp) {
        flock($fp, LOCK_EX);
        
        $size = filesize($filepath);
        $content = $size > 0 ? fread($fp, $size) : '';
        $records = json_decode($content, true);
        if (!is_array($records)) {
            $records = [];
        }
        
        $records[] = $record;
        
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    } else {
        return false;
    }
    
    return $record;
}

function update_incoming_status($id, $new_status) {
    if (!in_array($new_status, ['new', 'read'], true)) {
        return false;
    }
    
    ensure_directories_exist();
    $dir = opendir(INCOMING_DIR);
    if (!$dir) {
        return false;
    }
    
    while (($file = readdir($dir)) !== false) {
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'json') {
            continue;
        }
        
        $filepath = INCOMING_DIR . '/' . $file;
        
        // Open file for read/write
        $fp = fopen($filepath, 'r+');
        if ($fp) {
            flock($fp, LOCK_EX);
            
            $size = filesize($filepath);
            $content = $size > 0 ? fread($fp, $size) : '';
            $records = json_decode($content, true);
            
            $updated = false;
            if (is_array($records)) {
                foreach ($records as &$record) {
                    if ($record['id'] === $id) {
                        $record['status'] = $new_status;
                        $updated = true;
                        break;
                    }
                }
            }
            
            if ($updated) {
                ftruncate($fp, 0);
                rewind($fp);
                fwrite($fp, json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                fflush($fp);
                flock($fp, LOCK_UN);
                fclose($fp);
                closedir($dir);
                return true;
            }
            
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }
    closedir($dir);
    return false;
}

function get_all_incoming($status_filter = null, $date_filter = null, $search_query = null) {
    ensure_directories_exist();
    $all_records = [];
    
    if ($date_filter) {
        $filepath = INCOMING_DIR . '/' . $date_filter;
        if (file_exists($filepath)) {
            $all_records = safe_read_json($filepath);
        }
    } else {
        $files = glob(INCOMING_DIR . '/*.json');
        foreach ($files as $file) {
            $records = safe_read_json($file);
            $all_records = array_merge($all_records, $records);
        }
    }
    
    $filtered = [];
    foreach ($all_records as $r) {
        // Status filter
        if ($status_filter && ($r['status'] ?? '') !== $status_filter) {
            continue;
        }
        
        // Search query filter (matches name, email, phone, subject)
        if ($search_query) {
            $q = strtolower($search_query);
            $name_match = strpos(strtolower($r['full_name'] ?? ''), $q) !== false;
            $email_match = strpos(strtolower($r['email'] ?? ''), $q) !== false;
            $phone_match = strpos(strtolower($r['phone'] ?? ''), $q) !== false;
            $sub_match = strpos(strtolower($r['subject'] ?? ''), $q) !== false;
            
            if (!($name_match || $email_match || $phone_match || $sub_match)) {
                continue;
            }
        }
        
        $filtered[] = $r;
    }
    
    // Sort by submitted_at DESC
    usort($filtered, function ($a, $b) {
        return strcmp($b['submitted_at'] ?? '', $a['submitted_at'] ?? '');
    });
    
    return $filtered;
}

function get_incoming_by_id($id) {
    ensure_directories_exist();
    $files = glob(INCOMING_DIR . '/*.json');
    foreach ($files as $file) {
        $records = safe_read_json($file);
        foreach ($records as $r) {
            if (($r['id'] ?? '') === $id) {
                $r['_source_file'] = basename($file);
                return $r;
            }
        }
    }
    return null;
}

/**
 * 2. Outgoing email storage
 */
function save_outgoing_email($email_data) {
    ensure_directories_exist();
    
    $now = get_kl_now();
    $filename = get_filename_for_date($now);
    $filepath = OUTGOING_DIR . '/' . $filename;
    
    $record = [
        "id" => generate_uuid(),
        "sent_at" => $now->format(DateTime::ATOM),
        "parent_contact_id" => $email_data['parent_contact_id'],
        "recipient_name" => $email_data['recipient_name'],
        "recipient_email" => $email_data['recipient_email'],
        "subject" => $email_data['subject'],
        "body" => $email_data['body']
    ];
    
    // Lock and update
    $fp = fopen($filepath, 'c+');
    if ($fp) {
        flock($fp, LOCK_EX);
        
        $size = filesize($filepath);
        $content = $size > 0 ? fread($fp, $size) : '';
        $records = json_decode($content, true);
        if (!is_array($records)) {
            $records = [];
        }
        
        $records[] = $record;
        
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    } else {
        return false;
    }
    
    return $record;
}

function get_outgoing_by_parent_id($parent_id) {
    ensure_directories_exist();
    $replies = [];
    $files = glob(OUTGOING_DIR . '/*.json');
    foreach ($files as $file) {
        $records = safe_read_json($file);
        foreach ($records as $r) {
            if (($r['parent_contact_id'] ?? '') === $parent_id) {
                $replies[] = $r;
            }
        }
    }
    
    // Sort by sent_at DESC
    usort($replies, function($a, $b) {
        return strcmp($b['sent_at'] ?? '', $a['sent_at'] ?? '');
    });
    
    return $replies;
}

/**
 * 3. Settings storage
 */
function get_settings() {
    return [
        'smtp_host' => get_env('SMTP_HOST', ''),
        'smtp_port' => intval(get_env('SMTP_PORT', '465')),
        'smtp_user' => get_env('SMTP_USERNAME', ''),
        'smtp_pass' => get_env('SMTP_PASSWORD', ''),
        'smtp_secure' => get_env('SMTP_ENCRYPTION', 'ssl'),
        'smtp_from_email' => get_env('SMTP_FROM_EMAIL', ''),
        'smtp_from_name' => get_env('SMTP_FROM_NAME', 'Fiktech Support')
    ];
}

function get_noreply_settings() {
    return [
        'smtp_host' => get_env('NOREPLY_SMTP_HOST', ''),
        'smtp_port' => intval(get_env('NOREPLY_SMTP_PORT', '465')),
        'smtp_user' => get_env('NOREPLY_SMTP_USERNAME', ''),
        'smtp_pass' => get_env('NOREPLY_SMTP_PASSWORD', ''),
        'smtp_secure' => get_env('NOREPLY_SMTP_ENCRYPTION', 'ssl'),
        'smtp_from_email' => get_env('NOREPLY_SMTP_FROM_EMAIL', ''),
        'smtp_from_name' => get_env('NOREPLY_SMTP_FROM_NAME', 'Fiktech Auto-Response')
    ];
}

function save_settings($data) {
    return true;
}

/**
 * 4. Stats utility
 */
function get_dashboard_stats() {
    ensure_directories_exist();
    
    $total_count = 0;
    $today_count = 0;
    $new_count = 0;
    
    $now = get_kl_now();
    $today_filename = get_filename_for_date($now);
    
    $files = glob(INCOMING_DIR . '/*.json');
    $day_files_count = count($files);
    
    $available_dates = [];
    foreach ($files as $file) {
        $basename = basename($file);
        $available_dates[] = $basename;
        
        $records = safe_read_json($file);
        $file_count = count($records);
        $total_count += $file_count;
        
        if ($basename === $today_filename) {
            $today_count = $file_count;
        }
        
        foreach ($records as $r) {
            if (($r['status'] ?? 'new') === 'new') {
                $new_count++;
            }
        }
    }
    
    // Sort available dates descending
    rsort($available_dates);
    
    $latest = get_all_incoming(null, null, null);
    $latest_5 = array_slice($latest, 0, 5);
    
    return [
        "total_submissions" => $total_count,
        "today_submissions" => $today_count,
        "new_submissions" => $new_count,
        "active_days_count" => $day_files_count,
        "latest_submissions" => $latest_5,
        "available_dates" => $available_dates
    ];
}
