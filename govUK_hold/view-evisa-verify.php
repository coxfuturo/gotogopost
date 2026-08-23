<?php
// view-evisa-verify.php
require_once 'config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$error = '';
$success = false;

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $referenceNumber = trim($_POST['reference_number'] ?? '');
    $passportNumber = trim($_POST['passport_number'] ?? '');
    
    if (empty($referenceNumber) || empty($passportNumber)) {
        $error = 'Both Reference Number and Passport Number are required';
    } else {
        try {
            // Search for application by reference number (application_number) and passport number
            $stmt = $pdo->prepare("
                SELECT * FROM evisa_registrations 
                WHERE application_number = ? AND passport_number = ?
                LIMIT 1
            ");
            
            $stmt->execute([$referenceNumber, $passportNumber]);
            $application = $stmt->fetch();
            
            if ($application) {
                // Store in session for the view page
                $_SESSION['verified_application'] = true;
                $_SESSION['verified_data'] = $application;
                $_SESSION['reference_number'] = $referenceNumber;
                
                // Redirect to details page
                header('Location: view-evisa-details.php');
                exit;
            } else {
                $error = 'No matching application found. Please check your details.';
            }
            
        } catch (PDOException $e) {
            $error = 'Database error. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Your eVisas Status - GOV.UK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
            max-width: 800px;
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
        
        /* Main content */
        .govuk-main {
            padding: 40px 0;
            background-color: white;
            min-height: 70vh;
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
        
        /* Panel */
        .govuk-panel {
            background-color: #1d70b8;
            color: white;
            padding: 40px;
            margin: 30px 0;
            text-align: center;
            border-radius: 4px;
        }
        
        .govuk-panel__title {
            font-size: 32px;
            margin-bottom: 15px;
        }
        
        .govuk-panel__body {
            font-size: 24px;
        }
        
        /* Form styles */
        .govuk-form-group {
            margin-bottom: 30px;
        }
        
        .govuk-label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 19px;
        }
        
        .govuk-input {
            width: 100%;
            padding: 15px;
            border: 2px solid #0b0c0c;
            font-size: 19px;
            max-width: 500px;
        }
        
        .govuk-input:focus {
            outline: 3px solid #ffdd00;
            outline-offset: 0;
        }
        
        .govuk-button {
            background-color: #1d70b8;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 19px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }
        
        .govuk-button:hover {
            background-color: #0b4e8a;
        }
        
        /* Error message */
        .govuk-error-summary {
            background-color: #fbeaea;
            border-left: 8px solid #d4351c;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .govuk-error-summary__title {
            color: #d4351c;
            font-size: 24px;
            margin-bottom: 10px;
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
        
        /* Help text */
        .govuk-hint {
            color: #505a5f;
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }
        
        /* Information box */
        .info-box {
            background-color: #f3f2f1;
            border-left: 4px solid #1d70b8;
            padding: 20px;
            margin: 20px 0;
        }
        
        @media (max-width: 768px) {
            .govuk-heading-xl {
                font-size: 36px;
            }
            
            .govuk-heading-l {
                font-size: 28px;
            }
            
            .govuk-panel {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="govuk-header">
        <div class="container govuk-header__container">
            <div class="govuk-header__logo">
                <div class="govuk-header__logotype">
                    <a href="index.html">GOV.UK</a>
                </div>
            </div>
            <div class="govuk-header__content">
                <span>eVisas - View Your Status</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="govuk-main">
        <div class="container">
            <h1 class="govuk-heading-xl">View Your eVisas Status</h1>
            
            <div class="info-box">
                <p><strong>Important:</strong> Use the Application Number and Passport Number from your registration confirmation email.</p>
            </div>
            
            <div class="govuk-panel">
                <h1 class="govuk-panel__title">Secure Access</h1>
                <div class="govuk-panel__body">
                    Enter your details to view your eVisas status
                </div>
            </div>
            
            <!-- Display Error Message -->
            <?php if (!empty($error)): ?>
            <div class="govuk-error-summary">
                <h2 class="govuk-error-summary__title">Verification Error</h2>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Verification Form -->
            <form method="POST" action="">
                <div class="govuk-form-group">
                    <label class="govuk-label" for="reference-number">
                        Application Number
                    </label>
                    <span class="govuk-hint">
                        Enter the Application Number you received after registration (e.g., EV20240115001)
                    </span>
                    <input class="govuk-input" id="reference-number" name="reference_number" type="text" 
                           required placeholder="Enter your application number"
                           value="<?php echo htmlspecialchars($_POST['reference_number'] ?? ''); ?>">
                </div>
                
                <div class="govuk-form-group">
                    <label class="govuk-label" for="passport-number">
                        Passport Number
                    </label>
                    <span class="govuk-hint">
                        Enter the passport number used during registration
                    </span>
                    <input class="govuk-input" id="passport-number" name="passport_number" type="text" 
                           required placeholder="Enter your passport number"
                           value="<?php echo htmlspecialchars($_POST['passport_number'] ?? ''); ?>">
                </div>
                
                <button type="submit" class="govuk-button">
                    <i class="fas fa-search"></i> Verify and View Details
                </button>
            </form>
            
            <div style="margin-top: 40px; padding: 20px; background-color: #f3f2f1;">
                <h3 class="govuk-heading-m">Need help?</h3>
                <p><strong>Lost your Application Number?</strong> Check your registration confirmation email.</p>
                <p><strong>Contact eVisas support:</strong></p>
                <p><strong>Telephone:</strong> 0300 123 2241 (Monday to Friday, 9am to 5pm)</p>
                <p><strong>Email:</strong> evisas.support@homeoffice.gov.uk</p>
                <p><strong>Remember:</strong> Your Application Number was sent to your registered email address after successful registration.</p>
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
        // Auto-focus on first input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('reference-number').focus();
        });
    </script>
</body>
</html>