<?php
session_start();
include '../../connection.php';

$currentPassword = $_POST['current-password'];
$newPassword = $_POST['new-password'];

// Verify the current password
$result = $database->query("SELECT `password` FROM `Users` WHERE `username` = '". $_SESSION['user'] ."'");
$row = $result->fetch_assoc();

if ($currentPassword == $row['password'] ) {
    // Update the password
    $database->query("UPDATE Users SET `password` = '$newPassword' WHERE username = '" . $_SESSION['user'] . "'");

    echo json_encode(['status' => 'success', 'message' => 'Password changed successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'The current password is incorrect.']);
}
?>