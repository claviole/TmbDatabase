<?php
// Include your database connection file here
include '../configurations/connection.php';

// Get the POST data and sanitize it
$date_used = mysqli_real_escape_string($database, $_POST['date_used']);
$orange_tag_id = mysqli_real_escape_string($database, $_POST['orange_tag_id']);
$part_description = mysqli_real_escape_string($database, $_POST['part_description']);
$quantity = mysqli_real_escape_string($database, $_POST['quantity']);
$brand_name = mysqli_real_escape_string($database, $_POST['brand_name']);
$model_number = mysqli_real_escape_string($database, $_POST['model_number']);
$serial_number = mysqli_real_escape_string($database, $_POST['serial_number']);
$dimensions = mysqli_real_escape_string($database, $_POST['dimensions']);

// Prepare the SQL query
$query = "INSERT INTO repair_parts (date_used, orange_tag_id, part_description, quantity, brand_name, model_number, serial_number, dimensions) 
          VALUES ('$date_used', '$orange_tag_id', '$part_description', '$quantity', '$brand_name', '$model_number', '$serial_number', '$dimensions')";

// Execute the query
if (mysqli_query($database, $query)) {
    // If the query was successful, return the new part as a JSON object
    echo json_encode($_POST);
} else {
    // If the query failed, return an error message
    echo json_encode(array('error' => 'Could not add part to the database.'));
}
?>