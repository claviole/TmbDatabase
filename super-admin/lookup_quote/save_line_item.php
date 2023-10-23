<?php
include '../../connection.php';
session_start();
// Get the new values from the AJAX request
$partNumber = $_POST['partNumber'];
$blankingCost = $_POST['blankingCost'];
$packagingCost = $_POST['packagingCost'];
$totalCost = $_POST['totalCost'];

// Connect to the database


// Prepare an SQL statement to update the row
$stmt = $database->prepare("UPDATE `line_item` SET `Blanking Per Piece Cost`= ?,`Packaging Per Piece Cost` = ?,`Total Cost per Piece` = ? WHERE `Part#` = ?");

// Bind the new values to the SQL statement

$stmt->bind_param('ddds', $blankingCost, $packagingCost, $totalCost, $partNumber);


// Execute the SQL statement
$stmt->execute();
?>