<?php
session_start();
if(!isset($_SESSION['user'])){
    // Not logged in or not an admin, redirect to login page
    header("Location: /index.php");
    exit();
}
include '../../configurations/connection.php'; // Adjust the path as necessary

// Assuming the user's location code is stored in a session variable
$userLocationCode = $_SESSION['location_code'];

// Prepare the SQL query
$query = "SELECT checklist_id, date_completed FROM safety_checklist WHERE location_code = ?";
$stmt = $database->prepare($query);
$stmt->bind_param("s", $userLocationCode);
$stmt->execute();
$result = $stmt->get_result();

$audits = [];
while ($row = $result->fetch_assoc()) {
    $audits[] = $row;
}

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($audits);
?>