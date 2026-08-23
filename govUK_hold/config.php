<?php
// config.php - FIXED WITH CORRECT NAMESPACE

ob_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'govuk_evisas');
define('DB_USER', 'sfp');
define('DB_PASS', 'Sfp@072015');

// SMTP Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'studyexplora69@gmail.com');
define('SMTP_PASS', 'jcdh zmgo cmwc qugq');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_FROM_EMAIL', 'evisas-no-reply@homeoffice.gov.uk');
define('SMTP_FROM_NAME', 'GOV.UK eVisas');
define('SMTP_REPLY_TO', 'evisas.support@homeoffice.gov.uk');

// File upload settings
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5242880);
define('ALLOWED_FILE_TYPES', ['image/jpeg', 'image/png']);

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include PHPMailer with FULL NAMESPACE PATH
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

// Database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>