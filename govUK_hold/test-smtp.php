<?php
// test-gmail.php - Simple SMTP Test

// Include PHPMailer directly
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<h3>Testing Gmail SMTP</h3>";
echo "<pre>";

try {
    $mail = new PHPMailer(true);
    
    // Enable verbose debug output
    $mail->SMTPDebug = 3; // Full debug output
    $mail->Debugoutput = function($str, $level) {
        echo "[$level] $str\n";
    };
    
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'studyexplora69@gmail.com';
    $mail->Password = 'jcdh zmgo cmwc qugq';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    // Timeout
    $mail->Timeout = 30;
    
    // From and To
    $mail->setFrom('evisas-no-reply@homeoffice.gov.uk', 'GOV.UK eVisas');
    $mail->addAddress('studyexplora69@gmail.com', 'Test User');
    $mail->addReplyTo('evisas.support@homeoffice.gov.uk', 'Support');
    
    // Content
    $mail->isHTML(false);
    $mail->Subject = 'Test from PHPMailer';
    $mail->Body = 'This is a test email from PHPMailer.';
    
    echo "Attempting to send...\n";
    
    if ($mail->send()) {
        echo "\n✅ SUCCESS: Email sent!\n";
    } else {
        echo "\n❌ FAILED: " . $mail->ErrorInfo . "\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ EXCEPTION: " . $e->getMessage() . "\n";
}

echo "\n</pre>";
?>