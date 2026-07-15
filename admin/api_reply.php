<?php
/**
 * Fiktech Enterprise - Admin AJAX Send Reply Handler
 * Connects to user SMTP config, sends HTML email, saves log in outgoing JSON, and updates status to read.
 */
header('Content-Type: application/json');

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/contact_storage.php';
require_once __DIR__ . '/../includes/smtp_client.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed."]);
    exit;
}

// CSRF Verification
$csrfToken = $_POST['csrf_token'] ?? '';
if (empty($csrfToken)) {
    if (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'];
    } elseif (function_exists('getallheaders')) {
        $headers = getallheaders();
        $csrfToken = $headers['X-CSRF-Token'] ?? '';
    }
}

if (!csrf_verify($csrfToken)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "CSRF verification failed."]);
    exit;
}

// Fetch Inputs
$parentContactId = $_POST['parent_contact_id'] ?? '';
$recipientName = trim($_POST['recipient_name'] ?? '');
$recipientEmail = trim($_POST['recipient_email'] ?? '');
$subject = trim($_POST['reply_subject'] ?? '');
$messageBody = trim($_POST['reply_message'] ?? '');

// Validate inputs
if (empty($parentContactId) || empty($recipientEmail) || empty($subject) || empty($messageBody)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "All mandatory fields must be filled."]);
    exit;
}

if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid recipient email address."]);
    exit;
}

// Retrieve SMTP Settings
$settings = get_settings();
if (empty($settings['smtp_host']) || empty($settings['smtp_user'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error", 
        "message" => "SMTP configurations are incomplete. Please set up SMTP in Email Settings first."
    ]);
    exit;
}

// Construct HTML Body with Corporate Premium Styling
$htmlBody = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f9f9f9; }
        .wrapper { background-color: #f9f9f9; padding: 30px 15px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); overflow: hidden; }
        .header { background-color: #0A0A0A; padding: 25px; text-align: center; border-bottom: 2px solid #D4AF37; }
        .header h2 { color: #D4AF37; margin: 0; font-size: 1.6rem; letter-spacing: 2px; }
        .content { padding: 35px 25px; }
        .content p { margin-bottom: 20px; }
        .message-box { background-color: #f5f5f5; border-left: 3px solid #D4AF37; padding: 15px 20px; border-radius: 4px; font-style: normal; margin: 25px 0; white-space: pre-wrap; color: #444; }
        .footer { background-color: #111111; padding: 20px; text-align: center; font-size: 0.8em; color: #888888; border-top: 1px solid rgba(255,255,255,0.05); }
        .footer a { color: #D4AF37; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h2>FIKTECH ENTERPRISE</h2>
            </div>
            <div class="content">
                <p>Salam Sejahtera <strong>{$recipientName}</strong>,</p>
                <p>Terima kasih kerana menghubungi pihak FIKTECH ENTERPRISE. Mesej balasan berikut dihantar oleh perunding IT kami:</p>
                <div class="message-box">{$messageBody}</div>
                <p>Sila balas e-mel ini jika anda mempunyai sebarang pertanyaan lanjut mengenai perkhidmatan kami.</p>
                <p>Sekian, terima kasih.<br><strong>Pasukan Sokongan Teknologi Fiktech</strong></p>
            </div>
            <div class="footer">
                <p>&copy; 2026 <a href="http://127.0.0.1:5000">FIKTECH ENTERPRISE</a>. Hak Cipta Terpelihara.</p>
                <p>Level 15, Tech Tower, Cyberjaya, Selangor, Malaysia</p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;

// Initialize SMTP Socket Client
$smtp = new SmtpClient($settings);

$fromEmail = !empty($settings['smtp_from_email']) ? $settings['smtp_from_email'] : $settings['smtp_user'];
$fromName = !empty($settings['smtp_from_name']) ? $settings['smtp_from_name'] : 'Fiktech Support';

$sendSuccess = $smtp->send($recipientEmail, $recipientName, $subject, $htmlBody, $fromEmail, $fromName);

if ($sendSuccess) {
    // 1. Record Outgoing Reply
    $emailData = [
        "parent_contact_id" => $parentContactId,
        "recipient_name" => $recipientName,
        "recipient_email" => $recipientEmail,
        "subject" => $subject,
        "body" => $messageBody
    ];
    $outgoingRecord = save_outgoing_email($emailData);
    
    // 2. Auto Mark Incoming Submission status as Read
    update_incoming_status($parentContactId, 'read');
    
    echo json_encode([
        "status" => "success",
        "message" => "Email reply has been sent successfully via SMTP! Log recorded in outgoing folder.",
        "record" => $outgoingRecord
    ]);
} else {
    // Log detailed connection stack in PHP error log securely
    $smtpLogs = $smtp->getLogs();
    error_log("Fiktech SMTP sending failure to {$recipientEmail}: " . implode("\n", $smtpLogs));
    
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "SMTP server transaction failed. Please verify your SMTP config, host, user and password settings.",
        "logs" => $smtpLogs // Included for debug logs in panel
    ]);
}
