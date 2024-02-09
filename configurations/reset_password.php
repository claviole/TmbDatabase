<?php
use \Mailjet\Resources;

require 'vendor/autoload.php';
include "connection.php";

$logFile = 'password_reset_errors.log';

if ($_POST) {
    $useremail = $_POST['email'];

    // Generate a unique token
    $token = bin2hex(random_bytes(50));

    // Store the token and email in the database
    $stmt = $database->prepare("INSERT INTO `password_resets` (`email`,`token`) VALUES (?, ?)");
    $stmt->bind_param("ss", $useremail, $token);
    $stmt->execute();

    // Mailjet API credentials
    $apiKey = '75714be908e64ce7a2686eeca5afb921';
    $apiSecret = '1b9d487cd5b4c212b6b95e28c768815e';

    $mj = new \Mailjet\Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);

    // Prepare the Mailjet message structure
    $resetLink = "https://targetmetalsync.com/configurations/password_reset_link/reset_password.php?token=$token";
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => "system.notification@targetmetalsync.com",
                    'Name' => "Target Metal Utility System"
                ],
                'To' => [
                    [
                        'Email' => $useremail
                    ]
                ],
                'Subject' => 'Password Reset Request',
                'TextPart' => "To reset your password, please click the link below: $resetLink",
                'HTMLPart' => "<div style='font-family: Arial, sans-serif; color: #333;'>
                    <h1 style='background-color: #f2f2f2; padding: 10px; text-align: center; color: #333;'>Password Reset Request</h1>
                    <p>Hello,</p>
                    <p>You have requested to reset your password for your account at Target Metal Utility. To set a new password, please click on the button below:</p>
                    <p style='text-align: center; margin: 20px 0;'>
                        <a href='$resetLink' style='background-color: #004a99; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Password</a>
                    </p>
                    <p>If you did not request a password reset, please ignore this email and contact claviolette@targetmetalblanking.com if you have any concerns.</p>
                    <p>Best regards,<br>Target Metal Team</p>
                    <hr>
                    <footer style='font-size: 0.8em; text-align: center; color: #777;'>
                        This is an automated message, please do not reply directly to this email.
                    </footer>
                </div>"
            ]
        ]
    ];

    // Send the email
try {
    $response = $mj->post(Resources::$Email, ['body' => $body]);
    if ($response->success()) {
        // Log the success response to a server-side file if needed
        // error_log("Mailjet Success: " . print_r($response->getData(), true) . "\n", 3, $logFile);
        echo 'Password reset email sent!';
    } else {
        // Log the error response to a server-side file
        error_log("Mailjet Error: " . print_r($response->getData(), true) . "\n", 3, $logFile);
        echo 'An error occurred. Please try again later.';
    }
} catch (Exception $e) {
    // Log the exception to a server-side file
    error_log("Mailjet Exception: " . $e->getMessage() . "\n", 3, $logFile);
    echo 'An error occurred. Please try again later.';
}
}
?>