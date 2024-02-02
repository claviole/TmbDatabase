<?php
session_start();
if(!isset($_SESSION['user'])){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}
// Include your database connection file here
include '../configurations/connection.php';

// Get the orange_tag_id from the GET request
$orange_tag_id = $_GET['orange_tag_id'];

// Prepare the SQL query
$query = "SELECT * FROM parts WHERE orange_tag_id = ?";

// Prepare a statement
if ($stmt = $database->prepare($query)) {
    // Bind the orange_tag_id to the statement
    $stmt->bind_param("i", $orange_tag_id);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch all rows as an associative array
    $parts = $result->fetch_all(MYSQLI_ASSOC);

    // Close the statement
    $stmt->close();
} else {
    // Handle error - notify the user that the query failed
    echo "Error: " . $database->error;
}

// Return the parts as a JSON object
echo json_encode($parts);
?>