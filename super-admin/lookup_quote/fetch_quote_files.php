<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['quoteId'])) {
        $quoteId = $_POST['quoteId'];

        // Fetch files for the quote
        $stmt = $database->prepare("SELECT `file_name` FROM `invoice_files` WHERE `invoice_id` = ?");
        $stmt->bind_param("s", $quoteId);
        $stmt->execute();

        $result = $stmt->get_result();
        $files = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($files as $file) {
            echo '<a href="download.php?quoteId=' . $quoteId . '&file_name=' . urlencode($file['file_name']) . '">Download ' . $file['file_name'] . '</a><br>';
        }
    }
}
?>