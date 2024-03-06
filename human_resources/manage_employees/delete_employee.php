<?php
include '../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('human-resources' || 'super-admin' || 'supervisor')){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../../index.php");
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare an UPDATE statement
    $stmt = $database->prepare("UPDATE `employees` SET `status` = 'inactive' WHERE `employee_id` = ?");

    // Bind the id to the statement
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // If the query was successful, send a JSON response with a success status
        echo json_encode(['status' => 'success', 'message' => 'Employee status set to inactive successfully.']);
    } else {
        // If the query failed, send a JSON response with an error status
        echo json_encode(['status' => 'error', 'message' => 'Failed to update employee status.']);
    }

    $stmt->close();
} else {
    // If no id was provided, send a JSON response with an error status
    echo json_encode(['status' => 'error', 'message' => 'No employee ID provided.']);
}
?>