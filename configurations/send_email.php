<?php
use \Mailjet\Resources;

require '../configurations/vendor/autoload.php';
include "../configurations/connection.php"; // Make sure this path is correct
$logFile = 'email_errors.log';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $apiKey = '75714be908e64ce7a2686eeca5afb921';
    $apiSecret = '1b9d487cd5b4c212b6b95e28c768815e';

    $mj = new \Mailjet\Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);

    // Retrieve the orange tag ID and technician IDs from the POST request
    $orangeTagId = $_POST['orange_tag_id'];
    $ticketType = $_POST['ticket_type'];
    $originatorName = $_POST['originator_name'];
    $location = $_POST['location'];
    $priority = $_POST['priority'];
    $supervisor = $_POST['supervisor'];
    $creationDate = $_POST['orange_tag_creation_date'];
    $creationTime = $_POST['orange_tag_creation_time'];
    $description = $_POST['orange_tag_description'];
    $technicianIds = $_POST['technicians']; // This should be an array of technician IDs

    // Query the database for the email addresses of the technicians
    $technicianEmails = [];
    foreach ($technicianIds as $techId) {
        $query = "SELECT email FROM Users WHERE id = ?";
        $stmt = $database->prepare($query);
        $stmt->bind_param("i", $techId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $technicianEmails[] = $row['email'];
        }
    }

    // Check if we have any emails to send to
    if (empty($technicianEmails)) {
        echo 'No technician emails found.';
        exit;
    }

    // Prepare the recipients array for Mailjet
    $recipients = [];
    foreach ($technicianEmails as $techEmail) {
        $recipients[] = [
            'Email' => $techEmail
        ];
    }

    // Prepare the Mailjet message structure
    // Prepare the Mailjet message structure with improved HTML styling
$body = [
    'Messages' => [
        [
            'From' => [
                'Email' => "system.notification@targetmetalsync.com",
                'Name' => "Target Metal Utility System"
            ],
            'To' => $recipients,
            'Subject' => "Maintenance Ticket #$orangeTagId Update",
            'TextPart' => "Maintenance Ticket #$orangeTagId has been updated. Please check the system for further details.",
            'HTMLPart' => "<div style='font-family: Arial, sans-serif;'>
                <h2 style='color: #f7931e;'>Maintenance Ticket Update</h2>
                <hr style='border: 1px solid #f7931e;'>
                <h3 style='color: #333;'>Ticket #$orangeTagId Details:</h3>
                <p><strong>Ticket Type:</strong> $ticketType</p>
                <p><strong>Originator:</strong> $originatorName</p>
                <p><strong>Location:</strong> $location</p>
                <p><strong>Priority:</strong> $priority</p>
                <p><strong>Supervisor:</strong> $supervisor</p>
                <p><strong>Creation Date:</strong> $creationDate</p>
                <p><strong>Creation Time:</strong> $creationTime</p>
                <p><strong>Description:</strong> $description</p>
                <p style='font-size: 0.9em; color: #666;'>This is an automated message, please do not reply directly to this email.</p>
            </div>"
        ]
    ]
];

    // Send the email
    try {
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
        echo 'Message sent to technicians!';
    } catch (Exception $e) {
        error_log("Mailjet Error: " . $e->getMessage() . "\n", 3, $logFile);
        echo 'Mailjet Error: ' . $e->getMessage();
    }
} else {
    error_log("No POST data received\n", 3, $logFile);

}
?>