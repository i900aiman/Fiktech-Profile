<?php
/**
 * Fiktech Enterprise - Admin Login Portal
 */
require_once __DIR__ . '/../includes/config.php';

if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    
    if (!csrf_verify($csrfToken)) {
        $error = "CSRF validation failed. Please refresh and try again.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $configUser = get_env('ADMIN_USERNAME', 'admin');
        $configHash = get_env('ADMIN_PASSWORD_HASH', '');
        
        // Secure comparison using timing-safe or simple equality for username, and password verification
        if ($username === $configUser && !empty($configHash) && password_verify($password, $configHash)) {
            // Prevent session fixation
            regenerate_session();
            
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $username;
            
            header('Location: dashboard.php');
            exit;
        } else {
            // Delay slightly to mitigate brute-force
            usleep(250000); // 250ms delay
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | FIKTECH</title>
    
    <!-- CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../static/css/admin.css">
    
    <link rel="icon" type="image/x-icon" href="../static/favicon.ico">
</head>
<body class="login-body">

    <div class="login-card">
        <div class="login-title">
            <i class="fas fa-key" style="color: var(--accent-gold); font-size: 1.8rem; margin-bottom: 10px; display: block;"></i>
            FIKTECH <span>PORTAL</span>
        </div>
        
        <!-- Error output -->
        <?php if ($error): ?>
        <div class="login-error">
            <i class="fas fa-circle-exclamation" style="margin-right: 5px;"></i> <?= e($error) ?>
        </div>
        <?php endif; ?>
        
        <form action="login.php" method="POST" autocomplete="off">
            <!-- CSRF Token protection -->
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            
            <div class="login-form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="login-control" placeholder="Masukkan username" required autofocus>
            </div>
            
            <div class="login-form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="login-control" placeholder="Masukkan kata laluan" required>
            </div>
            
            <button type="submit" class="login-btn">LOG MASUK <i class="fas fa-sign-in-alt" style="margin-left: 8px;"></i></button>
        </form>
    </div>

</body>
</html>
