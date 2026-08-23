<?php
// view-evisa-details.php
require_once 'config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user came from verification
if (!isset($_SESSION['verified_application']) || $_SESSION['verified_application'] !== true) {
    header('Location: view-evisa-verify.html');
    exit;
}

// Get application data from session
$applicationData = $_SESSION['verified_data'] ?? null;
$referenceNumber = $_SESSION['reference_number'] ?? '';

if (!$applicationData) {
    header('Location: view-evisa-verify.html');
    exit;
}

// Extract data
$firstName = htmlspecialchars($applicationData['first_name'] ?? '');
$lastName = htmlspecialchars($applicationData['last_name'] ?? '');
$fullName = $firstName . ' ' . $lastName;
$dateOfBirth = $applicationData['date_of_birth'] ?? '';
$nationality = htmlspecialchars($applicationData['nationality'] ?? '');
$passportNumber = htmlspecialchars($applicationData['passport_number'] ?? '');
$visaType = htmlspecialchars($applicationData['visa_type'] ?? '');
$visaIssueDate = $applicationData['visa_issue_date'] ?? '';
$visaExpiryDate = $applicationData['visa_expiry_date'] ?? '';
$email = htmlspecialchars($applicationData['email'] ?? '');
$mobileNumber = htmlspecialchars($applicationData['mobile_number'] ?? '');
$address = htmlspecialchars($applicationData['address'] ?? '');
$city = htmlspecialchars($applicationData['city'] ?? '');
$postalCode = htmlspecialchars($applicationData['postal_code'] ?? '');
$country = htmlspecialchars($applicationData['country'] ?? '');
$purposeOfVisit = htmlspecialchars($applicationData['purpose_of_visit'] ?? '');
$panNumber = htmlspecialchars($applicationData['pan_number'] ?? '');
$emergencyContact = htmlspecialchars($applicationData['emergency_contact'] ?? '');
$emergencyPhone = htmlspecialchars($applicationData['emergency_phone'] ?? '');
$applicationNumber = htmlspecialchars($applicationData['application_number'] ?? '');
$status = htmlspecialchars($applicationData['status'] ?? 'pending');
$photoPath = $applicationData['photo_path'] ?? '';
$registrationDate = $applicationData['registration_date'] ?? '';

// Format dates
function formatDate($dateString) {
    if (empty($dateString) || $dateString == '0000-00-00') {
        return 'Not specified';
    }
    
    try {
        $date = new DateTime($dateString);
        return $date->format('d F Y');
    } catch (Exception $e) {
        return 'Invalid date';
    }
}

// Format address
$fullAddress = '';
if (!empty($address)) {
    $fullAddress = $address;
    if (!empty($city)) $fullAddress .= ', ' . $city;
    if (!empty($postalCode)) $fullAddress .= ', ' . $postalCode;
    if (!empty($country)) $fullAddress .= ', ' . $country;
}

// Format emergency contact
$emergencyContactFull = '';
if (!empty($emergencyContact)) {
    $emergencyContactFull = $emergencyContact;
    if (!empty($emergencyPhone)) $emergencyContactFull .= ' (' . $emergencyPhone . ')';
}

// Get photo URL
$photoUrl = '';
if (!empty($photoPath)) {
    // If photo path is a full URL
    if (filter_var($photoPath, FILTER_VALIDATE_URL)) {
        $photoUrl = $photoPath;
    } 
    // If photo path is relative
    else if (file_exists($photoPath)) {
        $photoUrl = $photoPath;
    }
    // If photo is in uploads folder
    else if (file_exists('uploads/' . basename($photoPath))) {
        $photoUrl = 'uploads/' . basename($photoPath);
    }
    // If we have just filename
    else if (file_exists('uploads/' . $photoPath)) {
        $photoUrl = 'uploads/' . $photoPath;
    }
}

// Current date
$currentDate = date('d F Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your eVisas Details - GOV.UK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base styles from previous page */
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header styles */
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
            padding: 30px 0;
            background-color: white;
        }
        
        /* Visa Document Styling */
        .visa-document {
            border: 2px solid #1d70b8;
            background: white;
            padding: 40px;
            margin: 20px 0;
            position: relative;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .visa-header {
            border-bottom: 3px solid #1d70b8;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .visa-logo {
            font-size: 24px;
            font-weight: bold;
            color: #1d70b8;
        }
        
        .visa-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #0b0c0c;
        }
        
        .visa-ref-number {
            font-size: 18px;
            color: #505a5f;
        }
        
        .visa-body {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }
        
        .visa-info-section {
            margin-bottom: 25px;
        }
        
        .visa-info-title {
            font-size: 18px;
            font-weight: bold;
            color: #1d70b8;
            margin-bottom: 10px;
            border-bottom: 1px solid #f3f2f1;
            padding-bottom: 5px;
        }
        
        .visa-info-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            margin-bottom: 8px;
        }
        
        .visa-info-label {
            font-weight: bold;
            color: #505a5f;
        }
        
        .visa-info-value {
            color: #0b0c0c;
        }
        
        .visa-photo-section {
            text-align: center;
        }
        
        .visa-photo {
            width: 200px;
            height: 250px;
            border: 2px solid #b1b4b6;
            background-color: #f8f8f8;
            margin-bottom: 15px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .visa-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-placeholder {
            text-align: center;
            color: #505a5f;
            padding: 20px;
        }
        
        .photo-placeholder i {
            font-size: 48px;
            margin-bottom: 10px;
            color: #b1b4b6;
        }
        
        .visa-qr-code {
            width: 120px;
            height: 120px;
            background-color: #f3f2f1;
            border: 1px solid #b1b4b6;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #505a5f;
            font-size: 12px;
        }
        
        /* Status badge */
        .visa-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .status-approved {
            background-color: #cce2d8;
            color: #00703c;
        }
        
        .status-pending {
            background-color: #fff7bf;
            color: #594d00;
        }
        
        .status-rejected {
            background-color: #f6d7d2;
            color: #d4351c;
        }
        
        /* Footer section */
        .visa-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #f3f2f1;
            font-size: 14px;
            color: #505a5f;
        }
        
        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .govuk-button {
            background-color: #1d70b8;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 19px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .govuk-button:hover {
            background-color: #0b4e8a;
        }
        
        .govuk-button--secondary {
            background-color: #f3f2f1;
            color: #0b0c0c;
            border: 2px solid #0b0c0c;
        }
        
        .govuk-button--warning {
            background-color: #d4351c;
            color: white;
        }
        
        /* Print styles */
       /* Print styles */
/* Print styles */
@media print {
    /* Hide everything except main content */
    body * {
        visibility: hidden;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .govuk-main, .govuk-main * {
        visibility: visible;
    }
    
    /* Position everything at top */
    .govuk-main {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Hide non-printable elements */
    .no-print, .govuk-header, .govuk-footer, .action-buttons,
    .watermark {
        display: none !important;
    }
    
    /* Container styling */
    .container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Visa document print styling */
    .visa-document {
        width: 100% !important;
        margin: 0 !important;
        padding: 20px !important;
        border: 2px solid #000000 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    
    /* Ensure all text is black */
    * {
        color: #000000 !important;
        background-color: transparent !important;
        text-shadow: none !important;
        box-shadow: none !important;
    }
    
    /* Fix layout for print */
    .visa-body {
        display: flex !important;
        gap: 40px !important;
        page-break-inside: avoid;
    }
    
    .visa-info {
        flex: 2 !important;
    }
    
    .visa-photo-section {
        flex: 1 !important;
    }
    
    /* Ensure borders are visible */
    .visa-header {
        border-bottom: 2px solid #000000 !important;
    }
    
    .visa-info-title {
        border-bottom: 1px solid #000000 !important;
    }
    
    .visa-footer {
        border-top: 2px solid #000000 !important;
    }
    
    /* Status badge for print */
    .visa-status {
        border: 1px solid #000000 !important;
        background-color: #ffffff !important;
    }
    
    .status-pending {
        color: #000000 !important;
        background-color: #ffffff !important;
    }
    
    /* Photo styling */
    .visa-photo {
        border: 1px solid #000000 !important;
    }
    
    /* Remove any background colors */
    body, .govuk-main, .visa-document {
        background: white !important;
    }
    
    /* QR code styling */
    .visa-qr-code {
        border: 1px solid #000000 !important;
        background: white !important;
    }
    
    /* Force single page */
    .page-break {
        page-break-before: avoid !important;
        page-break-after: avoid !important;
        page-break-inside: avoid !important;
    }
    
    /* Prevent elements from being cut off */
    h1, h2, h3, h4, h5, h6, p, div {
        page-break-inside: avoid !important;
        page-break-after: avoid !important;
    }
    
    /* Ensure proper spacing */
    .visa-info-section {
        margin-bottom: 20px !important;
        page-break-inside: avoid;
    }
    
    .visa-info-row {
        margin-bottom: 8px !important;
    }
}
        
        /* Responsive */
        @media (max-width: 768px) {
            .visa-body {
                grid-template-columns: 1fr;
            }
            
            .visa-photo {
                width: 150px;
                height: 190px;
            }
            
            .visa-info-row {
                grid-template-columns: 1fr;
                margin-bottom: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .govuk-button {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Watermark */
        .watermark {
            position: absolute;
            opacity: 0.1;
            font-size: 120px;
            color: #1d70b8;
            transform: rotate(-45deg);
            z-index: 0;
            pointer-events: none;
            white-space: nowrap;
            top: 30%;
            left: 10%;
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
                <span>eVisas - Your Immigration Status</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="govuk-main">
        <div class="container">
            <div class="no-print" style="margin-bottom: 30px;">
                <a href="view-evisa-verify.html" class="govuk-button govuk-button--secondary">
                    <i class="fas fa-arrow-left"></i> Back to Verification
                </a>
            </div>
            
            <h1 style="font-size: 36px; margin-bottom: 20px; color: #0b0c0c;">Your eVisas Details</h1>
            <p style="margin-bottom: 30px; color: #505a5f;">
                Reference: <span id="displayRefNumber"><?php echo htmlspecialchars($referenceNumber); ?></span> • <?php echo $currentDate; ?>
            </p>
            
            <!-- Action Buttons -->
            <div class="action-buttons no-print">
                <button onclick="window.print()" class="govuk-button">
                    <i class="fas fa-print"></i> Print eVisas
                </button>
                <button onclick="downloadAsPDF()" class="govuk-button">
                    <i class="fas fa-download"></i> Download PDF
                </button>
                <button onclick="shareDocument()" class="govuk-button govuk-button--secondary">
                    <i class="fas fa-share"></i> Share
                </button>
            </div>
            
            <!-- Visa Document -->
            <div class="visa-document" id="visaDocument">
                <div class="watermark">GOV.UK eVisas</div>
                
                <div class="visa-header">
                    <div class="visa-logo">GOV.UK</div>
                    <div class="visa-title">ELECTRONIC VISA (eVisas)</div>
                    <div class="visa-ref-number">Ref: <span id="documentRef"><?php echo htmlspecialchars($referenceNumber); ?></span></div>
                </div>
                
                <div class="visa-body">
                    <div class="visa-info">
                        <!-- Personal Information -->
                        <div class="visa-info-section">
                            <div class="visa-info-title">Personal Information</div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Full Name:</div>
                                <div class="visa-info-value" id="fullName"><?php echo $fullName; ?></div>
                            </div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Date of Birth:</div>
                                <div class="visa-info-value" id="dob"><?php echo formatDate($dateOfBirth); ?></div>
                            </div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Nationality:</div>
                                <div class="visa-info-value" id="nationality"><?php echo $nationality; ?></div>
                            </div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Passport No:</div>
                                <div class="visa-info-value" id="passportNo"><?php echo $passportNumber; ?></div>
                            </div>
                        </div>
                        
                        <!-- Visa Information -->
                        <div class="visa-info-section">
                            <div class="visa-info-title">Visa Information</div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Visa Type:</div>
                                <div class="visa-info-value" id="visaType"><?php echo $visaType; ?></div>
                            </div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Issue Date:</div>
                                <div class="visa-info-value" id="issueDate"><?php echo formatDate($visaIssueDate); ?></div>
                            </div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Expiry Date:</div>
                                <div class="visa-info-value" id="expiryDate"><?php echo formatDate($visaExpiryDate); ?></div>
                            </div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Status:</div>
                                <div class="visa-info-value">
                                    <span class="visa-status status-<?php echo $status; ?>" id="visaStatus">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="visa-info-section">
                            <div class="visa-info-title">Contact Information</div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Email:</div>
                                <div class="visa-info-value" id="email"><?php echo $email; ?></div>
                            </div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Mobile:</div>
                                <div class="visa-info-value" id="mobile"><?php echo $mobileNumber; ?></div>
                            </div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Address:</div>
                                <div class="visa-info-value" id="address"><?php echo $fullAddress; ?></div>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="visa-info-section">
                            <div class="visa-info-title">Additional Information</div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Purpose:</div>
                                <div class="visa-info-value" id="purpose"><?php echo $purposeOfVisit; ?></div>
                            </div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">PAN Number:</div>
                                <div class="visa-info-value" id="panNumber"><?php echo $panNumber ?: 'Not applicable'; ?></div>
                            </div>
                            <div class="visa-info-row">
                                <div class="visa-info-label">Emergency Contact:</div>
                                <div class="visa-info-value" id="emergencyContact"><?php echo $emergencyContactFull; ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="visa-photo-section">
                        <div class="visa-photo" id="photoContainer">
                            <?php if (!empty($photoUrl) && file_exists($photoUrl)): ?>
                            <img id="passportPhoto" src="<?php echo $photoUrl; ?>" alt="Passport Photo of <?php echo $fullName; ?>">
                            <?php else: ?>
                            <div class="photo-placeholder">
                                <i class="fas fa-user-circle"></i>
                                <div>Photo not available</div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <div style="font-weight: bold; margin-bottom: 5px;">Application Number</div>
                            <div style="font-size: 18px; color: #1d70b8;" id="applicationNumber"><?php echo $applicationNumber; ?></div>
                        </div>
                       
                    </div>
                </div>
                
                <div class="visa-footer">
                    <p><strong>Important Information:</strong></p>
                    <p>1. This is an electronic visa (eVisas) and does not require a physical label in your passport.</p>
                    <p>2. You must carry this document when traveling to the UK along with your passport.</p>
                    <p>3. This visa allows multiple entries to the UK during its validity period.</p>
                    <p>4. For verification, visit: <strong>gov.uk/verify-evisa</strong> and enter your reference number.</p>
                    <p style="margin-top: 20px; text-align: center; font-size: 12px;">
                        Issued by: UK Visas and Immigration, Home Office<br>
                        This document is generated electronically and is legally valid without signature.
                    </p>
                </div>
            </div>
            
            <!-- Additional Actions -->
            <div class="action-buttons no-print" style="margin-top: 30px;">
                <button onclick="requestExtension()" class="govuk-button">
                    <i class="fas fa-calendar-plus"></i> Request Extension
                </button>
                <button onclick="reportIssue()" class="govuk-button govuk-button--secondary">
                    <i class="fas fa-flag"></i> Report Issue
                </button>
                <button onclick="updateDetails()" class="govuk-button govuk-button--secondary">
                    <i class="fas fa-edit"></i> Update Details
                </button>
            </div>
            
            <!-- Help Information -->
            <div class="no-print" style="margin-top: 40px; padding: 20px; background-color: #f3f2f1;">
                <h3 style="font-size: 24px; margin-bottom: 15px;">Need help with your eVisas?</h3>
                <p><strong>Telephone:</strong> 0300 123 2241 (Monday to Friday, 9am to 5pm)</p>
                <p><strong>Email:</strong> evisas.support@homeoffice.gov.uk</p>
                <p><strong>Emergency contact (outside UK):</strong> +44 (0)20 7008 5000</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="govuk-footer no-print">
        <div class="container">
            <div class="govuk-footer__copyright">
                <p>© Crown copyright. All content is available under the Open Government Licence v3.0, except where otherwise stated.</p>
            </div>
        </div>
    </footer>

    <script>
        // Print function
        function printDocument() {
            window.print();
        }
        
        // Download as PDF (mock function)
        function downloadAsPDF() {
            alert('In a real implementation, this would generate and download a PDF version of your eVisas.');
            // In real implementation, use a library like jsPDF or make server request
        }
        
        // Share document
        function shareDocument() {
            if (navigator.share) {
                navigator.share({
                    title: 'My eVisas Document',
                    text: 'My UK eVisas details',
                    url: window.location.href
                });
            } else {
                alert('Share functionality is available on modern browsers. The URL has been copied to clipboard.');
                navigator.clipboard.writeText(window.location.href);
            }
        }
        
        // Additional functions
        function requestExtension() {
            alert('Redirecting to visa extension application...');
            // window.location.href = 'visa-extension.html';
        }
        
        function reportIssue() {
            alert('Opening issue report form...');
            // window.location.href = 'report-issue.html';
        }
        
        function updateDetails() {
            alert('Redirecting to details update page...');
            // window.location.href = 'update-details.html';
        }
        
        // Check if photo is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const photoImg = document.getElementById('passportPhoto');
            if (photoImg) {
                photoImg.onerror = function() {
                    // If photo fails to load, show placeholder
                    const container = document.getElementById('photoContainer');
                    container.innerHTML = `
                        <div class="photo-placeholder">
                            <i class="fas fa-user-circle"></i>
                            <div>Photo not available</div>
                        </div>
                    `;
                };
            }
        });
    </script>
</body>
</html>