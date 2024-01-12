<?php
require '../../vendor/autoload.php';
include '../../connection.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$results = json_decode($_POST['results'], true);


$spreadsheet = new Spreadsheet();

$spreadsheet->getProperties()->setCreator('You')
    ->setLastModifiedBy('You')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.');

$spreadsheet->setActiveSheetIndex(0);

// Add company logo
$drawing = new Drawing();
$drawing->setName('Company Logo');
$drawing->setDescription('Company Logo');
$drawing->setPath('../../images/company_header.png'); // Provide path to your logo file
$drawing->setHeight(100); // Set the logo's height to 100 pixels
$drawing->setCoordinates('A1');
$drawing->setOffsetX(10);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

// Add headers to the spreadsheet
$headers = ['Customer Name', 'Part Number', 'Gross Weight', 'Net Weight', 'Shipping Location', 'Blanking Cost Per Piece', 'Packaging Cost Per Piece', 'Freight Per Piece'];
$spreadsheet->getActiveSheet()->fromArray($headers, null, 'A5');

// Add values to the spreadsheet
$row = 6; // Start from the 6th row
foreach ($results as $result) {
    $spreadsheet->getActiveSheet()->fromArray(array_slice($result, 0, 8), null, 'A' . $row); // Exclude the contingency from the main data
    $row++;
}

// Add contingencies 5 rows below the last row
$row += 5;
$lastResult = end($results);
$contingencies = explode('.', $lastResult[9]); // Split the contingencies string into an array of strings

// Display the word "Contingency" only once
$spreadsheet->getActiveSheet()->setCellValue('A' . $row, 'Contingencies:');
$row++;

// Then list all the contingencies
foreach ($contingencies as $contingency) {
    $spreadsheet->getActiveSheet()->setCellValue('B' . $row, $contingency);
    $row += 2; // Skip one row after each contingency
}
// Style the header
$spreadsheet->getActiveSheet()->getStyle('A5:H5')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A5:H5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK);
$spreadsheet->getActiveSheet()->getStyle('A5:H5')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFFFFF00'); // Highlighter yellow in ARGB

// Center the contents of each cell
$spreadsheet->getActiveSheet()->getStyle('A5:H' . $spreadsheet->getActiveSheet()->getHighestDataRow())->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Auto-adjust column width
foreach (range('A', 'H') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Get the invoice_id from the last result
$invoice_id = end($results)[8]; // Assuming invoice_id is the 9th item in the result array

// ...

$writer = new Xlsx($spreadsheet);
// Set the filename to the invoice_id and current date
$filename = $invoice_id  . '_'. date('m-d-Y'). '.xlsx' ;


// Define the path where the file will be stored
$dir = $_SERVER['DOCUMENT_ROOT'] . '/invoice_files/';
if (!is_dir($dir)) {
    // If not, create the directory
    mkdir($dir, 0777, true);
}
$filePath = $dir . $filename;

// Save the Excel file to the defined path
$writer->save($filePath);

// Prepare an SQL statement to insert the file path into the database
$stmt = $database->prepare("INSERT INTO `invoice_files` (`invoice_id`, `file_name`, `file_path`) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $invoice_id, $filename, $filePath);

// Execute the statement
$stmt->execute();

// Output to the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');

$writer->save('php://output');
exit;