<?php
session_start();
include '../../../configurations/connection.php';

if(isset($_POST['submit'])){
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $dateOfHire = $_POST['date-of-hire'];
    $jobTitle = $_POST['job-title'];
    $firstDayOfWork = $_POST['first-day-of-work'];

    $sql = "INSERT INTO `employees` (`employee_fname`, `employee_lname`,`email`,`date_hired`, `job_title`, `first_day_of_work`) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("sssss", $firstName, $lastName, $dateOfHire, $jobTitle, $firstDayOfWork);

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