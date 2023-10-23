<?php
include '../../connection.php';
session_start();
if (isset($_GET['quoteId'])) {
 
    $invoice_id = $_GET['quoteId'];
  
    $stmt = $database->prepare("SELECT `file_name`, `file_contents` FROM `invoice_files` WHERE `invoice_id` = ? AND `file_name` = ?");
$stmt->bind_param("ss", $invoice_id, $_GET['file_name']);
    $stmt->execute();

    $result = $stmt->get_result();
    $file = $result->fetch_assoc();

    if ($file) {
        // Get the file contents
        $fileContents = $file['file_contents'];

        // Output headers
        header('Content-Description: File Transfer');
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename=' . $file['file_name']);

        // Output file data
        echo $fileContents;
    }
}
?>