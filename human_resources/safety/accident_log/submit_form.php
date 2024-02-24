<?php
include '../../../configurations/connection.php'; // Adjust the path as necessary
require '../../../configurations/vendor/autoload.php'; // Make sure this path correctly points to Composer's autoload.php
session_start();
if(!isset($_SESSION['user'])){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../../../index.php");
    exit();
}
use \Mailjet\Resources;

// Mailjet API credentials
$apiKey=$APIKey; 
$apiSecret=$APISecret; 

// Insert form data into the accident_report table
$stmt = $database->prepare("INSERT INTO `accident_report` (`employee_id`,`non_employee_name`, `accident_type`, `date_added`, `accident_date`, `accident_time`, `shift`, `time_sent_to_clinic`, `date_sent_to_clinic`, `accident_location`, `time_of_report`, `shift_start_time`, `accident_description`, `consecutive_days_worked`, `proper_ppe_used`, `proper_ppe_used_explain`, `procedure_followed`, `procedure_followed_explain`, `potential_severity`, `potential_severity_explain`, `environmental_impact`, `environmental_impact_explain`, `prevent_reoccurance`, `immediate_corrective_action`, `irp_required`, `irp_names`, `equip_out_of_service`, `equip_out_of_service_explain`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('issssssssssssissssssssssssss', $_POST['employee_id'], $_POST['non_employee_name'], $_POST['accident_type'], $_POST['date_added'], $_POST['accident_date'], $_POST['accident_time'], $_POST['shift'], $_POST['time_sent_to_clinic'], $_POST['date_sent_to_clinic'], $_POST['accident_location'], $_POST['time_of_report'], $_POST['shift_start_time'], $_POST['accident_description'], $_POST['consecutive_days_worked'], $_POST['proper_ppe_used'], $_POST['proper_ppe_used_explain'], $_POST['procedure_followed'], $_POST['procedure_followed_explain'], $_POST['potential_severity'], $_POST['potential_severity_explain'], $_POST['environmental_impact'], $_POST['environmental_impact_explain'], $_POST['prevent_reoccurrence'], $_POST['immediate_corrective_action'], $_POST['irp_required'], $_POST['irp_names'], $_POST['equip_out_of_service'], $_POST['equip_out_of_service_explain']);
$stmt->execute();

// Get the ID of the accident
$accidentId = $database->insert_id;
// Fetch employee's first and last name based on employee_id
$employeeId = $_POST['employee_id'];
$employeeQuery = $database->prepare("SELECT `employee_fname`, `employee_lname` FROM `employees` WHERE `employee_id` = ?");
$employeeQuery->bind_param('i', $employeeId);
$employeeQuery->execute();
$employeeResult = $employeeQuery->get_result();
if ($employeeRow = $employeeResult->fetch_assoc()) {
    $employeeFname = $employeeRow['employee_fname'];
    $employeeLname = $employeeRow['employee_lname'];
} else {
    // Handle case where employee is not found
    $employeeFname = "Unknown";
    $employeeLname = "Employee";
}

// Check if files were uploaded
if (isset($_FILES['fileUpload']['name']) && is_array($_FILES['fileUpload']['name']) && $_FILES['fileUpload']['error'][0] !== UPLOAD_ERR_NO_FILE) {
    // Loop through each file
    for ($i = 0; $i < count($_FILES['fileUpload']['name']); $i++) {
        // Check if file uploaded without errors
        if ($_FILES['fileUpload']['error'][$i] === UPLOAD_ERR_OK) {
            $fileTmpName = $_FILES['fileUpload']['tmp_name'][$i];
            $fileName = uniqid() . '-' . $_FILES['fileUpload']['name'][$i]; // Generate a unique name for the file

            // Check if directory exists
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/accident_files/';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            // Define the path where the file will be stored
            $filePath = $dir . $fileName;
            // After uploading files, initialize an array to store file paths
            $uploadedFilePaths = [];
             // Move the uploaded file to the desired directory
             if (move_uploaded_file($fileTmpName, $filePath)) {
                $stmt = $database->prepare("INSERT INTO accident_files (accident_id, file_name, file_path) VALUES (?, ?, ?)");
                $stmt->bind_param('iss', $accidentId, $fileName, $filePath);
                $stmt->execute();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload file: ' . $_FILES['fileUpload']['name'][$i]]);
                exit;
            }
        } else {
            // Handle file upload error
            echo json_encode(['status' => 'error', 'message' => 'Error uploading file: ' . $_FILES['fileUpload']['name'][$i]]);
            exit;
        }
    }
    // If all files are processed successfully
    echo json_encode(['status' => 'success', 'message' => 'Accident report and files updated successfully.']);
} else {
    // If there are no files to upload, just update the accident report
    echo json_encode(['status' => 'success', 'message' => 'Accident report updated successfully. No files were uploaded.']);
}

// Prepare the email content
// Prepare the email content with a link to the full report
$subject = "New Accident Report Submitted";
$fullReportUrl = "http://targetmetalsync.com/human_resources/safety/accident_log/index.php?accident_id=" . $accidentId; // Replace with your actual URL
// Ensure the dynamic data is sanitized before including it in the HTML content
$employeeFname = htmlspecialchars($employeeFname, ENT_QUOTES, 'UTF-8');
$employeeLname = htmlspecialchars($employeeLname, ENT_QUOTES, 'UTF-8');
$accidentType = htmlspecialchars($_POST['accident_type'], ENT_QUOTES, 'UTF-8');
$accidentDate = htmlspecialchars($_POST['accident_date'], ENT_QUOTES, 'UTF-8');
$accidentLocation = htmlspecialchars($_POST['accident_location'], ENT_QUOTES, 'UTF-8');
$accidentDescription = htmlspecialchars($_POST['accident_description'], ENT_QUOTES, 'UTF-8');
$fullReportUrl = htmlspecialchars($fullReportUrl, ENT_QUOTES, 'UTF-8');

$htmlPart = "<div style='font-family: Arial, sans-serif;'>
                <h2 style='color: #f7931e;'>Accident Report Details</h2>
                <hr style='border: 1px solid #f7931e;'>
                <p><strong>Employee Name:</strong> {$employeeFname} {$employeeLname}</p>
                <p><strong>Accident Type:</strong> {$accidentType}</p>
                <p><strong>Date of Accident:</strong> {$accidentDate}</p>
                <p><strong>Location:</strong> {$accidentLocation}</p>
                <p><strong>Description:</strong> {$accidentDescription}</p>
                <p>For the full report, please <a href='{$fullReportUrl}'>click here</a>.</p>
                <p style='font-size: 0.9em; color: #666;'>This is an automated message, please do not reply directly to this email.</p>
            </div>";

// List of recipient emails
$recipients = [
    [
        'Email' => "claviolette@targetmetalblanking.com",
        'Name' => "Christian Laviolette"
    ],
    [
        'Email' => "wjohns@targetmetalblanking.com",
        'Name' => "Bill Johns"
    ],
    [
        'Email' => "bdemantes@targetmetalblanking.com",
        'Name' => "Brendan Demantes"
    ]
    // Add more recipients as needed
];

// Initialize the Mailjet client
$mj = new \Mailjet\Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);

// Prepare the Mailjet message structure with attachments
$attachments = [];
foreach ($uploadedFilePaths as $filePath) {
    $fileContent = file_get_contents($filePath);
    $base64Content = base64_encode($fileContent);
    $attachments[] = [
        'ContentType' => mime_content_type($filePath),
        'Filename' => basename($filePath),
        'Base64Content' => $base64Content
    ];
}

// Prepare the Mailjet message structure
$body = [
    'Messages' => [
        [
            'From' => [
                'Email' => "system.notification@targetmetalsync.com",
                'Name' => "Target Metal Sync"
            ],
            'To' => $recipients,
            'Subject' => $subject,
            'HTMLPart' => $htmlPart,
            'Attachments' => $attachments
        ]
    ]
];

// Send the email
try {
    $response = $mj->post(Resources::$Email, ['body' => $body]);
    if ($response->success()) {
        
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to send accident report email.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mailjet Error: ' . $e->getMessage()
    ]);
}
?>