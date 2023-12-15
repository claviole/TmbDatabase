<?php
ob_start();
require '../../vendor/autoload.php';
include '../../connection.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$itemNames = $_POST['itemNames'];
$invoice_id = $_POST['invoice_id'];

$spreadsheet = new Spreadsheet();

$spreadsheet->getProperties()->setCreator('You')
    ->setLastModifiedBy('You')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.');

$spreadsheet->setActiveSheetIndex(0);

$columns = implode(",", array_map(function($item) use ($database) {
    return '`' . $database->real_escape_string($item) . '`';
}, $itemNames));
$stmt = $database->prepare("SELECT $columns FROM `line_item` WHERE `invoice_id` = ?");
$stmt->bind_param("s", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$line_items = $result->fetch_all(MYSQLI_ASSOC);

$result->free();
$stmt->close();

$officialHeaders = [];
$officialValues = [];

foreach ($line_items as $line_item) {
    $rowValues = [];
    foreach ($line_item as $column_name => $value) {
        if (!in_array($column_name, $officialHeaders)) {
            $officialHeaders[] = $column_name;
        }
        if ($column_name == 'Line Produced on') {
            $line_id = intval($value);
            $stmt = $database->prepare("SELECT `Line_Name`, `Line_Location` FROM `lines` WHERE `line_id` = ?");
            $stmt->bind_param("i", $line_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $line = $result->fetch_assoc();
            if ($line) {
                $value = $line['Line_Location']. ' ' . $line['Line_Name'] ;
                $line_item[$column_name] = $value;
            } else {
                echo "No line found with id: $line_id";
            }
            $result->free();
            $stmt->close();
        }
        $rowValues[] = $value;
    }
    $officialValues[] = $rowValues;
     // Debugging output
     echo "Row Values: ";
     print_r($rowValues);
     echo "Official Values: ";
     print_r($officialValues);
}

$middleColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(ceil(count($officialHeaders) / 2));

$drawing = new Drawing();
$drawing->setName('Company Logo');
$drawing->setDescription('Company Logo');
$drawing->setPath('../../images/company_header.png');
$imageHeight = 100;
$drawing->setHeight($imageHeight);
$drawing->setCoordinates($middleColumn . '1');
$drawing->setOffsetX(10);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight($imageHeight);

// Fetch data from invoice table
$stmt = $database->prepare("SELECT `invoice_id`, `version`, `invoice_date`, `Customer Name` FROM `invoice` WHERE `invoice_id` = ?");
$stmt->bind_param("s", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();
$result->free();
$stmt->close();



// Format the invoice date
$invoice_date = new DateTime($invoice['invoice_date']);
$formatted_date = $invoice_date->format('m/d/Y');

// Add invoice data to the spreadsheet
$spreadsheet->getActiveSheet()->setCellValue('A2', 'Quote ID:');
$spreadsheet->getActiveSheet()->setCellValue('B2', $invoice['invoice_id']);
$spreadsheet->getActiveSheet()->setCellValue('A3', 'Version:');
$spreadsheet->getActiveSheet()->setCellValue('B3', $invoice['version']);
$spreadsheet->getActiveSheet()->setCellValue('A4', 'Quote Date:');
$spreadsheet->getActiveSheet()->setCellValue('B4', $formatted_date);
$spreadsheet->getActiveSheet()->setCellValue('A5', 'Customer:');
$spreadsheet->getActiveSheet()->setCellValue('B5', $invoice['Customer Name']);
$spreadsheet->getActiveSheet()->setCellValue('A6', ':');

// Set the background color of cells A2 to B5 to highlighter yellow
$spreadsheet->getActiveSheet()->getStyle('A2:B5')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFFFFF00'); // Highlighter yellow in ARGB

// Put a solid black border around the range from A2 to B5
$spreadsheet->getActiveSheet()->getStyle('A2:B5')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

// Add headers to the spreadsheet
$spreadsheet->getActiveSheet()->fromArray($officialHeaders, null, 'A8');

// Add values to the spreadsheet
// Add values to the spreadsheet
$row = 9; // Start from the 9th row
foreach ($officialValues as $values) {
    foreach ($values as $column => $value) {
        // Replace underscores in the header with a space
        $header = str_replace('_', ' ', $officialHeaders[$column]);

        if ($header == 'Scrap Consumption') {
            $value .= '%'; // Add "%" to the value if the header is "Scrap Consumption"
        }
        if (stripos($header, 'cost') !== false) {
            $value = '$' . $value; // Add "$" before the value if the header contains "cost"
        }
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($column + 1, $row, $value);
    }
    $row++;
}



$lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($officialHeaders));
$spreadsheet->getActiveSheet()->getStyle('A8:'.$lastColumn.'8')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A8:'.$lastColumn.'8')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
// Fetch contingencies from invoice table
$stmt = $database->prepare("SELECT `contingencies` FROM `invoice` WHERE `invoice_id` = ?");
$stmt->bind_param("s", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$contingencies = $result->fetch_assoc()['contingencies'];
$result->free();
$stmt->close();


// Split contingencies by periods
$contingenciesArray = explode('.', $contingencies);

// Add "Contingency:" label to the spreadsheet
$lastRow = $spreadsheet->getActiveSheet()->getHighestDataRow();
$spreadsheet->getActiveSheet()->setCellValue('A' . ($lastRow + 6), 'Contingencies:');

// Add contingencies to the spreadsheet
// Get the highest column
$highestColumn = $spreadsheet->getActiveSheet()->getHighestDataColumn();
// Add contingencies to the spreadsheet
foreach ($contingenciesArray as $contingency) {
    $spreadsheet->getActiveSheet()->setCellValue('B' . ($lastRow + 6), trim($contingency)); // trim() is used to remove leading and trailing whitespace
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
    $newColumnIndex = $highestColumnIndex + 6;
    $newColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($newColumnIndex);

    $spreadsheet->getActiveSheet()->mergeCells('B'.($lastRow + 6).':'.$newColumnLetter.($lastRow + 6));
    $lastRow++;
}

// Get the highest column
$highestColumn = $spreadsheet->getActiveSheet()->getHighestDataColumn();

// Center the contents of each cell
$spreadsheet->getActiveSheet()->getStyle('A1:'.$highestColumn.($lastRow+20))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
// Auto-adjust column width

foreach (range('A', $highestColumn) as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Center the contents of each cell
$spreadsheet->getActiveSheet()->getStyle('A1:'.$highestColumn.$lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$spreadsheet->getActiveSheet()->setTitle('Sheet 1');

$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('../../uploads/excel/01simple.xlsx');