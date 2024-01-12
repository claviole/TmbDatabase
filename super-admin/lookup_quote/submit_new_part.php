<?php
include '../../configurations/connection.php';
$partNumber = $database->real_escape_string($_POST['partNumber']);
$supplier_name = $database->real_escape_string($_POST['supplier_name']);
$partName = $database->real_escape_string($_POST['partName']);
$mill = $database->real_escape_string($_POST['mill']);
$platform = $database->real_escape_string($_POST['platform']);
$type = $database->real_escape_string($_POST['type']);
$surface = $database->real_escape_string($_POST['surface']);
$materialType = $database->real_escape_string($_POST['materialType']);
$palletType = $database->real_escape_string($_POST['palletType']);
$palletSize = $database->real_escape_string($_POST['palletSize']);
$pallet_uses = $database->real_escape_string($_POST['pallet_uses']);
$stacksPerSkid = $database->real_escape_string($_POST['stacksPerSkid']);
$scrapConsumption = $database->real_escape_string($_POST['scrapConsumption']);
$stmt = $database->prepare("UPDATE Part SET `supplier_name` = ?, `Part Name` = ?, `Mill` = ?, `Platform` = ?, `Type` = ?, `Surface` = ?, `Material Type` = ?, `pallet_type` = ?, `pallet_size` = ?, `pallet_uses` = ?,  `Stacks per Skid` = ?,  `Scrap Consumption` = ? WHERE `Part#` = ?");
$stmt->bind_param("sssssssssidds", $supplier_name, $partName, $mill, $platform, $type, $surface, $materialType, $palletType, $palletSize, $pallet_uses, $piecesPerLift, $stacksPerSkid, $scrapConsumption, $partNumber);

$stmt->execute();

header('Location: edit_quote.php');
?>