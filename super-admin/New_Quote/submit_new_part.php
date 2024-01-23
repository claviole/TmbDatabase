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
$customer_id = $database->real_escape_string($_POST['customer_id']);

$stmt = $database->prepare("INSERT INTO Part (`Part#`,`supplier_name`,`customer_id`, `Part Name`, `Mill`, `Platform`, `Type`, `Surface`, `Material Type`, `pallet_type`, `pallet_size`,`pallet_uses`, `Stacks per Skid`, `Scrap Consumption`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssissssssssidd", $partNumber,$supplier_name,$customer_id, $partName, $mill, $platform, $type, $surface, $materialType, $palletType, $palletSize, $pallet_uses, $stacksPerSkid,  $scrapConsumption);

$stmt->execute();

header('Location: start_new_invoice.php');
?>