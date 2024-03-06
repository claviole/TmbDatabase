<?php
session_start();
include '../../../configurations/connection.php'; // Adjust the path as necessary

header('Content-Type: application/json');

if(!isset($_SESSION['user'])){
    // Not logged in or not an admin, redirect to login page
    header("Location: /index.php");
    exit();
}
// Check if an accident ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Accident ID is required.']);
    exit;
}

$accidentId = $_GET['id'];

// Prepare the SELECT statement to fetch accident details
$stmt = $database->prepare("SELECT accident_report.*, employees.employee_fname, employees.employee_lname FROM `accident_report` JOIN `employees` ON accident_report.employee_id = employees.employee_id WHERE accident_report.accident_id = ?");

// Bind the accident ID parameter
$stmt->bind_param('i', $accidentId);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch the data
$data = $result->fetch_assoc();

if ($data) {
    // Return the accident details
    echo json_encode(['status' => 'success', 'data' => $data]);
} else {
    // If no data found for the given accident ID
    echo json_encode(['status' => 'error', 'message' => 'No accident found with the provided ID.']);
}

$stmt->close();
$database->close();
?>