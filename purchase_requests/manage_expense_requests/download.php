<?php
session_start();

// Check if the user is logged in and is a super-admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'super-admin') {
    die('Access denied: insufficient permissions.');
}

// Sanitize the input to prevent directory traversal attacks
$fileName = basename($_GET['file']);
// Define the base directory where files are stored
$baseDir = realpath($_SERVER['DOCUMENT_ROOT'] . '/uploads');

// Construct the full path to the file
$filePath = $baseDir . DIRECTORY_SEPARATOR . $fileName;

// Validate that the file exists and is within the intended directory
if (strpos(realpath($filePath), $baseDir) === 0 && file_exists($filePath)) {
    // Set headers to force the download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));

    // Clear output buffer
    ob_clean();
    flush();

    // Read the file and output its content
    readfile($filePath);
    exit;
} else {
    die('File not found.');
}
?>