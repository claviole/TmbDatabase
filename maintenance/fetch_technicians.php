<?php
session_start();
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

header('Content-Type: application/json');
echo json_encode($technicians);
?>