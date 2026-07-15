<?php
/**
 * Fiktech Enterprise - AJAX Status Update Handler (admin/api_status.php)
 * Authenticates admin session, verifies CSRF token, and updates status of contact.
 */
header('Content-Type: application/json');

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/contact_storage.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed."]);
    exit;
}

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
    echo json_encode(["status" => "error", "message" => "CSRF validation failed."]);
    exit;
}

$id = $_POST['id'] ?? '';
$status = $_POST['status'] ?? '';

if (empty($id) || !in_array($status, ['new', 'read'], true)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid parameters."]);
    exit;
}

$success = update_incoming_status($id, $status);
if ($success) {
    echo json_encode(["status" => "success", "message" => "Status updated successfully."]);
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Submission not found."]);
}
