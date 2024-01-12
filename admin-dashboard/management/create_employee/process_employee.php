<?php
session_start();
include '../../../connection.php'; // Assuming you have a db_connection.php file for database connection


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
    }
    else if($user_role == "Human Resources")
    {
        $user_role = "Human Resources";
    }
    
    // Generate a random 6 character password
    $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);

    // Combine the first name and last name into a username
    $username = $first_name . ' ' . $last_name;

    // Prepare an SQL statement
    $stmt = $database->prepare("INSERT INTO `Users` (`username`, `email`, `password`, `user_type`) VALUES (?, ?, ?, ?)");

    // Bind the parameters
    $stmt->bind_param("ssss", $username, $email, $password, $user_role);

    // Execute the statement
   // After data insertion
$stmt->execute();

// Redirect to create_employee.php with the generated password as a query parameter
header("Location: create_employee.php?temp_password=$password");
exit();
}
?>