<?php
session_start();
include '../../../connection.php'; // Assuming you have a db_connection.php file for database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form data
    $email = $_POST['email'];

    // Generate a random 6 character password
    $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);

    // Prepare an SQL statement
    $stmt = $database->prepare("UPDATE Users SET password = ? WHERE email = ?");

    // Bind the parameters
    $stmt->bind_param("ss", $newPassword, $email);

    // Execute the statement
    $stmt->execute();

    // Close the statement
    $stmt->close();

    // Return the new password
    echo $newPassword;
}
?>