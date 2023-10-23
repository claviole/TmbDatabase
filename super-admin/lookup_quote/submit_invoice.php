<?php
session_start();
include '../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['wash_and_lube'])) {
        // The "Wash and Lube" checkbox was checked
        $wash_and_lube = true;
    } else {
        // The "Wash and Lube" checkbox was not checked
        $wash_and_lube = false;
    }
}

$data = json_decode(file_get_contents('php://input'), true);
error_log(print_r($data, true));  // Add this line
if ($data === null) {
    echo json_encode(['error' => 'No JSON data received.']);
    exit();
}

if (!isset($data['customer']) || !isset($data['parts'])) {
    echo json_encode(['error' => 'Invalid JSON data received.']);
    exit();
}
if (!isset($data['invoice_author'])) {
    echo json_encode(['error' => 'invoice_author not provided.']);
    exit();
}
$customer = $database->real_escape_string($data['customer']);

$invoiceDate = $database->real_escape_string($data['invoiceDate']);
$customerId = $database->real_escape_string($data['customerId']);
$author_fullname = $database->real_escape_string($data['invoice_author']);
$author_parts = explode(' ', $author_fullname);
$author = $author_parts[0][0] . $author_parts[1][0];
$contingencies = $database->real_escape_string($data['contingencies']);

// Start a transaction
$database->begin_transaction();


try {



    
    
    $parts = $data['parts'];

    foreach ($parts as $part) {
        $invoice_id = $database->real_escape_string($part['invoiceId']);
        $partNumber = $database->real_escape_string($part['partNumber']);
        $partName= $database->real_escape_string($part['partName']);
        $materialType = $database->real_escape_string($part['materialType']);
        $numOutputs= $database->real_escape_string($part['numOutputs']);
        $volume= $database->real_escape_string($part['volume']);
        $width= $database->real_escape_string($part['width']);
        $widthIN= $database->real_escape_string($part['widthIN']);
        $pitch= $database->real_escape_string($part['pitch']);
        $pitchIN= $database->real_escape_string($part['pitchIN']);
        $gauge= $database->real_escape_string($part['gauge']);
        $gaugeIN= $database->real_escape_string($part['gaugeIN']);
        $Density= $database->real_escape_string($part['Density']);
        $blankWeightlbs= $database->real_escape_string($part['blankWeight']);
        $blankWeightKg= $database->real_escape_string($part['blankWeightKg']);
        $scrapConsumption= $database->real_escape_string($part['scrapConsumption']);
        $pcsWeight= $database->real_escape_string($part['pcsWeight']);
        $pcsWeightKg= $database->real_escape_string($part['pcsWeightKg']);
        $scrapLbsInKg= $database->real_escape_string($part['scrapLbsInKg']);
        $scrapLbs= $database->real_escape_string($part['scrapLbs']);
        $palletType= $database->real_escape_string($part['palletType']);
        $palletSize= $database->real_escape_string($part['palletSize']);
        $palletWeight= $database->real_escape_string($part['palletWeight']);
        $pcsPerLift= $database->real_escape_string($part['pcsPerLift']);
        $stacksPerSkid= $database->real_escape_string($part['stacksPerSkid']);
        $pcsPerSkid= $database->real_escape_string($part['pcsPerSkid']);
        $UseSkidPcs= $database->real_escape_string($part['UseSkidPcs']);
        $liftWeight= $database->real_escape_string($part['liftWeight']);
        $stackHeight= $database->real_escape_string($part['stackHeight']);
        $skidsPerTruck= $database->real_escape_string($part['skidsPerTruck']);
        $pcsPerTruck= $database->real_escape_string($part['pcsPerTruck']);
        $weightPerTruck= $database->real_escape_string($part['weightPerTruck']);
        $annualTruckLoads= $database->real_escape_string($part['annualTruckLoads']);
        $skidCostPerPcs= $database->real_escape_string($part['skidCostPerPcs']);
        $lineProduced= $database->real_escape_string($part['lineProduced']);
        $partsPerHour= $database->real_escape_string($part['partsPerHour']);
        $uptime= $database->real_escape_string($part['uptime']);
        $blankingPerPieceCost= $database->real_escape_string($part['blankingPerPieceCost']);
        $packagingPerPieceCost= $database->real_escape_string($part['packagingPerPieceCost']);
        $freightPerPiece= $database->real_escape_string($part['freightPerPiece']);
        $totalPerPiece= $database->real_escape_string($part['totalPerPiece']);
        $material_cost= $database->real_escape_string($part['material_cost']);
        $wash_and_lube = $database->real_escape_string($part['wash_and_lube']);
        $material_markup_percent= $database->real_escape_string($part['material_markup_percent']);
        $material_cost_markup= $database->real_escape_string($part['material_cost_markup']);
        $nom= $database->real_escape_string($part['nom']);
        $blank_die= $database->real_escape_string($part['blank_die']);
        $model_year= $database->real_escape_string($part['model_year']);
        $trap= $database->real_escape_string($part['trap']);
        $palletCost= $database->real_escape_string($part['palletCost']);
        // Insert the part into the Line_Item table
        $stmt = $database->prepare("UPDATE Line_Item SET `Part Name` = ?, `Material Type` = ?, `# Outputs` = ?, `Volume` = ?, `Width(mm)` = ?, `width(in)` = ?, `Pitch(mm)` = ?, `Pitch(in)` = ?, `Gauge(mm)` = ?, `Density` = ?, `Gauge(in)` = ?, `Blank Weight(kg)` = ?, `Blank Weight(lb)` = ?, `Scrap Consumption` = ?, `Pcs Weight(kg)` = ?, `Pcs Weight(lb)` = ?, `Scrap Weight(kg)` = ?, `Scrap Weight(lb)` = ?, `Pallet Type` = ?, `Pallet Size` = ?, `Pallet Weight(lb)` = ?, `Pcs per Lift` = ?, `Stacks per Skid` = ?, `Pcs per Skid` = ?, `Lift Weight+Skid Weight(lb)` = ?, `Stack Height` = ?, `Skids per Truck` = ?, `Pieces per Truck` = ?, `Truck Weight(lb)` = ?, `Annual Truckloads` = ?, `UseSkidPcs` = ?, `Skid cost per piece` = ?, `Line Produced on` = ?, `PPH` = ?, `Uptime` = ?, `Blanking per piece cost` = ?, `Packaging per Piece Cost` = ?, `freight per piece cost` = ?, `Total Cost per Piece` = ?, `wash_and_lube` = ?, `material_cost` = ?, `material_markup_percent` = ?, `material_cost_markup` = ?, `nom?` = ?, `blank_die?` = ?, `model_year` = ?, `trap` = ?, `palletCost` = ? WHERE `invoice_id` = ? AND `Part#` = ?");
        $stmt->bind_param("ssddddddddddddddddsdddddddddddddiddddddddddssssdss", $partName, $materialType, $numOutputs, $volume, $width, $widthIN, $pitch, $pitchIN, $gauge, $Density, $gaugeIN, $blankWeightKg, $blankWeightlbs, $scrapConsumption, $pcsWeightKg, $pcsWeight, $scrapLbsInKg, $scrapLbs, $palletType, $palletSize, $palletWeight, $pcsPerLift, $stacksPerSkid, $pcsPerSkid, $liftWeight, $stackHeight, $skidsPerTruck, $pcsPerTruck, $weightPerTruck, $annualTruckLoads, $UseSkidPcs, $skidCostPerPcs, $lineProduced, $partsPerHour, $uptime, $blankingPerPieceCost, $packagingPerPieceCost, $freightPerPiece, $totalPerPiece, $wash_and_lube, $material_cost, $material_markup_percent, $material_cost_markup, $nom, $blank_die, $model_year, $trap, $palletCost, $invoice_id, $partNumber);
        
        $stmt->execute();    }
    // Get the maximum version number for the given invoice number
$result = $database->query("SELECT MAX(`version`) as `max_version` FROM `invoice` WHERE invoice_id = '$invoice_id'");
$row = $result->fetch_assoc();

$max_version = $row['max_version'] + 1;  // Increment the version number

// Insert the new invoice with the incremented version number
$database->query("INSERT INTO invoice (`invoice_date`,`invoice_id`, `invoice_author`,`contingencies`,`version`) VALUES ('$invoiceDate','$invoice_id', '$author','$contingencies','$max_version')");

    
   
    

    

    // Commit the transaction
    // Commit the transaction
    $database->commit();


// Return the invoice_id in the JSON response
echo json_encode(['success' => true ]);;
} catch (Exception $e) {
    // An error occurred, rollback the transaction
    $database->rollback();
    echo json_encode(['error' => $e->getMessage()]);
}
?>