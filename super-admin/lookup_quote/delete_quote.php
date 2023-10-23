<?php
include '../../connection.php';

$invoice_id = $_GET['invoice_id'];

$database->query("DELETE FROM `line_item` WHERE `invoice_id` = '$invoice_id'");
$database->query("DELETE FROM `invoice_files` WHERE `invoice_id` = '$invoice_id'");
$database->query("DELETE FROM `invoice` WHERE `invoice_id` ='$invoice_id'");

header('Location: lookup_quote.php');
?>