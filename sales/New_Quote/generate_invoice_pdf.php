<?php
require('../../fpdf186/fpdf.php');
include '../../connection.php';

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../../images/company_header.png',10,6,200);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,'Quote',1,0,'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Get the invoice ID from the query string
$invoice_id = isset($_GET['invoice_id']) ? $_GET['invoice_id'] : null;

if ($invoice_id === null) {
    // No invoice ID was provided, handle this error as needed
    exit('No invoice ID provided');
}

// Fetch the invoice details from the database
$result = $database->query("SELECT * FROM invoice WHERE invoice_id = $invoice_id");
$invoice = $result->fetch_assoc();

// Fetch the line items for the invoice
$result = $database->query("SELECT Line_Item.*, `lines`.Line_Name, `lines`.Line_Location FROM Line_Item INNER JOIN `lines` ON Line_Item.`Line Produced on` = `lines`.line_id WHERE Line_Item.invoice_id = $invoice_id");
$line_items = $result->fetch_all(MYSQLI_ASSOC);



// Create a new PDF
$pdf = new PDF('L', 'mm', 'A3'); // Changed to landscape mode to fit more columns
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

// Add the invoice data to the PDF
$pdf->Cell(0,10,'Invoice ID: ' . $invoice['invoice_id'], 0, 1);
$pdf->Cell(0,10,'Customer Name: ' . $invoice['Customer Name'], 0, 1);
$pdf->Cell(0,10,'Invoice Date: ' . $invoice['invoice_date'], 0, 1);
$pdf->Cell(0,10,'Invoice Author: ' . $invoice['invoice_author'], 0, 1);

$pdf->Ln(10); // Add a 10mm space

// Add table header
$pdf->SetFont('Arial','B',9);
$pdf->Cell(15,10,'Part#',1);
$pdf->Cell(40,10,'Part Name',1);
$pdf->Cell(25,10,'Line Produced',1);
$pdf->Cell(15,10,'Volume',1);
$pdf->Cell(25,10,'Material Type',1);
$pdf->Cell(15,10,'Width(mm)',1);
$pdf->Cell(15,10,'Width(in)',1);
$pdf->Cell(15,10,'Pitch(mm)',1);
$pdf->Cell(15,10,'Pitch(in)',1);
$pdf->Cell(15,10,'Gauge(mm)',1);
$pdf->Cell(15,10,'Gauge(in)',1);
$pdf->Cell(25,10,'Pcs per Skid',1);
$pdf->Cell(35,10,'Blanking per piece cost',1);
$pdf->Cell(35,10,'Packaging Per Piece Cost',1);
$pdf->Cell(35,10,'Freight per piece cost',1);
$pdf->Cell(35,10,'Total Cost per Piece',1);
$pdf->Ln();

// Add the line items to the PDF
$pdf->SetFont('Arial','',12);
foreach ($line_items as $item) {
    $pdf->Cell(15,10,$item['Part#'],1);
    $pdf->Cell(25,10,$item['Part Name'],1);
    $pdf->Cell(25,10,$item['Line_Location'] . ' (' . $item['Line_Name'] . ')',1);
    $pdf->Cell(15,10,$item['Volume'],1);
    $pdf->Cell(25,10,$item['Material Type'],1);
    $pdf->Cell(15,10,$item['Width(mm)'],1);
    $pdf->Cell(15,10,$item['width(in)'],1);
    $pdf->Cell(15,10,$item['Pitch(mm)'],1);
    $pdf->Cell(15,10,$item['Pitch(in)'],1);
    $pdf->Cell(15,10,$item['Gauge(mm)'],1);
    $pdf->Cell(15,10,$item['Gauge(in)'],1);
    $pdf->Cell(25,10,$item['Pcs per Skid'],1);
    $pdf->Cell(35,10,$item['Blanking per piece cost'],1);
    $pdf->Cell(35,10,$item['Packaging Per Piece Cost'],1);
    $pdf->Cell(35,10,$item['freight per piece cost'],1);
    $pdf->Cell(35,10,$item['Total Cost per Piece'],1);
    $pdf->Ln();
}

// Prepare the filename
$filename = str_replace(' ', '_', $invoice['Customer Name']) . '_Invoice_' . $invoice_id . '.pdf';

// Output the PDF
$pdf->Output($filename, 'D');
?>