<?php
include '../../connection.php';

$partNumber = $_POST['partNumber'];
$partName = $_POST['partName'];

$result = $database->query("SELECT `part_id`,`supplier_name`,`customer_id`,`Part#`,`Part Name`,`Mill`,`Platform`,`Type`,`Surface`,`Material Type`,`pallet_type`,`pallet_size`,`pallet_uses`,`Pieces per Lift`,`Stacks per Skid`,`Skids per Truck`,`Scrap Consumption` FROM Part WHERE `Part#` = '$partNumber' AND `Part Name` = '$partName'");
$part = $result->fetch_assoc();

echo json_encode($part);
?>