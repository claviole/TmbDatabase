<?php
include '../../connection.php';

if(isset($_POST['invoiceId'])){
    $invoiceId = $_POST['invoiceId'];

    // Prepare the statement to update the invoice
    $stmt = $database->prepare("UPDATE invoice SET approval_status = 'Approved' WHERE invoice_id = ?");
    $stmt->bind_param("i", $invoiceId);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Invoice approved successfully.";
    } else {
        echo "Error approving invoice.";
    }
}
?>