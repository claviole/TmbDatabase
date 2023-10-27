<?php
include '../../connection.php';

if(isset($_POST['quoteId']) && isset($_POST['version'])){
    $quoteId = $_POST['quoteId'];
    $version = $_POST['version'];

    // Prepare the statement to update the quote
    $stmt = $database->prepare("UPDATE `invoice` SET `approval_status` = 'Approved' WHERE `invoice_id` = ? AND `version` = ?");
    $stmt->bind_param("ss", $quoteId, $version);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, // or false in case of an error
            'message' => 'Quote Approved Successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false, // or false in case of an error
            'message' => 'Failed to approve quote'
        ]);
    }
}
?>