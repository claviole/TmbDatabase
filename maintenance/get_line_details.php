<?php
session_start();
if(!isset($_SESSION['user'])){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}
include '../configurations/connection.php';

// Get the line_id from the AJAX request
$line_id = isset($_GET['line_id']) ? $_GET['line_id'] : '';

// Prepare and execute the query
$query = "SELECT Line_Location, Line_Name FROM `Lines` WHERE line_id = ?";
$stmt = $database->prepare($query);
$stmt->bind_param("i", $line_id);
$stmt->execute();
$result = $stmt->get_result();
$line_details = $result->fetch_assoc();

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($line_details);
?>