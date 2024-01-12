<?php
session_start();
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
$pepper = "Krdh%RA-kPm1248)v2y52WqE&+b}r7T6p/Jn@.?wA(L8"; // Replace with your actual pepper

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form data
    $email = $_POST['email'];

    // Generate a random 6 character password
    $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);

    // Hash the new password
    $hashedPassword = password_hash($pepper . $newPassword, PASSWORD_BCRYPT);

    // Prepare an SQL statement
    $stmt = $database->prepare("UPDATE Users SET password = ? WHERE email = ?");

    // Bind the parameters
    $stmt->bind_param("ss", $hashedPassword, $email);

    // Execute the statement
    $stmt->execute();

    // Close the statement
    $stmt->close();

    // Return the new password
    echo $newPassword;
}
?>