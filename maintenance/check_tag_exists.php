<?php
include '../configurations/connection.php';

// Assuming $database is a mysqli object
$orange_tag_id = $_GET['orange_tag_id'];

// Prepare the SQL statement with a placeholder for the orange_tag_id
$stmt = $database->prepare("SELECT * FROM `orange_tag` WHERE `orange_tag_id` = ?");

// Bind the $orange_tag_id variable to the placeholder in the prepared statement
$stmt->bind_param("s", $orange_tag_id);

// Execute the prepared statement
$stmt->execute();

// Store the result so we can check the number of rows
$stmt->store_result();

// Prepare the response array based on whether a row was found
$response = array('exists' => $stmt->num_rows > 0 ? true : false);

// Encode the response as JSON and output it
echo json_encode($response);

// Close the statement
$stmt->close();
?>