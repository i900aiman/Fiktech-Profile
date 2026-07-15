<?php
/**
 * Fiktech Enterprise - AJAX Form Submission Handler (api/contact.php)
 * Validates CSRF token, validates inputs, and records message in incoming files.
 */
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/validators.php';
require_once __DIR__ . '/../includes/contact_storage.php';
require_once __DIR__ . '/../includes/contact_security.php';
require_once __DIR__ . '/../includes/smtp_client.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed."]);
    exit;
}

if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 20000) {
    http_response_code(413);
    echo json_encode(["status" => "error", "message" => "Request is too large."]);
    exit;
}

// Read inputs (either JSON body or form encoded)
$inputRaw = file_get_contents('php://input');
$data = json_decode($inputRaw, true);

if (!is_array($data)) {
    $data = $_POST;
}

// Honeypot submissions are acknowledged but intentionally discarded so bots
// do not learn which protection triggered.
if (!empty($data['website'])) {
    echo json_encode(["status" => "success", "message" => "Your message has been received successfully!"]);
    exit;
}

// Retrieve and verify CSRF token
$csrfToken = $data['csrf_token'] ?? '';
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
    echo json_encode(["status" => "error", "message" => "CSRF validation failed. Please reload."]);
    exit;
}

if (!contact_form_token_verify($data['form_token'] ?? '')) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Form session expired or was submitted too quickly. Please reload and try again."]);
    exit;
}

$rateLimit = contact_rate_limit(5, 600);
if (!$rateLimit['allowed']) {
    header('Retry-After: ' . $rateLimit['retry_after']);
    http_response_code(429);
    echo json_encode(["status" => "error", "message" => "Too many submissions. Please wait a few minutes and try again."]);
    exit;
}

if (contact_content_looks_like_spam($data)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Your message could not be accepted. Please remove excessive links or markup."]);
    exit;
}

// Validate inputs
$validation = validate_contact_form($data);

if (!$validation['is_valid']) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Form validation failed.",
        "errors" => $validation['errors']
    ]);
    exit;
}

try {
    $saved = save_incoming_submission($validation['cleaned']);
    if ($saved) {
        $c = $validation['cleaned'];
        $adminNotificationSent = false;
        $autoReplySent = false;

        // Send a copy of the submission to the Roundcube/admin mailbox.
        try {
            $adminSettings = get_settings();
            $adminEmail = get_env('ADMIN_EMAIL', $adminSettings['smtp_user']);

            if (!empty($adminSettings['smtp_host']) && !empty($adminSettings['smtp_user']) && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                $adminSmtp = new SmtpClient($adminSettings);
                $adminFromEmail = $adminSettings['smtp_from_email'] ?: $adminSettings['smtp_user'];
                $adminFromName = $adminSettings['smtp_from_name'] ?: 'Fiktech Website';
                $adminSubject = "Contact Us: " . $c['subject'];
                $adminBody = "
                <!DOCTYPE html>
                <html><head><meta charset='UTF-8'></head>
                <body style='font-family:Arial,sans-serif;color:#222;line-height:1.6'>
                    <h2>New Contact Us Submission</h2>
                    <table cellpadding='8' cellspacing='0' style='border-collapse:collapse;width:100%;max-width:700px'>
                        <tr><th align='left' style='border:1px solid #ddd'>Name</th><td style='border:1px solid #ddd'>" . htmlspecialchars($c['full_name']) . "</td></tr>
                        <tr><th align='left' style='border:1px solid #ddd'>Email</th><td style='border:1px solid #ddd'>" . htmlspecialchars($c['email']) . "</td></tr>
                        <tr><th align='left' style='border:1px solid #ddd'>Phone</th><td style='border:1px solid #ddd'>" . htmlspecialchars($c['phone']) . "</td></tr>
                        <tr><th align='left' style='border:1px solid #ddd'>Company</th><td style='border:1px solid #ddd'>" . htmlspecialchars($c['company_name'] ?: '-') . "</td></tr>
                        <tr><th align='left' style='border:1px solid #ddd'>Service</th><td style='border:1px solid #ddd'>" . htmlspecialchars($c['service']) . "</td></tr>
                        <tr><th align='left' style='border:1px solid #ddd'>Subject</th><td style='border:1px solid #ddd'>" . htmlspecialchars($c['subject']) . "</td></tr>
                        <tr><th align='left' style='border:1px solid #ddd'>Message</th><td style='border:1px solid #ddd'>" . nl2br(htmlspecialchars($c['message'])) . "</td></tr>
                    </table>
                    <p>Submission ID: " . htmlspecialchars($saved['id']) . "</p>
                </body></html>";

                $adminNotificationSent = $adminSmtp->send(
                    $adminEmail,
                    'Fiktech Admin',
                    $adminSubject,
                    $adminBody,
                    $adminFromEmail,
                    $adminFromName,
                    $c['email'],
                    $c['full_name']
                );

                if (!$adminNotificationSent) {
                    error_log("Admin notification SMTP failed: " . implode(" | ", $adminSmtp->getLogs()));
                }
            } else {
                error_log('Admin notification skipped: SMTP or ADMIN_EMAIL configuration is incomplete.');
            }
        } catch (Throwable $emailEx) {
            error_log("Admin notification email failed: " . $emailEx->getMessage());
        }

        // Send an acknowledgement from the noreply mailbox to the visitor.
        try {
            $noreplySettings = get_noreply_settings();
            if (!empty($noreplySettings['smtp_host']) && !empty($noreplySettings['smtp_user'])) {
                $noreplySmtp = new SmtpClient($noreplySettings);
                $clientEmail = $c['email'];
                $clientName = $c['full_name'];
                $subj = "Pengesahan Mesej: " . $c['subject'];
                $body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; background-color: #f9f9f9; padding: 20px; }
                        .container { max-width: 600px; margin: 0 auto; background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
                        .header { background: #0A0A0A; padding: 20px; text-align: center; border-bottom: 2px solid #D4AF37; }
                        .header h2 { color: #D4AF37; margin: 0; font-size: 1.4rem; letter-spacing: 2px; }
                        .content { padding: 30px 20px; }
                        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        .table th, .table td { border: 1px solid #eee; padding: 12px; text-align: left; font-size: 0.9rem; }
                        .table th { background: #fdfdfd; width: 35%; color: #555; }
                        .footer { background: #111; color: #888; text-align: center; padding: 15px; font-size: 0.8rem; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>FIKTECH ENTERPRISE</h2>
                        </div>
                        <div class='content'>
                            <p>Salam Sejahtera <strong>" . htmlspecialchars($clientName) . "</strong>,</p>
                            <p>Terima kasih kerana menghubungi kami. Kami telah menerima pertanyaan anda dan perunding IT kami akan meneliti butiran tersebut dengan kadar segera.</p>
                            <p>Berikut adalah salinan butiran mesej yang anda hantar:</p>
                            
                            <table class='table'>
                                <tr><th>Nama</th><td>" . htmlspecialchars($clientName) . "</td></tr>
                                <tr><th>E-mel</th><td>" . htmlspecialchars($clientEmail) . "</td></tr>
                                <tr><th>No. Telefon</th><td>" . htmlspecialchars($c['phone']) . "</td></tr>
                                <tr><th>Syarikat</th><td>" . htmlspecialchars($c['company_name'] ?: '-') . "</td></tr>
                                <tr><th>Servis Diminati</th><td>" . htmlspecialchars($c['service']) . "</td></tr>
                                <tr><th>Subjek</th><td>" . htmlspecialchars($c['subject']) . "</td></tr>
                                <tr><th>Mesej</th><td>" . nl2br(htmlspecialchars($c['message'])) . "</td></tr>
                            </table>
                        </div>
                        <div class='footer'>
                            <p>&copy; 2026 FIKTECH ENTERPRISE. Hak Cipta Terpelihara.</p>
                        </div>
                    </div>
                </body>
                </html>";
                
                $fromEmail = !empty($noreplySettings['smtp_from_email']) ? $noreplySettings['smtp_from_email'] : $noreplySettings['smtp_user'];
                $fromName = !empty($noreplySettings['smtp_from_name']) ? $noreplySettings['smtp_from_name'] : 'Fiktech Auto-Response';

                $autoReplySent = $noreplySmtp->send($clientEmail, $clientName, $subj, $body, $fromEmail, $fromName);
                if (!$autoReplySent) {
                    error_log("Auto-reply SMTP failed: " . implode(" | ", $noreplySmtp->getLogs()));
                }
            } else {
                error_log('Auto-reply skipped: noreply SMTP configuration is incomplete.');
            }
        } catch (Throwable $emailEx) {
            error_log("Auto-acknowledgement email failed: " . $emailEx->getMessage());
        }

        echo json_encode([
            "status" => "success",
            "message" => "Your message has been received successfully! We will contact you soon.",
            "id" => $saved['id'],
            "email_status" => [
                "admin_notification" => $adminNotificationSent ? "sent" : "failed",
                "auto_reply" => $autoReplySent ? "sent" : "failed"
            ]
        ]);
    } else {
        throw new Exception("Unable to write file.");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "An internal server error occurred. Please try again later."
    ]);
}
