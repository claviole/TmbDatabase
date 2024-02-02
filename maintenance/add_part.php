<?php

// Include your database connection file here
include '../configurations/connection.php';
session_start();
if(!isset($_SESSION['user'])){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}

// Get the POST data
$date_used = $_POST['date_used'];
$orange_tag_id = $_POST['orange_tag_id'];
$part_description = $_POST['part_description'];
$quantity = $_POST['quantity'];
$brand_name = $_POST['brand_name'];
$model_number = $_POST['model_number'];
$serial_number = $_POST['serial_number'];
$dimensions = $_POST['dimensions'];

// Prepare the SQL query
$query = "INSERT INTO repair_parts (date_used, orange_tag_id, part_description, quantity, brand_name, model_number, serial_number, dimensions) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($database, $query);

// Bind parameters
mysqli_stmt_bind_param($stmt, 'sssissss', $date_used, $orange_tag_id, $part_description, $quantity, $brand_name, $model_number, $serial_number, $dimensions);

// Execute the query
if (mysqli_stmt_execute($stmt)) {
    // If the query was successful, return the new part as a JSON object
    echo json_encode($_POST);
} else {
    // If the query failed, return an error message
    echo json_encode(array('error' => 'Could not add part to the database.'));
}
?>