<?php
include '../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare a DELETE statement
    $stmt = $database->prepare("DELETE FROM `employees` WHERE `employee_id` = ?");

    // Bind the id to the statement
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // If the query was successful, send a JSON response with a success status
        echo json_encode(['status' => 'success', 'message' => 'Employee deleted successfully.']);
    } else {
        // If the query failed, send a JSON response with an error status
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete employee.']);
    }

    $stmt->close();
} else {
    // If no id was provided, send a JSON response with an error status
    echo json_encode(['status' => 'error', 'message' => 'No employee ID provided.']);
}
?>