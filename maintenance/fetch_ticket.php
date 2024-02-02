<?php
session_start();
if(!isset($_SESSION['user'])){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}
// Include your database connection file here
include '../configurations/connection.php';

// Sanitize input
$orange_tag_id = mysqli_real_escape_string($database, $_GET['orange_tag_id']);

// Fetch the data from the database
$query = "SELECT * FROM orange_tag WHERE orange_tag_id = '$orange_tag_id'";
$result = mysqli_query($database, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    // Return the data as a JSON object
    echo json_encode($row);
} else {
    echo "Error: " . mysqli_error($database);
}
?>