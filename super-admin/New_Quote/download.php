<?php
ob_start(); // Start output buffering
include '../../connection.php';
session_start();
if (isset($_GET['quoteId'])) {
 
    $invoice_id = $_GET['quoteId'];
  
    $stmt = $database->prepare("SELECT `file_name`, `file_contents` FROM `invoice_files` WHERE `invoice_id` = ? AND `file_name` = ?");
    $stmt->bind_param("is", $invoice_id, $_GET['file_name']);
    $stmt->execute();

    $result = $stmt->get_result();
    $file = $result->fetch_assoc();

    if ($file) {
        // Get the file contents
        $fileContents = $file['file_contents'];

        // Clean (erase) the output buffer and turn off output buffering
        ob_end_clean();

        // Output headers
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $file['file_name']);
        header('Content-Transfer-Encoding: binary');

        // Output file data
        echo $fileContents;
    }
}
?>