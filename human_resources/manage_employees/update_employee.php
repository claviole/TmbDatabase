<?php
// Start session and include database connection

session_start();
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('human-resources' || 'super-admin' || 'supervisor')){
    // Not logged in or not an admin, redirect to login page
    header("Location: /index.php");
    exit();
}
include '../../configurations/connection.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $employee_id = $_POST['employee_id'];
    $employee_fname = $_POST['employee_fname'];
    $employee_lname = $_POST['employee_lname'];
    $date_hired = $_POST['date_hired'];
    $first_day_of_work = $_POST['first_day_of_work'];
    $job_title = $_POST['job_title'];

    // Prepare the SQL statement with placeholders
    $stmt = $database->prepare("UPDATE `employees` SET `employee_fname` = ?, `employee_lname` = ?, `date_hired` = ?, `first_day_of_work` = ?, `job_title` = ? WHERE `employee_id` = ?");
    // Bind the parameters to the statement
    $stmt->bind_param("sssssi", $employee_fname, $employee_lname, $date_hired, $first_day_of_work, $job_title, $employee_id);
    // Execute the prepared statement
    if ($stmt->execute()) {
        // If the query was successful, send a JSON response with a success status
        echo json_encode(['status' => 'success', 'message' => 'Employee updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update employee.']);
    }

    // Close the statement
    $stmt->close();
    // Close the connection
    $database->close();
}
?>