<?php
use \Mailjet\Resources;

require 'vendor/autoload.php';
include "connection.php"; // Ensure the database connection path is correct
$logFile = 'email_errors.log';

function sendExpenseFormEmail($formData) {
    global $database; // Ensure $database is accessible within the function

    $apiKey = '75714be908e64ce7a2686eeca5afb921';
    $apiSecret = '1b9d487cd5b4c212b6b95e28c768815e';

    $mj = new \Mailjet\Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);

    // Prepare the recipients array for Mailjet
    $recipients = [['Email' => "hmalone@targetsteel.net"]]; // Replace with actual recipient email

    // Start building the HTMLPart with a header
    $htmlPart = "<div style='font-family: Arial, sans-serif;'>
                <h2 style='color: #f7931e;'>Expense Form Submission</h2>
                <hr style='border: 1px solid #f7931e;'>";

    // Dynamically add form data to the email
    foreach ($formData as $key => $value) {
        if (!empty($value)) { // Check if the value is not empty
            // Convert blob data to string if necessary, e.g., for 'additional_comments'
            if ($key == 'additional_comments') {
                $value = '...'; // Convert or handle blob data accordingly
            }
            $htmlPart .= "<p><strong>" . ucfirst(str_replace('_', ' ', $key)) . ":</strong> $value</p>";
        }
    }

    // Close the HTMLPart
    $htmlPart .= "<p style='font-size: 0.9em; color: #666;'>This is an automated message, please do not reply directly to this email.</p>
            </div>";

    // Prepare the Mailjet message structure
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => "system.notification@targetmetalsync.com",
                    'Name' => "Target Metal Sync"
                ],
                'To' => $recipients,
                'Subject' => "New Expense Form Submission",
                'HTMLPart' => $htmlPart
            ]
        ]
    ];

    // Send the email
    try {
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        if ($response->success()) {
            return ['status' => 'success', 'message' => 'Message sent successfully!'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to send the message.'];
        }
    } catch (Exception $e) {
        error_log("Mailjet Error: " . $e->getMessage() . "\n", 3, $GLOBALS['logFile']);
        return ['status' => 'error', 'message' => 'Mailjet Error: ' . $e->getMessage()];
    }
}
// Add this function to your existing send_expense.php file
function sendApprovalDenialEmail($email, $isApproved, $reason = '', $formHtml = '') {
    $cssTemplate = "<style>
    form { margin: 20px 0; }
    .form-group { margin-bottom: 15px; }
    label { font-weight: bold; }
    input, select, textarea { border: 1px solid #ccc; padding: 10px; width: 100%; box-sizing: border-box; }
</style>";
    global $database; // Ensure $database is accessible within the function

    $apiKey = '75714be908e64ce7a2686eeca5afb921';
    $apiSecret = '1b9d487cd5b4c212b6b95e28c768815e';

    $mj = new \Mailjet\Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);

    $subject = $isApproved ? "Expense Request Approved" : "Expense Request Denied";
    $htmlPart = $isApproved ? "<p>Your expense request has been approved.</p>" : "<p>Your expense request has been denied.</p><p>Reason: $reason</p>";
   // Prepend the CSS template to the formHtml content
$formHtmlStyled = $cssTemplate . $formHtml;

// Then append $formHtmlStyled to the htmlPart
$htmlPart .= "<h2>Expense Details:</h2>" . $formHtmlStyled;
 // Close the HTMLPart
 $htmlPart .= "<p style='font-size: 0.9em; color: #666;'>This is an automated message, please do not reply directly to this email.</p>
 </div>";

    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => "system.notification@targetmetalsync.com",
                    'Name' => "Target Metal Sync"
                ],
                'To' => [
                    ['Email' => $email]
                ],
                'Subject' => $subject,
                'HTMLPart' => $htmlPart
            ]
        ]
    ];

    try {
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        if (!$response->success()) {
            throw new Exception('Mailjet Error');
        }
    } catch (Exception $e) {
        // Log error or handle it
        return false;
    }

    return true;
}
?>