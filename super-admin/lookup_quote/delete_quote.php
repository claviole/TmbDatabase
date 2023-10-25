<?php
ob_start(); // Turn on output buffering
include '../../connection.php';

if (!isset($_GET['invoice_id'])) {
    exit('No invoice ID provided');
}

$invoice_id = $_GET['invoice_id'];

$database->query("DELETE FROM `invoice` WHERE `invoice_id` = '{$invoice_id}'");
$database->query("DELETE FROM `Line_Item` WHERE `invoice_id` = '{$invoice_id}'");
$database->query("DELETE FROM `invoice_files` WHERE `invoice_id` = '{$invoice_id}'");

header('Location: lookup_quote.php');
ob_end_flush(); // Send the output and turn off output buffering
?>