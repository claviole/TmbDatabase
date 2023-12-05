<?php
SESSION_START();
include '../../connection.php';

$customerName = $database->real_escape_string($_POST['customerName']);
$customerAddress = $database->real_escape_string($_POST['customerAddress']);
$customerCity = $database->real_escape_string($_POST['customerCity']);
$customerState = $database->real_escape_string($_POST['customerState']);
$customerZip = $database->real_escape_string($_POST['customerZip']);
$customerPhone = $database->real_escape_string($_POST['customerPhone']);
$customerEmail = $database->real_escape_string($_POST['customerEmail']);
$customerContact = $database->real_escape_string($_POST['customerContact']);

$stmt = $database->prepare("INSERT INTO Customer (`Customer Name`, `Customer Address`, `Customer City`, `Customer State`, `Customer Zip`, `Customer Phone`, `Customer Email`, `Customer Contact`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $customerName, $customerAddress, $customerCity, $customerState, $customerZip, $customerPhone, $customerEmail, $customerContact);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'New customer added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
}
?>