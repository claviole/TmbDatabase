<?php
include '../../../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the id from the form data
    $id = $_POST['id'];

    // Prepare a delete statement
    $stmt = $database->prepare("DELETE FROM Users WHERE id = ?");

    // Bind the id to the statement
    $stmt->bind_param("i", $id);

    // Execute the statement
    $stmt->execute();

    // Close the statement
    $stmt->close();

    // Return a success message
    echo "Employee deleted successfully";
}
?>