<?php
session_start();
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user_type'] != 'super-admin') {
    // Not logged in or not an admin, redirect to login page
    header("Location: ../../../index.php");
    exit();
}

include '../../../configurations/connection.php'; // Database connection
$pepper = $PEPPER; // Your actual pepper for password hashing

// Include Mailjet Client
use \Mailjet\Resources;
require '../../../configurations/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $user_role = $_POST['user_role']; // Assuming this is posted from your form
    $location_code = $_POST['location_code']; // Assuming this is posted from your form

    // Check if the email already exists in the system
    $stmt = $database->prepare("SELECT `status` FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['status'] === 'inactive') {
            // User exists but is inactive, display Sweet Alert message
            echo "<script type='text/javascript'>
                    Swal.fire({
                        title: 'Account Exists',
                        text: 'That email address is already in use on another account, please submit an inquiry if this was a mistake',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'create_employee.php';
                        }
                    });
                  </script>";
            exit();
        } else {
           // User exists and is active, redirect with an error message
    header("Location: create_employee.php?error=email_in_use&active=true");
    exit();
        }
    }
    // Generate a random 6 character password
    $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);

    // Hash the password
    $hashedPassword = password_hash($pepper . $password, PASSWORD_BCRYPT);

    // Combine the first name and last name into a username
    $username = $first_name . ' ' . $last_name;

    // Prepare an SQL statement to insert the new user
    $stmt = $database->prepare("INSERT INTO `Users` (`username`, `email`, `password`, `user_type`, `location_code`) VALUES (?, ?, ?, ?, ?)");

    // Bind the parameters
    $stmt->bind_param("sssss", $username, $email, $hashedPassword, $user_role, $location_code);

    // Execute the statement and check if the user was successfully inserted
    if ($stmt->execute()) {
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
            header("Location: create_employee.php?email_sent=success");
        } else {
            header("Location: create_employee.php?email_sent=failure");
        }
    } catch (Exception $e) {
        header("Location: create_employee.php?email_sent=exception&message=" . urlencode($e->getMessage()));
    }
} else {
    // Handle error when user insertion fails
    header("Location: create_employee.php?error=database_error");
}
exit();
}
?>