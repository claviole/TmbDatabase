<?php
session_start();
// Check if the user is logged in and is an admin
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != 'super-admin'){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../../../index.php");
    exit();
}
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
$pepper = $PEPPER; // Replace with your actual pepper

// Include Mailjet Client
use \Mailjet\Resources;
require '../../../configurations/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    // Other form processing...

    // Generate a random 6 character password
    $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);

    // Hash the password and other database operations...
    // After successfully creating the user and before redirecting...

    // Prepare the Mailjet client
    $apiKey = '75714be908e64ce7a2686eeca5afb921';
    $apiSecret = '1b9d487cd5b4c212b6b95e28c768815e';
    $mj = new \Mailjet\Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);

    // Prepare the email content
    // Prepare the email content with enhanced styling
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
            'Subject' => "Welcome to Our Company!",
            'HTMLPart' => "
            <div style='background-color: #f9f9f9; padding: 20px; font-family: Arial, sans-serif;'>
                <div style='max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
                    <h2 style='text-align: center; color: #d9534f; margin-bottom: 20px;'>Welcome Aboard!</h2>
                    <p style='color: #333; font-size: 16px;'>Dear <strong>$first_name</strong>,</p>
                    <p style='color: #333; font-size: 16px;'>An account for TMSync has been created on your behalf by an administrator.</p> 
                    <p style='color: #333; font-size: 16px;'>Here is your temporary password:</p>
                    <p style='background-color: #f2f2f2; color: #d9534f; padding: 10px; text-align: center; font-weight: bold; margin: 20px 0;'>$password</p>
                    <p style='color: #333; font-size: 16px;'>Please change your password after your first login by clicking the settings logo in the top left of the screen below your name.</p>
                    <p style='text-align: center; margin: 25px 0;'><a href='https://targetmetalsync.com/' style='background-color: #d9534f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login to Your Account</a></p>
                    <p style='color: #333; font-size: 16px;'>If you have any questions, feel free to reach out to your administator.</p>
                    <p style='font-size: 0.9em; color: #666; text-align: center; margin-top: 30px;'>This is an automated message, please do not reply directly to this email.</p>
                </div>
            </div>"
        ]
    ]
];

    // Send the email
    try {
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        if ($response->success()) {
            // Redirect with success message
            header("Location: create_employee.php?email_sent=success");
        } else {
            // Handle failure
            header("Location: create_employee.php?email_sent=failure");
        }
    } catch (Exception $e) {
        // Handle exception
        header("Location: create_employee.php?email_sent=exception");
    }
    exit();
}
?>