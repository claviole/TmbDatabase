<?php
session_start();
include '../../connection.php';


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
$author = $database->real_escape_string($data['invoice_author']);


// Start a transaction
$database->begin_transaction();


try {
    // Get the ID of the inserted invoice
    $invoice_id = $database->insert_id;
    // Insert the invoice into the invoice tabl
    



    
    
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
        $liftWeight= $database->real_escape_string($part['liftWeight']);
        $stackHeight= $database->real_escape_string($part['stackHeight']);
        $skidsPerTruck= $database->real_escape_string($part['skidsPerTruck']);
        $pcsPerTruck= $database->real_escape_string($part['pcsPerTruck']);
        $weightPerTruck= $database->real_escape_string($part['weightPerTruck']);
        $annualTruckLoads= $database->real_escape_string($part['annualTruckLoads']);
        $fiveUseSkidPcs= $database->real_escape_string($part['fiveUseSkidPcs']);
        $skidCostPerPcs= $database->real_escape_string($part['skidCostPerPcs']);
        $lineProduced= $database->real_escape_string($part['lineProduced']);
        $partsPerHour= $database->real_escape_string($part['partsPerHour']);
        $uptime= $database->real_escape_string($part['uptime']);
        $blankingPerPieceCost= $database->real_escape_string($part['blankingPerPieceCost']);
        $packagingPerPieceCost= $database->real_escape_string($part['packagingPerPieceCost']);
        $freightPerPiece= $database->real_escape_string($part['freightPerPiece']);
        $totalPerPiece= $database->real_escape_string($part['totalPerPiece']);

        // Insert the part into the Line_Item table
    $database->query("INSERT INTO Line_Item (invoice_id, `Part#`, `Part Name`, `Material Type`, `# Outputs`, `Volume`, `Width(mm)`, `width(in)`, `Pitch(mm)`, `Pitch(in)`, `Gauge(mm)`,`Density`, `Gauge(in)`, `Blank Weight(kg)`, `Blank Weight(lb)`, `Scrap Consumption`, `Pcs Weight(kg)`, `Pcs Weight(lb)`, `Scrap Weight(kg)`, `Scrap Weight(lb)`, `Pallet Type`, `Pallet Size`, `Pallet Weight(lb)`,`Pcs per Lift`,`Stacks per Skid`,`Pcs per Skid`,`Lift Weight+Skid Weight(lb)`,`Stack Height`,`Skids per Truck`,`Pieces per Truck`,`Truck Weight(lb)`,`Annual Truckloads`,`# Pieces 5 use skid`,`Skid cost per piece`,`Line Produced on`,`PPH`,`Uptime`,`Blanking per piece cost`,`Packaging per Piece Cost`,`freight per piece cost`,`Total Cost per Piece`) VALUES ('$invoice_id','$partNumber','$partName','$materialType','$numOutputs','$volume','$width','$widthIN','$pitch','$pitchIN','$gauge','$gaugeIN','$Density','$blankWeightKg','$blankWeightlbs','$scrapConsumption','$pcsWeightKg','$pcsWeight','$scrapLbsInKg','$scrapLbs','$palletType','$palletSize','$palletWeight','$pcsPerLift','$stacksPerSkid','$pcsPerSkid','$liftWeight','$stackHeight','$skidsPerTruck','$pcsPerTruck','$weightPerTruck','$annualTruckLoads','$fiveUseSkidPcs','$skidCostPerPcs','$lineProduced','$partsPerHour','$uptime','$blankingPerPieceCost','$packagingPerPieceCost','$freightPerPiece','$totalPerPiece')");
    }
    $database->query("INSERT INTO invoice (`Customer name`, `customer_id`, `invoice_date`,`invoice_number`, `invoice_author`) VALUES ('$customer', '$customerId', '$invoiceDate','$invoice_id', '$author')");
    
    // Commit the transaction
    // Commit the transaction
    $database->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // An error occurred, rollback the transaction
    $database->rollback();
    echo json_encode(['error' => $e->getMessage()]);
}
?>