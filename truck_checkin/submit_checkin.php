<?php
session_start();
include '../configurations/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $locationCode = $database->real_escape_string($_POST['location_code']);
    $loadNumber = $database->real_escape_string($_POST['load_number']);
    $truckNumber = $database->real_escape_string($_POST['truck_number']);
    $phoneNumber = $database->real_escape_string($_POST['phone_number']);
    $arrivalDate = $database->real_escape_string($_POST['arrival_date']);
    $part_number = $database->real_escape_string($_POST['part_number']);

    // SQL query to insert data
    $query = "INSERT INTO trucking (location_code, load_number,`part_number`, truck_number, phone_number, arrival_date) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $database->prepare($query)) {
        $stmt->bind_param("sissss", $locationCode, $loadNumber,$part_number, $truckNumber, $phoneNumber, $arrivalDate);
        if ($stmt->execute()) {
            echo "Success";
        } else {
            // Handle error
            http_response_code(500);
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Handle error
        http_response_code(500);
        echo "Error preparing statement: " . $database->error;
    }
    $database->close();
} else {
    // Not a POST request
    http_response_code(400);
    echo "Invalid request";
}
?>