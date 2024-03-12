<?php
session_start();

include '../configurations/connection.php'; // Include your database connection script

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['trucking_id'])) {
    $truckingId = $_POST['trucking_id'];
    $status = 'Departed'; // Set status to 'Departed'
    $currentLocation = 'Departed'; // Set current location to 'Departed'

    // Prepare SQL query to update the trucking record
    $sql = "UPDATE trucking SET 
                `status` = ?, 
                `current_location` = ? 
            WHERE trucking_id = ?";

    $stmt = $database->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ssi", 
        $status, 
        $currentLocation, 
        $truckingId);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Truck departure updated successfully.']);
    } else {
        // Handle errors, e.g., print them
        echo json_encode(['success' => false, 'message' => 'Failed to update truck departure.']);
    }

    // Close statement
    $stmt->close();
} else {
    // If not a POST request or trucking_id is not set, send a 400 Bad Request response
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Bad request.']);
}

// Close database connection if you're done with it here
$database->close();
?>