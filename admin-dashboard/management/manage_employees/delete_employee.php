<?php
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
session_start();
// Check if the user is logged in and is an admin
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != 'super-admin'){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../../../index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the id from the form data
    $id = $_POST['id'];

    // Prepare an update statement
    $stmt = $database->prepare("UPDATE Users SET `status` = 'inactive' WHERE id = ?");

    // Bind the id to the statement
    $stmt->bind_param("i", $id);

    // Execute the statement
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Employee status set to inactive successfully";
    } else {
        echo "No changes made. Employee may already be inactive or does not exist.";
    }

    // Close the statement
    $stmt->close();
}
?>