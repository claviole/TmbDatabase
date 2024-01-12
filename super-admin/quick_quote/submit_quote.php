<?php
session_start();
include '../../connection.php';


// Get the results from the POST data
$results = json_decode($_POST['submit_quote_results'], true);

// Get the last result
$lastResult = end($results);

// Select certain parts of the last result to store in the invoice table
$invoiceData = [
    'invoice_id' => $lastResult[8], // Assuming invoice_id is the 9th item in the result array
    'contingencies' => $lastResult[9], // Assuming contingencies is the 10th item in the result array
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


$lineItemSql = "INSERT INTO Line_Item (`invoice_id`, `Part#`, `Blank Weight(lb)`, `Pcs Weight(lb)`, `Blanking per piece cost`, `Packaging Per Piece Cost`,`freight per piece cost`, `Line Produced on`, `PPH`,`ship_to_location`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
$lineItemStmt = $database->prepare($lineItemSql);


foreach ($results as $result) {
    // Select certain parts of the result to store in the Line_Item table
    $lineItemData = [
        'invoice_id' => $result[8], // Assuming invoice_id is the 9th item in the result array
        'part_number' => $result[1], // Assuming Part# is the 1st item in the result array
        'part_name' => $result[0], // Assuming Part Name is the 2nd item in the result array
        'Blank Weight(lb)' => $result[2], // Assuming Pcs Weight(lb) is the 3rd item in the result array
        'Pcs Weight(lb)' => $result[3], // Assuming Pcs Weight(lb) is the 4th item in the result array
        'Blanking per piece cost' => $result[5], // Assuming Blanking per piece cost is the 5th item in the result array
        'Packaging Per Piece Cost' => $result[6], // Assuming Packaging per piece cost is the 6th item in the result array
        'freight per piece cost' => $result[7], // Assuming freight per piece cost is the 7th item in the result array
        'Line Produced on' => $result[12], // Assuming Line Produced on is the 8th item in the result array
        'PPH' => $result[13], // Assuming PPH is the 9th item in the result array
        'ship_to_location' => $result[14], // Assuming ship_to_location is the 10th item in the result array
    ];

    $lineItemStmt->bind_param("ssdddddsis", $lineItemData['invoice_id'], $lineItemData['part_number'],$lineItemData['Blank Weight(lb)'], $lineItemData['Pcs Weight(lb)'], $lineItemData['Blanking per piece cost'], $lineItemData['Packaging Per Piece Cost'], $lineItemData['freight per piece cost'], $lineItemData['Line Produced on'], $lineItemData['PPH'],$lineItemData['ship_to_location']);
    $lineItemStmt->execute();
}

// Return a response
echo json_encode(['status' => 'success']);