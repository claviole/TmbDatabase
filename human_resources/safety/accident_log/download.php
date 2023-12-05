<?php
include '../../../connection.php';

$accidentId = $_GET['accidentId'];
$fileName = urldecode($_GET['fileName']);

$query = "SELECT file_path FROM accident_files WHERE accident_id = $accidentId AND file_name = '$fileName'";
$result = mysqli_query($database, $query);
$file = mysqli_fetch_assoc($result);

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