<?php
session_start();
include '../../configurations/connection.php';


// Get the results from the POST data
$results = json_decode($_POST['submit_quote_results'], true);

// Get the last result
$lastResult = end($results);

// Select certain parts of the last result to store in the invoice table
$invoiceData = [
    'invoice_id' => $lastResult[8], 
    'contingencies' => $lastResult[9], 
    'invoice_date' => $lastResult[10],
    'invoice_author' => $lastResult[11],
    'Customer Name' => $lastResult[0],

    // Add more parts as needed
];

// Insert into invoice table
$invoiceSql = "INSERT INTO invoice (`invoice_id`, `contingencies`,`invoice_date`,`invoice_author`,`Customer Name`) VALUES ( ?, ?, ?, ?, ?)";
$invoiceStmt = $database->prepare($invoiceSql);
$invoiceStmt->bind_param("sssss", $invoiceData['invoice_id'], $invoiceData['contingencies'], $invoiceData['invoice_date'], $invoiceData['invoice_author'], $invoiceData['Customer Name']);
$invoiceStmt->execute();


$lineItemSql = "INSERT INTO Line_Item (`invoice_id`, `Part#`, `Blank Weight(lb)`, `Pcs Weight(lb)`, `Blanking per piece cost`, `Packaging Per Piece Cost`,`freight per piece cost`, `Line Produced on`, `PPH`,`ship_to_location`,`material_cost`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
$lineItemStmt = $database->prepare($lineItemSql);


foreach ($results as $result) {
    // Select certain parts of the result to store in the Line_Item table
    $lineItemData = [
        'invoice_id' => $result[8], 
        'part_number' => $result[1], 
        'part_name' => $result[0], 
        'Blank Weight(lb)' => $result[2], 
        'Pcs Weight(lb)' => $result[3],
        'Blanking per piece cost' => $result[5], 
        'Packaging Per Piece Cost' => $result[6], 
        'freight per piece cost' => $result[7], 
        'Line Produced on' => $result[12], 
        'PPH' => $result[13], 
        'ship_to_location' => $result[14], 
        'material_cost' => $result[15],
        'Total Cost per Piece' => $result[5] + $result[6] + $result[7] + $result[15] 
    ];

    $lineItemStmt->bind_param("ssdddddsisdd", $lineItemData['invoice_id'], $lineItemData['part_number'],$lineItemData['Blank Weight(lb)'], $lineItemData['Pcs Weight(lb)'], $lineItemData['Blanking per piece cost'], $lineItemData['Packaging Per Piece Cost'], $lineItemData['freight per piece cost'], $lineItemData['Line Produced on'], $lineItemData['PPH'],$lineItemData['ship_to_location'],$lineItemData['material_cost'],$lineItemData['Total Cost per Piece']);
    $lineItemStmt->execute();
}

// Return a response
echo json_encode(['status' => 'success']);