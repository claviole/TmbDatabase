<?php
include '../../../configurations/connection.php';
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('human-resources' || 'super-admin' || 'supervisor')){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../../../index.php");
    exit();
}
// Fetch the most recently added employee
$result = $database->query("SELECT * FROM `employees` ORDER BY `employee_id` DESC LIMIT 1");
$employee = $result->fetch_assoc();

// Check the employee's job title and create the appropriate records in the employee_training table
if ($employee['job_title'] == 1) {
    // Job title is 1, create record for training 1
    $database->query("INSERT INTO `employee_training` (`employee_id`, `training_path_id`) VALUES ({$employee['employee_id']}, 1)");
} elseif ($employee['job_title'] == 2) {
    // Job title is 2, create record for training 2
    $database->query("INSERT INTO `employee_training` (`employee_id`, `training_path_id`) VALUES ({$employee['employee_id']}, 2)");
}
// Add more elseif statements for other job titles as needed

echo json_encode($employee);
?>