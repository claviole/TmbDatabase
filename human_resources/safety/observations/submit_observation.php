<?php
include '../../../configurations/connection.php';
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('human-resources' || 'super-admin'|| 'supervisor')){
    // Not logged in or not an admin, redirect to login page
    header("Location: /index.php");
    exit();
}
$observation_score = $_POST['observation_score'];
$employee_id = $_POST['employee_id'];
$observation_date = $_POST['observation_date'];
$observation_time = $_POST['observation_time'];
$observation_description = $_POST['observation_description'];

$query = "INSERT INTO observations (observation_score, employee_id, observation_date, observation_time, observation_description) VALUES (?, ?, ?, ?, ?)";

$stmt = $database->prepare($query);
$stmt->bind_param("iisss", $observation_score, $employee_id, $observation_date, $observation_time, $observation_description);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}