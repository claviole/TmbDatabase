<?php
session_start();
if(!isset($_SESSION['user'])){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../index.php");
    exit();
}
include '../configurations/connection.php';

// Get the location_code from the AJAX request
$locationCode = $_SESSION['location_code'];

$query = "SELECT * FROM Users WHERE user_type = 'maintenance-tech' AND status = 'active' AND location_code = ?";
$stmt = $database->prepare($query);
$stmt->bind_param('s', $locationCode);
$stmt->execute();
$result = $stmt->get_result();

$technicians = array();
while ($row = $result->fetch_assoc()) {
    $technicians[] = array(
        'id' => $row['id'],
        'username' => $row['username']
    );
}

// Assuming the current user's ID and username are stored in $_SESSION
$currentUser = array(
    'id' => $_SESSION['user_id'], // Adjust this according to how user ID is stored
    'username' => $_SESSION['user'] // Adjust this according to how username is stored
);

// Add the current user to the technicians array
array_unshift($technicians, $currentUser); // Adds the current user at the beginning of the array

header('Content-Type: application/json');
echo json_encode($technicians);
?>