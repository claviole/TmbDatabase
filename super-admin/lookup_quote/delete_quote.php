<?php
ob_start(); // Turn on output buffering
include '../../configurations/connection.php';

if (!isset($_GET['invoice_id'])) {
    exit('No invoice ID provided');
}

$invoice_id = $_GET['invoice_id'];

// Prepare the SQL statement for invoice deletion
$stmt_invoice = $database->prepare("DELETE FROM `invoice` WHERE `invoice_id` = ?");
$stmt_invoice->bind_param("s", $invoice_id);
$stmt_invoice->execute();
$stmt_invoice->close();

// Prepare the SQL statement for Line_Item deletion
$stmt_line_item = $database->prepare("DELETE FROM `Line_Item` WHERE `invoice_id` = ?");
$stmt_line_item->bind_param("s", $invoice_id);
$stmt_line_item->execute();
$stmt_line_item->close();

// Prepare the SQL statement for invoice_files deletion
$stmt_invoice_files = $database->prepare("DELETE FROM `invoice_files` WHERE `invoice_id` = ?");
$stmt_invoice_files->bind_param("s", $invoice_id);
$stmt_invoice_files->execute();
$stmt_invoice_files->close();

header('Location: lookup_quote.php');
ob_end_flush(); // Send the output and turn off output buffering
?>