<?php
include '../../connection.php';
session_start();
// Get the new values from the AJAX request
$partNumber = $_POST['partNumber'];
$blankingCost = $_POST['blankingCost'];
$packagingCost = $_POST['packagingCost'];
$freightCost= $_POST['freightCost'];
$totalCost = $blankingCost + $packagingCost + $freightCost;
// Connect to the database


// Prepare an SQL statement to update the row
$stmt = $database->prepare("UPDATE `Line_Item` SET `Blanking Per Piece Cost`= ?,`Packaging Per Piece Cost` = ?,`Total Cost per Piece` = ?,`freight per piece cost`=? WHERE `Part#` = ?");

// Bind the new values to the SQL statement

$stmt->bind_param('dddds', $blankingCost, $packagingCost, $totalCost,$freightCost, $partNumber);


// Execute the SQL statement
$stmt->execute();
?>