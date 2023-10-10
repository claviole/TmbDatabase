<?php
include '../../connection.php';

$partNumber = $database->real_escape_string($_POST['partNumber']);
$partName = $database->real_escape_string($_POST['partName']);
$mill = $database->real_escape_string($_POST['mill']);
$platform = $database->real_escape_string($_POST['platform']);
$type = $database->real_escape_string($_POST['type']);
$surface = $database->real_escape_string($_POST['surface']);
$materialType = $database->real_escape_string($_POST['materialType']);
$palletType = $database->real_escape_string($_POST['palletType']);
$palletSize = $database->real_escape_string($_POST['palletSize']);
$pallet_uses = $database->real_escape_string($_POST['pallet_uses']);
$piecesPerLift = $database->real_escape_string($_POST['piecesPerLift']);
$stacksPerSkid = $database->real_escape_string($_POST['stacksPerSkid']);
$skidsPerTruck = $database->real_escape_string($_POST['skidsPerTruck']);
$scrapConsumption = $database->real_escape_string($_POST['scrapConsumption']);

$stmt = $database->prepare("INSERT INTO Part (`Part#`, `Part Name`, `Mill`, `Platform`, `Type`, `Surface`, `Material Type`, `pallet_type`, `pallet_size`,`pallet_uses`, `Pieces per Lift`, `Stacks per Skid`, `Skids per Truck`, `Scrap Consumption`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssiidd", $partNumber, $partName, $mill, $platform, $type, $surface, $materialType, $palletType, $palletSize, $pallet_uses, $piecesPerLift, $stacksPerSkid, $skidsPerTruck, $scrapConsumption);

$stmt->execute();

header('Location: start_new_invoice.php');
?>