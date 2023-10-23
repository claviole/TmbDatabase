<?php
include '../../connection.php';

if(isset($_POST['quoteId'])){
    $quoteId = $_POST['quoteId'];

    // Prepare the statement to update the quote
    $stmt = $database->prepare("UPDATE invoice SET approval_status = 'Denied' WHERE invoice_id = ?");
    $stmt->bind_param("s", $quoteId);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Quote denied successfully.";
    } else {
        echo "Error denying quote.";
    }
}
?>