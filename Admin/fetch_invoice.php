<?php
include '../connection.php';

if(isset($_POST['invoiceId'])){
    $invoiceId = $_POST['invoiceId'];
    $stmt = $database->prepare("SELECT invoice.*, Customer.`Customer Name` FROM invoice INNER JOIN Customer ON invoice.customer_id = Customer.customer_id WHERE invoice.invoice_id = ?");
    $stmt->bind_param("i", $invoiceId);
    $stmt->execute();
    $result = $stmt->get_result();
    $invoice = $result->fetch_assoc();

    // Fetch the line items for the invoice
    $stmt = $database->prepare("SELECT * FROM Line_Item WHERE invoice_id = ?");
    $stmt->bind_param("i", $invoiceId);
    $stmt->execute();
    $result = $stmt->get_result();
    $line_items = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['invoice' => $invoice, 'line_items' => $line_items]);
}
?>