<?php
// PHP Processing Code - FULLY UPDATED WITH SMTP
require_once 'config.php';

// Initialize variables
$errors = [];
$success = false;
$applicationNumber = '';
$submittedData = [];
$emailSent = false;

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $mobileNumber = trim($_POST['mobile_number'] ?? '');
    $panNumber = trim($_POST['pan_number'] ?? '');
    $passportNumber = trim($_POST['passport_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dateOfBirth = $_POST['date_of_birth'] ?? '';
    $nationality = $_POST['nationality'] ?? '';
    $visaType = $_POST['visa_type'] ?? '';
    $visaIssueDate = $_POST['visa_issue_date'] ?? '';
    $visaExpiryDate = $_POST['visa_expiry_date'] ?? '';
    $purposeOfVisit = trim($_POST['purpose_of_visit'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postalCode = trim($_POST['postal_code'] ?? '');
    $country = $_POST['country'] ?? '';
    $emergencyContact = trim($_POST['emergency_contact'] ?? '');
    $emergencyPhone = trim($_POST['emergency_phone'] ?? '');
    $declaration = isset($_POST['declaration']) ? true : false;
    $dataProtection = isset($_POST['data_protection']) ? true : false;
    
    // Store submitted data for re-populating form
    $submittedData = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'mobile_number' => $mobileNumber,
        'pan_number' => $panNumber,
        'passport_number' => $passportNumber,
        'email' => $email,
        'date_of_birth' => $dateOfBirth,
        'nationality' => $nationality,
        'visa_type' => $visaType,
        'visa_issue_date' => $visaIssueDate,
        'visa_expiry_date' => $visaExpiryDate,
        'purpose_of_visit' => $purposeOfVisit,
        'address' => $address,
        'city' => $city,
        'postal_code' => $postalCode,
        'country' => $country,
        'emergency_contact' => $emergencyContact,
        'emergency_phone' => $emergencyPhone
    ];
    
    // Validation
    if (empty($firstName)) $errors[] = 'First name is required';
    if (empty($lastName)) $errors[] = 'Last name is required';
    if (empty($mobileNumber)) $errors[] = 'Mobile number is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (empty($passportNumber)) $errors[] = 'Passport number is required';
    if (empty($dateOfBirth)) $errors[] = 'Date of birth is required';
    if (empty($nationality)) $errors[] = 'Nationality is required';
    if (empty($visaType)) $errors[] = 'Visa type is required';
    if (empty($visaIssueDate)) $errors[] = 'Visa issue date is required';
    if (empty($visaExpiryDate)) $errors[] = 'Visa expiry date is required';
    if (empty($purposeOfVisit)) $errors[] = 'Purpose of visit is required';
    if (empty($address)) $errors[] = 'Address is required';
    if (empty($city)) $errors[] = 'City is required';
    if (empty($postalCode)) $errors[] = 'Postal code is required';
    if (empty($country)) $errors[] = 'Country is required';
    if (empty($emergencyContact)) $errors[] = 'Emergency contact name is required';
    if (empty($emergencyPhone)) $errors[] = 'Emergency contact phone is required';
    if (!$declaration) $errors[] = 'You must agree to the declaration';
    if (!$dataProtection) $errors[] = 'You must agree to the data protection policy';
    
    // Validate PAN number format if provided
    if (!empty($panNumber) && !preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $panNumber)) {
        $errors[] = 'Invalid PAN number format';
    }
    
    // Validate dates
    if (!empty($dateOfBirth)) {
        $dob = new DateTime($dateOfBirth);
        $today = new DateTime();
        $age = $today->diff($dob)->y;
        if ($age < 18) {
            $errors[] = 'You must be at least 18 years old';
        }
    }
    
    if (!empty($visaIssueDate) && !empty($visaExpiryDate)) {
        if (strtotime($visaExpiryDate) <= strtotime($visaIssueDate)) {
            $errors[] = 'Visa expiry date must be after issue date';
        }
    }
    
    // Handle file upload
    $photoPath = '';
    if (isset($_FILES['passport_photo']) && $_FILES['passport_photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['passport_photo']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ALLOWED_FILE_TYPES;
            $maxSize = MAX_FILE_SIZE;
            
            $fileType = $_FILES['passport_photo']['type'];
            $fileSize = $_FILES['passport_photo']['size'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = 'Only JPEG and PNG files are allowed';
            } elseif ($fileSize > $maxSize) {
                $errors[] = 'File size must be less than 5MB';
            } else {
                $uploadDir = UPLOAD_DIR;
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = pathinfo($_FILES['passport_photo']['name'], PATHINFO_EXTENSION);
                $fileName = 'evisa_' . time() . '_' . uniqid() . '.' . $fileExtension;
                $photoPath = $uploadDir . $fileName;
                
                if (!move_uploaded_file($_FILES['passport_photo']['tmp_name'], $photoPath)) {
                    $errors[] = 'Failed to upload photo';
                }
            }
        } else {
            $errors[] = 'Error uploading photo: ' . $_FILES['passport_photo']['error'];
        }
    } else {
        $errors[] = 'Passport photo is required';
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        try {
            // Generate application number
            $applicationNumber = 'EV' . date('Ymd') . strtoupper(substr(uniqid(), 7, 6));
            
            // Insert into database
            $stmt = $pdo->prepare("
                INSERT INTO evisa_registrations 
                (
                    first_name, last_name, mobile_number, pan_number, passport_number, 
                    email, photo_path, application_number, status, date_of_birth, 
                    nationality, visa_type, visa_issue_date, visa_expiry_date, 
                    purpose_of_visit, address, city, postal_code, country, 
                    emergency_contact, emergency_phone, registration_date
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $firstName, 
                $lastName, 
                $mobileNumber, 
                $panNumber, 
                $passportNumber, 
                $email, 
                $photoPath,
                $applicationNumber,
                $dateOfBirth,
                $nationality,
                $visaType,
                $visaIssueDate,
                $visaExpiryDate,
                $purposeOfVisit,
                $address,
                $city,
                $postalCode,
                $country,
                $emergencyContact,
                $emergencyPhone
            ]);
            
            $success = true;
            
            // Send confirmation email using SMTP
            $emailSent = sendConfirmationEmail($email, $firstName . ' ' . $lastName, $applicationNumber, $passportNumber);
            
            // Store success data in session
            $_SESSION['registration_success'] = true;
            $_SESSION['application_number'] = $applicationNumber;
            $_SESSION['passport_number'] = $passportNumber;
            $_SESSION['email'] = $email;
            $_SESSION['email_sent'] = $emailSent;
            
            // Clear form data after successful submission
            $submittedData = [];
            
            // Redirect to show alert
            header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
            exit;
            
        } catch (PDOException $e) {
            // Delete uploaded file if database insertion fails
            if (!empty($photoPath) && file_exists($photoPath)) {
                unlink($photoPath);
            }
            
            if ($e->getCode() == 23000) { // Duplicate entry
                $errors[] = 'This passport number or email is already registered';
            } else {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Check for success in session
if (isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true) {
    $success = true;
    $applicationNumber = $_SESSION['application_number'] ?? '';
    $passportNumber = $_SESSION['passport_number'] ?? '';
    $email = $_SESSION['email'] ?? '';
    $emailSent = $_SESSION['email_sent'] ?? false;
    
    // Clear session data
    unset($_SESSION['registration_success']);
    unset($_SESSION['application_number']);
    unset($_SESSION['passport_number']);
    unset($_SESSION['email']);
    unset($_SESSION['email_sent']);
}


// Function to send confirmation email - FIXED VERSION
function sendConfirmationEmail($to, $name, $appNumber, $passportNumber) {
    try {
        // IMPORTANT: Use FULL namespace
        require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/PHPMailer/src/Exception.php';
        require_once __DIR__ . '/PHPMailer/src/SMTP.php';
        
        // Create instance using FULL namespace
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // OR use alias
        // use PHPMailer\PHPMailer\PHPMailer;
        // use PHPMailer\PHPMailer\Exception;
        // $mail = new PHPMailer(true);
        
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        // Debug OFF
        $mail->SMTPDebug = 0;
        
        // From and To
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to, $name);
        $mail->addReplyTo(SMTP_REPLY_TO, 'eVisas Support');
        
        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Your eVisas Registration - Application #$appNumber";
        
        $verificationLink = "http://" . $_SERVER['HTTP_HOST'] . "/govUK/view-evisa-verify.php";
        $currentDate = date('d/m/Y H:i:s');
        
        // Simple HTML email
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; }
        .header { background: #005ea5; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f8f8; }
        .details { background: white; padding: 15px; border-left: 4px solid #005ea5; margin: 15px 0; }
        .button { background: #005ea5; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>GOV.UK eVisas Registration</h2>
        </div>
        <div class="content">
            <p>Dear $name,</p>
            <p>Thank you for registering for your online immigration status (eVisas).</p>
            
            <div class="details">
                <h3>Application Details</h3>
                <p><strong>Application Number:</strong> $appNumber</p>
                <p><strong>Passport Number:</strong> $passportNumber</p>
                <p><strong>Date:</strong> $currentDate</p>
            </div>
            
            <p>Please verify your application:</p>
            <p><a href="$verificationLink" class="button">Verify Application</a></p>
            
            <p>Need help? Contact: evisas.support@homeoffice.gov.uk</p>
        </div>
    </div>
</body>
</html>
HTML;

        $mail->Body = $html;
        
        // Plain text version
        $mail->AltBody = "Dear $name,\n\nYour eVisas application $appNumber has been received.\nPassport: $passportNumber\n\nVerify at: $verificationLink";
        
        // Send email
        if ($mail->send()) {
            // Log success
            $log = "[" . date('Y-m-d H:i:s') . "] Email sent to: $to | App: $appNumber\n";
            file_put_contents('email_success.log', $log, FILE_APPEND);
            return true;
        }
        
        return false;
        
    } catch (Exception $e) {
        // Log error
        $error = "[" . date('Y-m-d H:i:s') . "] Email error to $to: " . $e->getMessage() . "\n";
        file_put_contents('email_errors.log', $error, FILE_APPEND);
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eVisas: Access and Use Your Online Immigration Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... पहले वाला CSS code रहेगा ... */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f3f2f1;
            color: #0b0c0c;
            line-height: 1.5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header styles matching GOV.UK */
        .govuk-header {
            background-color: #0b0c0c;
            color: white;
            padding: 10px 0;
            border-bottom: 5px solid #1d70b8;
        }
        
        .govuk-header__container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .govuk-header__logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .govuk-header__logotype {
            font-size: 28px;
            font-weight: bold;
        }
        
        .govuk-header__logotype a {
            color: white;
            text-decoration: none;
        }
        
        .govuk-breadcrumb {
            background-color: #f3f2f1;
            padding: 15px 0;
            border-bottom: 1px solid #b1b4b6;
        }
        
        .govuk-breadcrumb__list {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
        }
        
        .govuk-breadcrumb__list-item {
            margin-right: 10px;
        }
        
        .govuk-breadcrumb__list-item:after {
            content: "›";
            margin-left: 10px;
            color: #505a5f;
        }
        
        .govuk-breadcrumb__list-item:last-child:after {
            content: "";
        }
        
        /* Main content */
        .govuk-main {
            padding: 30px 0;
            background-color: white;
        }
        
        .govuk-heading-xl {
            font-size: 48px;
            margin-bottom: 20px;
            color: #0b0c0c;
        }
        
        .govuk-heading-l {
            font-size: 36px;
            margin-bottom: 20px;
            color: #0b0c0c;
        }
        
        .govuk-heading-m {
            font-size: 24px;
            margin-bottom: 15px;
            color: #0b0c0c;
        }
        
        .govuk-body {
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        /* Form styles */
        .govuk-form-group {
            margin-bottom: 20px;
        }
        
        .govuk-label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 19px;
        }
        
        .govuk-hint {
            color: #505a5f;
            font-size: 14px;
            margin-bottom: 10px;
            display: block;
        }
        
        .govuk-input {
            width: 100%;
            padding: 10px;
            border: 2px solid #0b0c0c;
            font-size: 16px;
            max-width: 500px;
        }
        
        .govuk-input:focus {
            outline: 3px solid #ffdd00;
            outline-offset: 0;
        }
        
        .govuk-select {
            width: 100%;
            padding: 10px;
            border: 2px solid #0b0c0c;
            font-size: 16px;
            max-width: 500px;
            background-color: white;
        }
        
        .govuk-textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #0b0c0c;
            font-size: 16px;
            max-width: 500px;
            min-height: 100px;
            resize: vertical;
        }
        
        .govuk-file-upload {
            border: 2px dashed #1d70b8;
            padding: 30px;
            text-align: center;
            background-color: #f8f8f8;
            margin-top: 10px;
        }
        
        .govuk-button {
            background-color: #1d70b8;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 19px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            margin-right: 10px;
        }
        
        .govuk-button:hover {
            background-color: #0b4e8a;
        }
        
        .govuk-button--secondary {
            background-color: #f3f2f1;
            color: #0b0c0c;
            border: 2px solid #0b0c0c;
        }
        
        .govuk-button--secondary:hover {
            background-color: #dde0e2;
        }
        
        /* Success message */
        .govuk-success {
            background-color: #cce2d8;
            border-left: 8px solid #00703c;
            padding: 20px;
            margin: 30px 0;
        }
        
        .govuk-success__title {
            color: #00703c;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        /* Grid layout */
        .govuk-grid-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        
        .govuk-grid-column-two-thirds {
            width: 66.66%;
            padding: 0 15px;
        }
        
        .govuk-grid-column-one-third {
            width: 33.33%;
            padding: 0 15px;
        }
        
        /* Panel */
        .govuk-panel {
            background-color: #1d70b8;
            color: white;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }
        
        .govuk-panel__title {
            font-size: 36px;
            margin-bottom: 15px;
        }
        
        .govuk-panel__body {
            font-size: 24px;
        }
        
        /* Requirements box */
        .govuk-inset-text {
            border-left: 10px solid #1d70b8;
            padding: 15px;
            background-color: #f3f2f1;
            margin: 30px 0;
        }
        
        /* Footer */
        .govuk-footer {
            background-color: #0b0c0c;
            color: white;
            padding: 30px 0;
            margin-top: 40px;
        }
        
        .govuk-footer__copyright {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #505a5f;
            color: #b1b4b6;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .govuk-grid-column-two-thirds,
            .govuk-grid-column-one-third {
                width: 100%;
            }
            
            .govuk-heading-xl {
                font-size: 36px;
            }
            
            .govuk-heading-l {
                font-size: 28px;
            }
            
            .govuk-heading-m {
                font-size: 20px;
            }
        }
        
        /* Photo preview */
        .photo-preview {
            margin-top: 10px;
            max-width: 200px;
            border: 2px solid #b1b4b6;
            display: none;
        }
        
        /* Error message */
        .govuk-error {
            color: #d4351c;
            font-weight: bold;
            margin-top: 5px;
            display: block;
            font-size: 14px;
        }
        
        /* Form sections */
        .form-section {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #b1b4b6;
        }
        
        .form-section:last-child {
            border-bottom: none;
        }
        
        /* Checkbox styles */
        .govuk-checkboxes__item {
            margin-bottom: 10px;
        }
        
        .govuk-checkboxes__input {
            margin-right: 10px;
            transform: scale(1.2);
        }
        
        .govuk-checkboxes__label {
            font-size: 16px;
        }
        
        /* Visa type options */
        .visa-options {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        
        .visa-option {
            padding: 10px;
            border: 2px solid #b1b4b6;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .visa-option.selected {
            border-color: #1d70b8;
            background-color: #f3f2f1;
        }
        
        .visa-option input {
            margin-right: 5px;
        }
        
        /* Error states */
        .error-field {
            border-color: #d4351c !important;
        }
        
        .error-message {
            color: #d4351c;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }
        
        .success-container {
            background-color: #cce2d8;
            border: 2px solid #00703c;
            padding: 30px;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <!-- JavaScript for success alert -->
    <?php if ($success && isset($_GET['success'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var message = "✅ Registration Successful!\n\n";
            message += "Application Number: <?php echo $applicationNumber; ?>\n";
            message += "Passport Number: <?php echo $passportNumber; ?>\n\n";
            
            <?php if ($emailSent): ?>
            message += "📧 Confirmation email has been sent to:\n";
            message += "<?php echo $email; ?>\n\n";
            message += "Please check your inbox (and spam folder).";
            <?php else: ?>
            message += "⚠️ Email could not be sent.\n";
            message += "Please note your application number for reference.";
            <?php endif; ?>
            
            var result = confirm(message);
            
            if (result) {
                window.location.href = 'view-evisa-verify.php';
            }
        });
    </script>
    <?php endif; ?>
    
   
    <header class="govuk-header">
        <div class="container govuk-header__container">
            <div class="govuk-header__logo">
                <div class="govuk-header__logotype">
                    <a href="index.html">GOV.UK</a>
                </div>
            </div>
            <div class="govuk-header__content">
                <span>Welcome to GOV.UK</span>
            </div>
        </div>
    </header>

    <!-- Breadcrumb -->
    <nav class="govuk-breadcrumb">
        <div class="container">
            <ol class="govuk-breadcrumb__list">
                <li class="govuk-breadcrumb__list-item">
                    <a href="index.html">Home</a>
                </li>
                <li class="govuk-breadcrumb__list-item">
                    <a href="#">Visas and immigration</a>
                </li>
                <li class="govuk-breadcrumb__list-item" aria-current="page">
                    eVisas registration
                </li>
            </ol>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="govuk-main">
        <div class="container">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-two-thirds">
                    <h1 class="govuk-heading-xl">eVisas: Access and Use Your Online Immigration Status</h1>
                    
                    <div class="govuk-inset-text">
                        <p class="govuk-body"><strong>Before you start:</strong></p>
                        <ul>
                            <li>You'll need a valid passport</li>
                            <li>Have your PAN card ready (if applicable)</li>
                            <li>Prepare a passport-sized photo (JPEG or PNG, max 5MB)</li>
                            <li>This service usually takes about 10-15 minutes</li>
                        </ul>
                    </div>
                    
                    <?php if ($success && isset($_GET['success'])): ?>
                    <!-- Success Message -->
                    <div class="govuk-panel">
                        <h1 class="govuk-panel__title">
                            Registration Submitted!
                        </h1>
                        <div class="govuk-panel__body">
                            Your application number<br>
                            <strong><?php echo htmlspecialchars($applicationNumber); ?></strong>
                        </div>
                        <p class="govuk-body" style="margin-top: 20px; color: white;">
                            ✅ Your registration has been successfully submitted.<br>
                            📧 Verification details have been sent to: <?php echo htmlspecialchars($email); ?><br>
                            🔍 Check your email for application verification link.
                        </p>
                        <div style="margin-top: 30px;">
                            <a href="view-evisa-verify.php" class="govuk-button">Verify Your Application Now</a>
                            <a href="evisa-registration.php" class="govuk-button govuk-button--secondary">Submit Another Application</a>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    
                    <h2 class="govuk-heading-l">Register for eVisas</h2>
                    <p class="govuk-body">Complete this form to register for your online immigration status.</p>
                    
                    <!-- Display Errors -->
                    <?php if (!empty($errors)): ?>
                    <div class="govuk-error-summary" style="background-color: #fbeaea; border-left: 8px solid #d4351c; padding: 20px; margin-bottom: 30px;">
                        <h3 class="govuk-error-summary__title" style="color: #d4351c;">
                            Please fix the following errors:
                        </h3>
                        <ul class="govuk-list govuk-error-summary__list">
                            <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Registration Form -->
                    <form method="POST" action="" enctype="multipart/form-data">
                        <!-- Personal Details Section -->
                        <div class="form-section">
                            <h3 class="govuk-heading-m">Personal details</h3>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="first-name">
                                    First name *
                                </label>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['first_name'])) ? 'error-field' : ''; ?>" 
                                       id="first-name" name="first_name" type="text" 
                                       value="<?php echo htmlspecialchars($submittedData['first_name'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['first_name'])): ?>
                                <span class="error-message">First name is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="last-name">
                                    Last name *
                                </label>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['last_name'])) ? 'error-field' : ''; ?>" 
                                       id="last-name" name="last_name" type="text" 
                                       value="<?php echo htmlspecialchars($submittedData['last_name'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['last_name'])): ?>
                                <span class="error-message">Last name is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="date-of-birth">
                                    Date of birth *
                                </label>
                                <span class="govuk-hint">Format: YYYY-MM-DD</span>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['date_of_birth'])) ? 'error-field' : ''; ?>" 
                                       id="date-of-birth" name="date_of_birth" type="date" 
                                       value="<?php echo htmlspecialchars($submittedData['date_of_birth'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['date_of_birth'])): ?>
                                <span class="error-message">Date of birth is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="nationality">
                                    Nationality *
                                </label>
                                <select class="govuk-select <?php echo (!empty($errors) && empty($submittedData['nationality'])) ? 'error-field' : ''; ?>" 
                                        id="nationality" name="nationality" required>
                                    <option value="">Select your nationality</option>
                                    <option value="Indian" <?php echo ($submittedData['nationality'] ?? '') == 'Indian' ? 'selected' : ''; ?>>Indian</option>
                                    <option value="British" <?php echo ($submittedData['nationality'] ?? '') == 'British' ? 'selected' : ''; ?>>British</option>
                                    <option value="American" <?php echo ($submittedData['nationality'] ?? '') == 'American' ? 'selected' : ''; ?>>American</option>
                                    <option value="Australian" <?php echo ($submittedData['nationality'] ?? '') == 'Australian' ? 'selected' : ''; ?>>Australian</option>
                                    <option value="Canadian" <?php echo ($submittedData['nationality'] ?? '') == 'Canadian' ? 'selected' : ''; ?>>Canadian</option>
                                    <option value="Chinese" <?php echo ($submittedData['nationality'] ?? '') == 'Chinese' ? 'selected' : ''; ?>>Chinese</option>
                                    <option value="French" <?php echo ($submittedData['nationality'] ?? '') == 'French' ? 'selected' : ''; ?>>French</option>
                                    <option value="German" <?php echo ($submittedData['nationality'] ?? '') == 'German' ? 'selected' : ''; ?>>German</option>
                                    <option value="Japanese" <?php echo ($submittedData['nationality'] ?? '') == 'Japanese' ? 'selected' : ''; ?>>Japanese</option>
                                    <option value="Other" <?php echo ($submittedData['nationality'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                                <?php if (!empty($errors) && empty($submittedData['nationality'])): ?>
                                <span class="error-message">Nationality is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="mobile-number">
                                    Mobile number *
                                </label>
                                <span class="govuk-hint">Include country code, e.g., +91 for India</span>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['mobile_number'])) ? 'error-field' : ''; ?>" 
                                       id="mobile-number" name="mobile_number" type="tel" 
                                       value="<?php echo htmlspecialchars($submittedData['mobile_number'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['mobile_number'])): ?>
                                <span class="error-message">Mobile number is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="email">
                                    Email address *
                                </label>
                                <span class="govuk-hint">We'll send your confirmation to this address</span>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['email'])) ? 'error-field' : ''; ?>" 
                                       id="email" name="email" type="email" 
                                       value="<?php echo htmlspecialchars($submittedData['email'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['email'])): ?>
                                <span class="error-message">Valid email is required</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Address Details Section -->
                        <div class="form-section">
                            <h3 class="govuk-heading-m">Address details</h3>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="address">
                                    Full address *
                                </label>
                                <textarea class="govuk-textarea <?php echo (!empty($errors) && empty($submittedData['address'])) ? 'error-field' : ''; ?>" 
                                          id="address" name="address" rows="4" required><?php echo htmlspecialchars($submittedData['address'] ?? ''); ?></textarea>
                                <?php if (!empty($errors) && empty($submittedData['address'])): ?>
                                <span class="error-message">Address is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="city">
                                    City *
                                </label>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['city'])) ? 'error-field' : ''; ?>" 
                                       id="city" name="city" type="text" 
                                       value="<?php echo htmlspecialchars($submittedData['city'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['city'])): ?>
                                <span class="error-message">City is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="postal-code">
                                    Postal code *
                                </label>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['postal_code'])) ? 'error-field' : ''; ?>" 
                                       id="postal-code" name="postal_code" type="text" 
                                       value="<?php echo htmlspecialchars($submittedData['postal_code'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['postal_code'])): ?>
                                <span class="error-message">Postal code is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="country">
                                    Country *
                                </label>
                                <select class="govuk-select <?php echo (!empty($errors) && empty($submittedData['country'])) ? 'error-field' : ''; ?>" 
                                        id="country" name="country" required>
                                    <option value="">Select your country</option>
                                    <option value="India" <?php echo ($submittedData['country'] ?? '') == 'India' ? 'selected' : ''; ?>>India</option>
                                    <option value="United Kingdom" <?php echo ($submittedData['country'] ?? '') == 'United Kingdom' ? 'selected' : ''; ?>>United Kingdom</option>
                                    <option value="United States" <?php echo ($submittedData['country'] ?? '') == 'United States' ? 'selected' : ''; ?>>United States</option>
                                    <option value="Australia" <?php echo ($submittedData['country'] ?? '') == 'Australia' ? 'selected' : ''; ?>>Australia</option>
                                    <option value="Canada" <?php echo ($submittedData['country'] ?? '') == 'Canada' ? 'selected' : ''; ?>>Canada</option>
                                    <option value="China" <?php echo ($submittedData['country'] ?? '') == 'China' ? 'selected' : ''; ?>>China</option>
                                    <option value="France" <?php echo ($submittedData['country'] ?? '') == 'France' ? 'selected' : ''; ?>>France</option>
                                    <option value="Germany" <?php echo ($submittedData['country'] ?? '') == 'Germany' ? 'selected' : ''; ?>>Germany</option>
                                    <option value="Japan" <?php echo ($submittedData['country'] ?? '') == 'Japan' ? 'selected' : ''; ?>>Japan</option>
                                    <option value="Other" <?php echo ($submittedData['country'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                                <?php if (!empty($errors) && empty($submittedData['country'])): ?>
                                <span class="error-message">Country is required</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Document Details Section -->
                        <div class="form-section">
                            <h3 class="govuk-heading-m">Document details</h3>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="pan-number">
                                    PAN number (if applicable)
                                </label>
                                <span class="govuk-hint">Permanent Account Number (10 characters)</span>
                                <input class="govuk-input" id="pan-number" name="pan_number" type="text" 
                                       pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" maxlength="10"
                                       value="<?php echo htmlspecialchars($submittedData['pan_number'] ?? ''); ?>">
                                <?php if (in_array('Invalid PAN number format', $errors)): ?>
                                <span class="error-message">Invalid PAN number format (5 letters, 4 numbers, 1 letter)</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="passport-number">
                                    Passport number *
                                </label>
                                <span class="govuk-hint">Enter exactly as shown in your passport</span>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['passport_number'])) ? 'error-field' : ''; ?>" 
                                       id="passport-number" name="passport_number" type="text" 
                                       value="<?php echo htmlspecialchars($submittedData['passport_number'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['passport_number'])): ?>
                                <span class="error-message">Passport number is required</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Visa Information Section -->
                        <div class="form-section">
                            <h3 class="govuk-heading-m">Visa information</h3>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label">
                                    Visa type *
                                </label>
                                <div class="visa-options">
                                    <?php $selectedVisa = $submittedData['visa_type'] ?? ''; ?>
                                    <div class="visa-option <?php echo $selectedVisa == 'Tourist' ? 'selected' : ''; ?>">
                                        <input type="radio" id="visa-tourist" name="visa_type" value="Tourist" 
                                               <?php echo $selectedVisa == 'Tourist' ? 'checked' : ''; ?> required>
                                        <label for="visa-tourist">Tourist</label>
                                    </div>
                                    <div class="visa-option <?php echo $selectedVisa == 'Business' ? 'selected' : ''; ?>">
                                        <input type="radio" id="visa-business" name="visa_type" value="Business"
                                               <?php echo $selectedVisa == 'Business' ? 'checked' : ''; ?>>
                                        <label for="visa-business">Business</label>
                                    </div>
                                    <div class="visa-option <?php echo $selectedVisa == 'Student' ? 'selected' : ''; ?>">
                                        <input type="radio" id="visa-student" name="visa_type" value="Student"
                                               <?php echo $selectedVisa == 'Student' ? 'checked' : ''; ?>>
                                        <label for="visa-student">Student</label>
                                    </div>
                                    <div class="visa-option <?php echo $selectedVisa == 'Work' ? 'selected' : ''; ?>">
                                        <input type="radio" id="visa-work" name="visa_type" value="Work"
                                               <?php echo $selectedVisa == 'Work' ? 'checked' : ''; ?>>
                                        <label for="visa-work">Work</label>
                                    </div>
                                    <div class="visa-option <?php echo $selectedVisa == 'Transit' ? 'selected' : ''; ?>">
                                        <input type="radio" id="visa-transit" name="visa_type" value="Transit"
                                               <?php echo $selectedVisa == 'Transit' ? 'checked' : ''; ?>>
                                        <label for="visa-transit">Transit</label>
                                    </div>
                                </div>
                                <?php if (!empty($errors) && empty($submittedData['visa_type'])): ?>
                                <span class="error-message">Visa type is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="visa-issue-date">
                                    Visa issue date *
                                </label>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['visa_issue_date'])) ? 'error-field' : ''; ?>" 
                                       id="visa-issue-date" name="visa_issue_date" type="date" 
                                       value="<?php echo htmlspecialchars($submittedData['visa_issue_date'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['visa_issue_date'])): ?>
                                <span class="error-message">Visa issue date is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="visa-expiry-date">
                                    Visa expiry date *
                                </label>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['visa_expiry_date'])) ? 'error-field' : ''; ?>" 
                                       id="visa-expiry-date" name="visa_expiry_date" type="date" 
                                       value="<?php echo htmlspecialchars($submittedData['visa_expiry_date'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['visa_expiry_date'])): ?>
                                <span class="error-message">Visa expiry date is required</span>
                                <?php endif; ?>
                                <?php if (in_array('Visa expiry date must be after issue date', $errors)): ?>
                                <span class="error-message">Visa expiry date must be after issue date</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="purpose-of-visit">
                                    Purpose of visit *
                                </label>
                                <textarea class="govuk-textarea <?php echo (!empty($errors) && empty($submittedData['purpose_of_visit'])) ? 'error-field' : ''; ?>" 
                                          id="purpose-of-visit" name="purpose_of_visit" rows="4" required><?php echo htmlspecialchars($submittedData['purpose_of_visit'] ?? ''); ?></textarea>
                                <?php if (!empty($errors) && empty($submittedData['purpose_of_visit'])): ?>
                                <span class="error-message">Purpose of visit is required</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Emergency Contact Section -->
                        <div class="form-section">
                            <h3 class="govuk-heading-m">Emergency contact</h3>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="emergency-contact">
                                    Emergency contact name *
                                </label>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['emergency_contact'])) ? 'error-field' : ''; ?>" 
                                       id="emergency-contact" name="emergency_contact" type="text" 
                                       value="<?php echo htmlspecialchars($submittedData['emergency_contact'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['emergency_contact'])): ?>
                                <span class="error-message">Emergency contact name is required</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="emergency-phone">
                                    Emergency contact phone *
                                </label>
                                <span class="govuk-hint">Include country code</span>
                                <input class="govuk-input <?php echo (!empty($errors) && empty($submittedData['emergency_phone'])) ? 'error-field' : ''; ?>" 
                                       id="emergency-phone" name="emergency_phone" type="tel" 
                                       value="<?php echo htmlspecialchars($submittedData['emergency_phone'] ?? ''); ?>" required>
                                <?php if (!empty($errors) && empty($submittedData['emergency_phone'])): ?>
                                <span class="error-message">Emergency contact phone is required</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Photo Upload Section -->
                        <div class="form-section">
                            <h3 class="govuk-heading-m">Photo upload</h3>
                            
                            <div class="govuk-form-group">
                                <label class="govuk-label" for="passport-photo">
                                    Passport-sized photo *
                                </label>
                                <span class="govuk-hint">Your photo must be:
                                    <ul>
                                        <li>In JPEG or PNG format</li>
                                        <li>At least 600x750 pixels</li>
                                        <li>File size no more than 5MB</li>
                                        <li>Clear and in focus</li>
                                        <li>Taken within the last 6 months</li>
                                    </ul>
                                </span>
                                <div class="govuk-file-upload">
                                    <input class="govuk-input" id="passport-photo" name="passport_photo" type="file" accept="image/jpeg,image/png" required>
                                    <p class="govuk-body" style="margin-top: 10px;">Click to upload your photo</p>
                                </div>
                                <?php if (in_array('Passport photo is required', $errors)): ?>
                                <span class="error-message">Passport photo is required</span>
                                <?php elseif (in_array('Only JPEG and PNG files are allowed', $errors)): ?>
                                <span class="error-message">Only JPEG and PNG files are allowed</span>
                                <?php elseif (in_array('File size must be less than 5MB', $errors)): ?>
                                <span class="error-message">File size must be less than 5MB</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Declaration Section -->
                        <div class="form-section">
                            <h3 class="govuk-heading-m">Declaration</h3>
                            
                            <div class="govuk-form-group">
                                <div class="govuk-checkboxes">
                                    <div class="govuk-checkboxes__item">
                                        <input class="govuk-checkboxes__input" id="declaration" name="declaration" type="checkbox" 
                                               <?php echo isset($_POST['declaration']) ? 'checked' : ''; ?> required>
                                        <label class="govuk-label govuk-checkboxes__label" for="declaration">
                                            I declare that the information I have given is true and complete to the best of my knowledge. I understand that providing false information may result in my application being refused or my permission to stay being cancelled.
                                        </label>
                                    </div>
                                </div>
                                <?php if (in_array('You must agree to the declaration', $errors)): ?>
                                <span class="error-message">You must agree to the declaration</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="govuk-form-group">
                                <div class="govuk-checkboxes">
                                    <div class="govuk-checkboxes__item">
                                        <input class="govuk-checkboxes__input" id="data-protection" name="data_protection" type="checkbox" 
                                               <?php echo isset($_POST['data_protection']) ? 'checked' : ''; ?> required>
                                        <label class="govuk-label govuk-checkboxes__label" for="data-protection">
                                            I agree to the processing of my personal data as described in the <a href="#">privacy policy</a>.
                                        </label>
                                    </div>
                                </div>
                                <?php if (in_array('You must agree to the data protection policy', $errors)): ?>
                                <span class="error-message">You must agree to the data protection policy</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Submit buttons -->
                        <button type="submit" class="govuk-button" name="submit">
                            Submit registration
                        </button>
                        
                        <button type="reset" class="govuk-button govuk-button--secondary">
                            Clear form
                        </button>
                    </form>
                    
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <div class="govuk-grid-column-one-third">
                    <div class="govuk-success">
                        <h3 class="govuk-success__title">Need help?</h3>
                        <p class="govuk-body">If you need help with your application, contact the immigration helpline:</p>
                        <p class="govuk-body"><strong>Telephone:</strong><br>0300 123 2241</p>
                        <p class="govuk-body"><strong>Email:</strong><br>evisas.support@homeoffice.gov.uk</p>
                        <p class="govuk-body"><strong>Hours:</strong><br>Monday to Friday, 9am to 5pm</p>
                    </div>
                    
                    <div style="background-color: #f3f2f1; padding: 20px; margin-top: 30px;">
                        <h3 class="govuk-heading-m">Important information</h3>
                        <ul style="list-style-type: none; padding-left: 0;">
                            <li style="margin-bottom: 10px;">✓ You must provide accurate information</li>
                            <li style="margin-bottom: 10px;">✓ Keep your documents ready for verification</li>
                            <li style="margin-bottom: 10px;">✓ Application processing time: 3-5 working days</li>
                            <li style="margin-bottom: 10px;">✓ You'll receive email updates on your application status</li>
                            <li style="margin-bottom: 10px;">✓ Check your email for verification link after registration</li>
                        </ul>
                    </div>
                    
                    <div style="background-color: #f3f2f1; padding: 20px; margin-top: 30px;">
                        <h3 class="govuk-heading-m">Related content</h3>
                        <ul>
                            <li style="margin-bottom: 10px;"><a href="view-evisa-verify.php">Verify your application</a></li>
                            <li style="margin-bottom: 10px;"><a href="#">Check visa requirements</a></li>
                            <li style="margin-bottom: 10px;"><a href="#">UK visa fees</a></li>
                            <li style="margin-bottom: 10px;"><a href="#">Prove your immigration status</a></li>
                            <li style="margin-bottom: 10px;"><a href="#">Update your visa details</a></li>
                            <li style="margin-bottom: 10px;"><a href="#">Visa processing times</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="govuk-footer">
        <div class="container">
            <div class="govuk-footer__copyright">
                <p>© Crown copyright. All content is available under the Open Government Licence v3.0, except where otherwise stated.</p>
            </div>
        </div>
    </footer>

    <script>
        // Simple JavaScript for visa type selection (optional)
        function selectVisaType(element) {
            // Remove selected class from all options
            document.querySelectorAll('.visa-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            element.classList.add('selected');
            
            // Set the radio button as checked
            const radioButton = element.querySelector('input[type="radio"]');
            radioButton.checked = true;
        }
        
        // Attach click events to visa options
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.visa-option').forEach(option => {
                option.addEventListener('click', function() {
                    selectVisaType(this);
                });
            });
            
            // Set today as max for visa issue date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('visa-issue-date').max = today;
            
            // Set min for visa expiry date (today)
            document.getElementById('visa-expiry-date').min = today;
            
            // Set max date for date of birth (18 years ago)
            const minAgeDate = new Date();
            minAgeDate.setFullYear(minAgeDate.getFullYear() - 18);
            document.getElementById('date-of-birth').max = minAgeDate.toISOString().split('T')[0];
        });
    </script>
</body>
</html>