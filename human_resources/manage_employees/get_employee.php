<?php
include '../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No employee ID provided.']);
    exit;
}

$id = $_GET['id'];

$query = "SELECT * FROM `employees` WHERE `employee_id` = ?";
$stmt = $database->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'No employee found with the provided ID.']);
    exit;
}

$employee = $result->fetch_assoc();

echo json_encode($employee);