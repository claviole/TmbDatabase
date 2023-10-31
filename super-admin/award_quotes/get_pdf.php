<?php
include '../../connection.php';

$invoice_id = $_GET['invoice_id'];
$version = $_GET['version'];

$stmt = $database->prepare("SELECT `file_name`, `file_contents` FROM `invoice_files` WHERE `invoice_id` = ? AND `file_name` LIKE ?");
$file_name = "%-$version.pdf";
$stmt->bind_param("is", $invoice_id, $file_name);
$stmt->execute();

$result = $stmt->get_result();
$file = $result->fetch_assoc();

if ($file) {
    // Get the file contents
  // Get the file contents
  $fileContents = $file['file_contents'];

  // Output headers
  header('Content-Description: File Transfer');
  header('Content-Type: application/json');
  header('Content-Disposition: attachment; filename=' . $file['file_name']);

    // Output file data
    echo $fileContents;
}
?>