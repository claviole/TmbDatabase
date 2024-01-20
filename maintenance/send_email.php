<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../configurations/vendor/autoload.php';
include "../configurations/connection.php"; // Make sure this path is correct

if ($_POST) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    // Your Outlook email address and password
    $outlookEmail = 'tmms.automation@outlook.com';
    $outlookPassword = '042217Dv!';

    // Create a new PHPMailer instance
    $mail = new PHPMailer;

    // Set up SMTP
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp-mail.outlook.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $outlookEmail;
    $mail->Password = $outlookPassword;

    // Set who the message is to be sent from
    $mail->setFrom($outlookEmail, 'TMB Utility Notification System');

    // Retrieve the orange tag ID and technician IDs from the POST request
    $orangeTagId = $_POST['orange_tag_id'];
    $technicianIds = $_POST['technicians']; // This should be an array of technician IDs

    // Query the database for the email addresses of the technicians
    $technicianEmails = [];
    foreach ($technicianIds as $techId) {
        $query = "SELECT `email` FROM `Users` WHERE `id` = ?";
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

    // Set the subject line
    $mail->Subject = 'Maintenance Ticket Update';

    // Set the body
    $mail->isHTML(true); // Set email format to HTML
    $mail->Body = "<p>There has been changes to a maintenance ticket that you are assigned to, or you have been added to a new Maintenance Ticket.</p>";
    $mail->Body .= "<p><strong>Orange Tag ID:</strong> {$orangeTagId}</p>";
    $mail->Body .= "<p>Please check the system for further details.</p>";

    // Add each technician as a recipient
    foreach ($technicianEmails as $techEmail) {
        $mail->addAddress($techEmail);
    }

    // Send the email
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message sent to technicians!';
    }

    // Clear all recipients for next iteration
    $mail->clearAddresses();
}
?>