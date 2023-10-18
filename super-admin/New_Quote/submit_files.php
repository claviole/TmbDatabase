<?php
session_start();
include '../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_FILES['invoice_files']) || !isset($_POST['invoice_id'])) {
        echo json_encode(['error' => 'No files or invoice_id received.']);
        exit();
    }

    $invoice_id = $_POST['invoice_id'];
    $fileCount = count($_FILES['invoice_files']['name']);

    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = $_FILES['invoice_files']['name'][$i];
        $fileTmpName = $_FILES['invoice_files']['tmp_name'][$i];

        // Generate a unique name for the file before saving it
        $uniqueFileName = uniqid() . '-' . $fileName;

        // You should create a 'uploads' directory in your project root
        $destination = '../../uploads/' . $uniqueFileName;

        // Move the file
        if (move_uploaded_file($fileTmpName, $destination)) {
            // Read the file contents
            $fileContents = file_get_contents($destination);

            // Insert file info into the database table
            $sql = "INSERT INTO invoice_files (invoice_id, file_name, file_contents) VALUES (?, ?, ?)";
            $stmt = $database->prepare($sql);
            $stmt->bind_param("iss", $invoice_id, $uniqueFileName, $fileContents);
            $stmt->execute();
        }
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>