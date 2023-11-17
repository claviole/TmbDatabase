<?php
include '../../connection.php';

if(isset($_POST['invoiceId'])){
    $invoiceId = $_POST['invoiceId'];

    // Prepare the statement to delete from Line_Item table
    $stmt = $database->prepare("DELETE FROM Line_Item WHERE invoice_id = ?");
    $stmt->bind_param("i", $invoiceId);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "Line items deleted successfully. ";
    } else {
        echo "No line items found for this invoice or error deleting line items. ";
    }

    // Prepare the statement to delete from invoice table
    $stmt = $database->prepare("DELETE FROM invoice WHERE invoice_id = ?");
    $stmt->bind_param("i", $invoiceId);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "Invoice deleted successfully.";

        // Get the new maximum invoice_id
        $result = $database->query("SELECT MAX(invoice_id) as max_invoice_id FROM invoice");
        $new_max_invoice_id = $result->fetch_assoc()['max_invoice_id'] + 1;

        // Update the AUTO_INCREMENT value of the invoice_id column
        $database->query("ALTER TABLE invoice AUTO_INCREMENT = $new_max_invoice_id");
    } else {
        echo "Error deleting invoice.";
    }
}
?>