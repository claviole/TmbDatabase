<?php
session_start();
include '../configurations/connection.php';
$currentUserLocationCode = $_SESSION['location_code'];

$sql = "SELECT trucking_id, load_number, phone_number, part_number, truck_number, arrival_date, `status` FROM trucking WHERE location_code = ? AND status = 'pending'";
$stmt = $database->prepare($sql);
$stmt->bind_param("s", $currentUserLocationCode);
$stmt->execute();
$result = $stmt->get_result();

$checkIns = [];
while ($row = $result->fetch_assoc()) {
    $checkIns[] = $row;
}

echo json_encode($checkIns);
?>