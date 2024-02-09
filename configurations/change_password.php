<?php
session_start();
include 'connection.php';

$currentPassword = $_POST['current-password'];
$newPassword = $_POST['new-password'];
$pepper = $PEPPER; // Replace with your actual pepper

// Prepare the statement to verify the current password
$stmt = $database->prepare("SELECT `password` FROM `Users` WHERE `username` = ?");
$stmt->bind_param("s", $_SESSION['user']);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (password_verify($pepper . $currentPassword, $row['password'])) {
    // Check if the new password meets complexity requirements
    if (strlen($newPassword) >= 8 && preg_match('/[A-Z]/', $newPassword) && preg_match('/[a-z]/', $newPassword) && preg_match('/[0-9]/', $newPassword) && preg_match('/[\W]/', $newPassword)) {
        // Prepare the statement to update the password
        $hashedPassword = password_hash($pepper . $newPassword, PASSWORD_BCRYPT);
        $stmt = $database->prepare("UPDATE Users SET `password` = ? WHERE username = ?");
        $stmt->bind_param("ss", $hashedPassword, $_SESSION['user']);

        // Execute the statement
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Password changed successfully.']);
    } else {
        $error_message = "The new password does not meet the complexity requirements. It must be:\n" .
                         "- At least 8 characters long\n" .
                         "- Contain at least one uppercase letter\n" .
                         "- Contain at least one lowercase letter\n" .
                         "- Contain at least one number\n" .
                         "- Contain at least one special character.";
    
        echo json_encode(['status' => 'error', 'message' => $error_message]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'The current password is incorrect.']);
}
?>