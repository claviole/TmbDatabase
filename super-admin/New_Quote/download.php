<?php
include '../../configurations/connection.php';
session_start();
if (isset($_GET['quoteId'])) {
 
    $invoice_id = $_GET['quoteId'];
  
    $stmt = $database->prepare("SELECT `file_name`, `file_path` FROM `invoice_files` WHERE `invoice_id` = ? AND `file_name` = ?");
    $stmt->bind_param("ss", $invoice_id, $_GET['file_name']);
    $stmt->execute();

    $result = $stmt->get_result();
    $file = $result->fetch_assoc();

    if ($file) {
        // Get the file path
        $filePath = $file['file_path'];

        // Check if the file exists
        if (file_exists($filePath)) {
            // Output headers
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename=' . $file['file_name']);
            header('Content-Transfer-Encoding: binary');

            // Output file data
            readfile($filePath);
        } else {
            echo "File not found: $filePath";
        }
    }
}
?>