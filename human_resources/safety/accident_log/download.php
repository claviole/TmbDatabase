<?php
include '../../../configurations/connection.php';
session_start();
if(!isset($_SESSION['user'])){
    // Not logged in or not an admin, redirect to login page
    header("Location: ../../index.php");
    exit();
}
$accidentId = $_GET['accidentId'];
$fileName = urldecode($_GET['fileName']);

// Prepare the SQL statement with placeholders
$stmt = $database->prepare("SELECT file_path FROM accident_files WHERE accident_id = ? AND file_name = ?");
// Bind the parameters to the statement
$stmt->bind_param("is", $accidentId, $fileName);
// Execute the prepared statement
$stmt->execute();
// Get the result of the query
$result = $stmt->get_result();
$file = $result->fetch_assoc();

if ($file) {
    $filePath = $file['file_path'];

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "File not found: $filePath";
    }
}
?>