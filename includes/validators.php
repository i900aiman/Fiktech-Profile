<?php
/**
 * Fiktech Enterprise - Input Validation Helper
 */

const VALID_SERVICES = [
    "Website Design & Development",
    "Mobile Application Development",
    "Custom Web Application",
    "IT Support & Maintenance"
];

function clean_input($val) {
    if ($val === null || !is_scalar($val)) {
        return "";
    }
    return trim(htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8'));
}

function validate_contact_form($data) {
    $errors = [];
    $cleaned = [];
    
    // 1. Full Name (Required, 3-100 chars)
    $fullName = clean_input($data['full_name'] ?? '');
    if (empty($fullName)) {
        $errors['full_name'] = "Full name is required.";
    } elseif (strlen($fullName) < 3 || strlen($fullName) > 100) {
        $errors['full_name'] = "Full name must be between 3 and 100 characters.";
    } else {
        $cleaned['full_name'] = $fullName;
    }
    
    // 2. Email Address (Required, valid format, max 100 chars)
    $email = clean_input($data['email'] ?? '');
    if (empty($email)) {
        $errors['email'] = "Email address is required.";
    } elseif (strlen($email) > 100) {
        $errors['email'] = "Email must not exceed 100 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address.";
    } else {
        $cleaned['email'] = strtolower($email);
    }
    
    // 3. Phone Number (Required, valid format, max 20 chars)
    $phone = clean_input($data['phone'] ?? '');
    $phoneRegex = '/^\+?[0-9\s\-()]{7,20}$/';
    if (empty($phone)) {
        $errors['phone'] = "Phone number is required.";
    } elseif (strlen($phone) > 20) {
        $errors['phone'] = "Phone number must not exceed 20 characters.";
    } elseif (!preg_match($phoneRegex, $phone)) {
        $errors['phone'] = "Please enter a valid phone number.";
    } else {
        $cleaned['phone'] = $phone;
    }
    
    // 4. Company Name (Optional, max 100 chars)
    $companyName = clean_input($data['company_name'] ?? '');
    if (!empty($companyName) && strlen($companyName) > 100) {
        $errors['company_name'] = "Company name must not exceed 100 characters.";
    } else {
        $cleaned['company_name'] = $companyName;
    }
    
    // 5. Subject (Required, 3-150 chars)
    $subject = clean_input($data['subject'] ?? '');
    if (empty($subject)) {
        $errors['subject'] = "Subject is required.";
    } elseif (strlen($subject) < 3 || strlen($subject) > 150) {
        $errors['subject'] = "Subject must be between 3 and 150 characters.";
    } else {
        $cleaned['subject'] = $subject;
    }
    
    // 6. Service Interested (Required, must be one of the pre-defined services)
    $service = clean_input($data['service'] ?? '');
    if (empty($service)) {
        $errors['service'] = "Please select a service of interest.";
    } elseif (!in_array($service, VALID_SERVICES, true)) {
        $errors['service'] = "Invalid service selected.";
    } else {
        $cleaned['service'] = $service;
    }
    
    // 7. Message (Required, 10-2000 chars)
    $message = clean_input($data['message'] ?? '');
    if (empty($message)) {
        $errors['message'] = "Message content is required.";
    } elseif (strlen($message) < 10 || strlen($message) > 2000) {
        $errors['message'] = "Message must be between 10 and 2000 characters.";
    } else {
        $cleaned['message'] = $message;
    }
    
    // 8. Consent Checkbox (Required)
    $consent = $data['consent'] ?? null;
    if ($consent === null || !in_array(strtolower($consent), ['on', 'true', '1'], true)) {
        $errors['consent'] = "You must consent to being contacted.";
    } else {
        $cleaned['consent'] = true;
    }
    
    return [
        'is_valid' => count($errors) === 0,
        'errors' => $errors,
        'cleaned' => $cleaned
    ];
}
