<?php
session_start();
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
$pepper = "Krdh%RA-kPm1248)v2y52WqE&+b}r7T6p/Jn@.?wA(L8"; // Replace with your actual pepper

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $user_role = $_POST['user_role'];

    // Check the user role and assign appropriate value
    if ($user_role == "Sales") {
        $user_role = "Sales";
    } else if ($user_role == "Management") {
        $user_role = "super-admin";
    } else if($user_role == "Human Resources") {
        $user_role = "Human Resources";
    }
    
    // Generate a random 6 character password
    $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);

    // Hash the password
    $hashedPassword = password_hash($pepper . $password, PASSWORD_BCRYPT);

    // Combine the first name and last name into a username
    $username = $first_name . ' ' . $last_name;

    // Prepare an SQL statement
    $stmt = $database->prepare("INSERT INTO `Users` (`username`, `email`, `password`, `user_type`) VALUES (?, ?, ?, ?)");

    // Bind the parameters
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $user_role);

    // Execute the statement
    $stmt->execute();

    // Redirect to create_employee.php with the generated password as a query parameter
    header("Location: create_employee.php?temp_password=$password");
    exit();
}
?>