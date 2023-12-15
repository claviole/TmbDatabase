<?php
include '../../connection.php';
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
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($filePath));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));

            // Output file data
            readfile($filePath);
            exit;
        } else {
            echo "File not found: $filePath";
        }
    }
}
?>