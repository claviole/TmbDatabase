<?php
include '../../configurations/connection.php';

if(isset($_POST['partNumber'])){
    $partNumber = $_POST['partNumber'];
    $stmt = $database->prepare("SELECT * FROM Part WHERE `Part#` = ?");
    if ($stmt === false) {
        die("Failed to prepare statement: " . $database->error);
    }
    $bind_result = $stmt->bind_param("s", $partNumber);
    if ($bind_result === false) {
        die("Failed to bind parameters: " . $stmt->error);
    }
    $execute_result = $stmt->execute();
    if ($execute_result === false) {
        die("Failed to execute statement: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $partData = $result->fetch_assoc();
    echo json_encode($partData);
}
?>