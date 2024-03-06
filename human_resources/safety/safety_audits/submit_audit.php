<?php
session_start();
include '../../../configurations/connection.php';

// Check if the user is logged in and has the correct user type
if (!isset($_SESSION['user']) || $_SESSION['user_type'] != 'human-resources' && $_SESSION['user_type'] != 'super-admin' && $_SESSION['user_type'] != 'supervisor') {
    header("Location: /index.php");
    exit();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input from the form
    $safety_auditor = mysqli_real_escape_string($database, $_POST['safety_auditor']);
    $date_completed = mysqli_real_escape_string($database, $_POST['date_completed']);
    $facility = mysqli_real_escape_string($database, $_POST['facility']);
    $department = mysqli_real_escape_string($database, $_POST['department']);
    $additional_comments = mysqli_real_escape_string($database, $_POST['additional_comments']);
    $location_code = mysqli_real_escape_string($database, $_POST['location_code']);

    // Insert into the safety_checklist table
    $checklist_query = "INSERT INTO safety_checklist (safety_auditor, date_completed, facility, department, additional_comments, location_code) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($database, $checklist_query);
    mysqli_stmt_bind_param($stmt, "ssssss", $safety_auditor, $date_completed, $facility, $department, $additional_comments, $location_code);
    mysqli_stmt_execute($stmt);
    $checklist_id = mysqli_insert_id($database); // Get the last inserted ID

   // Insert into the safety_checklist_answers table
for ($i = 1; $i <= 28; $i++) { // Adjust the number based on your total questions
    $question_text = mysqli_real_escape_string($database, $_POST["question{$i}_text"]);
    $ranking = isset($_POST["question{$i}_ranking"]) ? $_POST["question{$i}_ranking"] : null;
    $a_one = mysqli_real_escape_string($database, $_POST["question{$i}_a_one"]);
    $a_two = mysqli_real_escape_string($database, $_POST["question{$i}_a_two"]);
    $a_three = mysqli_real_escape_string($database, $_POST["question{$i}_a_three"]);

    $answers_query = "INSERT INTO safety_checklist_answers (checklist_id, question, ranking, a_one, a_two, a_three) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($database, $answers_query);
    mysqli_stmt_bind_param($stmt, "isssss", $checklist_id, $question_text, $ranking, $a_one, $a_two, $a_three);
    mysqli_stmt_execute($stmt);
}

    echo json_encode(['success' => true]);
  
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}
?>