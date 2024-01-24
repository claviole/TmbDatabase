<?php
include '../../configurations/connection.php';

$partNumber = $_POST['partNumber'];
$partName = $_POST['partName'];

// Prepare the SQL statement with placeholders
$stmt = $database->prepare("SELECT `part_id`, `supplier_name`,`customer_id`,`Part#`,`Part Name`,`Mill`,`Platform`,`Type``Surface`,`Material Type`,`pallet_type`,`pallet_size`,`pallet_uses`,`Pieces per Lift`,`Stacks per Skid`,`Skids per Truck`,`Scrap Consumption` FROM `Part` WHERE `Part#` = ? AND `Part Name` = ?");
// Bind the parameters to the statement
$stmt->bind_param("ss", $partNumber, $partName);
// Execute the prepared statement
$stmt->execute();
// Get the result of the query
$result = $stmt->get_result();
$part = $result->fetch_assoc();

echo json_encode($part);

$stmt->close();
?>