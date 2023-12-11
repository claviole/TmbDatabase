<?php
// Start session and include database connection

session_start();
include '../../connection.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $employee_id = $_POST['employee_id'];
    $employee_fname = $_POST['employee_fname'];
    $employee_lname = $_POST['employee_lname'];
    $date_hired = $_POST['date_hired'];
    $first_day_of_work = $_POST['first_day_of_work'];
    $job_title = $_POST['job_title'];

    // Sanitize the data
    $employee_id = $database->real_escape_string($employee_id);
    $employee_fname = $database->real_escape_string($employee_fname);
    $employee_lname = $database->real_escape_string($employee_lname);
    $date_hired = $database->real_escape_string($date_hired);
    $first_day_of_work = $database->real_escape_string($first_day_of_work);
    $job_title = $database->real_escape_string($job_title);

    // Prepare the SQL statement
    $sql = "UPDATE `employees` SET `employee_fname` = '$employee_fname', `employee_lname` = '$employee_lname', `date_hired` = '$date_hired', `first_day_of_work` = '$first_day_of_work', `job_title` = '$job_title' WHERE `employee_id` = $employee_id";

    // Execute the SQL statement
    if ($database->query($sql) === TRUE) {
      // If the query was successful, send a JSON response with a success status
      echo json_encode(['status' => 'success', 'message' => 'Employee updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update employee.']);
    }

    // Close the connection
    $database->close();
}
?>