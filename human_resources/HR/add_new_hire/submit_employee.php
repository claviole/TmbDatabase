<?php
if(!isset($_SESSION['user']) || $_SESSION['user_type'] != ('human-resources' || 'super-admin' || 'supervisor')){
    // Not logged in or not an admin, redirect to login page
    header("Location: /index.php");
    exit();
}
include '../../../configurations/connection.php';

if(isset($_POST['submit'])){
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $dateOfHire = $_POST['date-of-hire'];
    $jobTitle = (int) $_POST['job-title'];
    $firstDayOfWork = $_POST['first-day-of-work'];
    $location_code = $_POST['location_code'];

    $sql = "INSERT INTO `employees` (`employee_fname`, `employee_lname`,`email`,`date_hired`, `job_title`, `first_day_of_work`,`location_code`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("ssssiss", $firstName, $lastName,$email, $dateOfHire, $jobTitle, $firstDayOfWork, $location_code);

    if($stmt->execute()){
        $_SESSION['success'] = "New employee added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $database->error;
    }

    // Redirect back to the form page
    header("Location: index.php");
    exit();
}
?>