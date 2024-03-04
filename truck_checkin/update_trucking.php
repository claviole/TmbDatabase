<?php
session_start();

include '../configurations/connection.php'; // Include your database connection script

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['trucking_id'])) {
    $truckingId = $_POST['trucking_id'];
    $loadNumber = $_POST['load_number'];
    $weight = $_POST['weight'];
    $bay_location = $_POST['bay_location'];
    $truckNumber = $_POST['truck_number'];
    $phoneNumber = $_POST['phone_number'];
    $appointmentDate = $_POST['appointment_date'];
    $appointmentTime = $_POST['appointment_time'];
    $status = 'checked in'; // Set status to 'checked in'
    $confirmationTime = date('Y-m-d H:i:s'); // Current date and time

    // Prepare SQL query to update the trucking record
    $sql = "UPDATE trucking SET 
                load_number = ?, 
                `weight` = ?,
                `bay_location` = ?, 
                truck_number = ?, 
                phone_number = ?, 
                appointment_date = ?, 
                appointment_time = ?, 
                `status` = ?, 
                confirmation_time = ? 
            WHERE trucking_id = ?";

    $stmt = $database->prepare($sql);

    // Bind parameters
    $stmt->bind_param("issssssssi", 
        $loadNumber, 
        $weight,
        $bay_location, 
        $truckNumber, 
        $phoneNumber, 
        $appointmentDate, 
        $appointmentTime, 
        $status, 
        $confirmationTime, 
        $truckingId);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Trucking record updated successfully.']);
    } else {
        // Handle errors, e.g., print them
        echo json_encode(['success' => false, 'message' => 'Failed to update trucking record.']);
    }

    // Close statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
}

// Close database connection if you're done with it here
$database->close();
?>