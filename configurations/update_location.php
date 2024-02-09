<?php
// Start the session to access session variables
session_start();

// Include your database connection script
include 'connection.php'; // Adjust the path as necessary to match your project structure

// Check if the user is a super-admin and the location_code is set in the POST request
if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'super-admin' && isset($_POST['location_code'])) {
    // Sanitize the input to prevent SQL Injection
    $location_code = $database->real_escape_string($_POST['location_code']);
    
    // Optionally, update the location in the database for the current user
    // Prepare the SQL statement to update the user's location_code
    $stmt = $database->prepare("UPDATE `Users` SET `location_code` = ? WHERE `id` = ?");
    
    // Bind the parameters to the SQL query
    $stmt->bind_param("si", $location_code, $_SESSION['user_id']);
    
    // Execute the query
    if($stmt->execute()) {
        // If the database is updated successfully, also update the location in the session
        $_SESSION['location_code'] = $location_code;
        
        // Return a success message
        echo json_encode(['status' => 'success', 'message' => 'Location updated successfully.']);
    } else {
        // If the database update fails, return an error message
        echo json_encode(['status' => 'error', 'message' => 'Failed to update location in the database.']);
    }
    
    // Close the prepared statement
    $stmt->close();
} else {
    // If the user is not a super-admin or the location_code is not set, return an error message
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access or missing location code.']);
}

// Close the database connection
$database->close();
?>