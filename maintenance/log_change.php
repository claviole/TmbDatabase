<?php
include '../configurations/connection.php';

// log_change.php

session_start();

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the data from the AJAX request
    $formId = $_POST['form_id'] ?? '';
    $fieldId = $_POST['field_id'] ?? '';
    $newValue = $_POST['new_value'] ?? '';
    $user = $_POST['user'] ?? 'unknown'; // Default to 'unknown' if the user is not found
    $timestamp = $_POST['timestamp'] ?? '';
    $orangeTagId = $_POST['orange_tag_id'] ?? '';

    // Include the orange tag ID in the log entry
    $logEntry = "User: {$user}, Orange Tag ID: {$orangeTagId}, Timestamp: {$timestamp}, Form ID: {$formId}, Field ID: {$fieldId}, New Value: {$newValue}\n";

    // Write to a log file (make sure the file is writable by the web server)
    file_put_contents('../configurations/logs/maintenance.log', $logEntry, FILE_APPEND);

    // If you want to log to a database, you would insert the data into a table here
    // ...

    echo 'Change logged successfully';
} else {
    // Handle the error for non-POST requests
    http_response_code(405); // Method Not Allowed
    echo 'Invalid request method';
}