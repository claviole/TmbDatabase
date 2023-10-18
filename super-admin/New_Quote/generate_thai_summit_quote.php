<?php
require_once('../../libraries/TCPDF-main/tcpdf.php');
include '../../connection.php';
// Define the savePdfToDatabase function
function savePdfToDatabase($invoice_id, $file_name, $file_contents) {
    global $database;

    // Prepare the SQL statement
    $stmt = $database->prepare("INSERT INTO invoice_files (invoice_id, file_name, file_contents) VALUES (?, ?, ?)");

    // Bind the parameters
    $stmt->bind_param("iss", $invoice_id, $file_name, $file_contents);

    // Execute the statement
    $stmt->execute();
}

class PDF extends TCPDF
{
    
    // Page header
    public function Header()
    {
        global $invoice; // Make sure $invoice is accessible in this scope

        // Fetch and format the invoice date
        $invoice_date = $invoice['invoice_date'];
        $formatted_date = date('F j, Y', strtotime($invoice_date));
        $this->setFillColor(229, 229, 229);

        // Set the draw color to black
        $this->SetDrawColor(0, 0, 0); // This sets the draw color to black

        $this->Rect(0, 0, $this->getPageWidth(),45, 'DF' );

        

        $this->Rect(0, 45, $this->getPageWidth(),90, 'DF' );
        $this->Image('../../images/company_header.png',0,6,100);
        $this->Image('../../images/thai_summit_header.png',$this->getPageWidth() - 100, 25, 100);
        
        $this->SetFont('helvetica', 'B', 20);
        $this->SetX(100);
        $this->Cell(100, 10, 'Thai Summit', '', false, 'C', true, '', 0, false, 'M', 'M');
        $this->Ln(10);
        $this->SetX(100);
        $this->Cell(100, 10, 'Aluminum Blank Price Quotation', '', false, 'C', true, '', 0, false, 'M', 'M');
        $this->Ln(10);
        $this->setFillColor(235, 235, 235);
        $this->SetDrawColor(0, 0, 0); // This sets the draw color to black
        $this->Rect(0, 45, $this->getPageWidth(), $this->getPageHeight()-45, 'DF' );
        $this->SetFont('helvetica', '', 12);
        $this->Cell(0, 25,$formatted_date, 0, false, 'C', 0, '', 0, false, 'M', 'M');
    
    }

    // Page footer
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
    
}

$invoice_id = isset($_GET['invoice_id']) ? $_GET['invoice_id'] : null;

if ($invoice_id === null) {
    exit('No invoice ID provided');
}

$result = $database->query("SELECT * FROM invoice WHERE invoice_id = $invoice_id");
$invoice = $result->fetch_assoc();
$contingencies = $invoice['contingencies']; // Fetch the contingencies text from the invoice
$contingencies = nl2br($contingencies); // Replace newline characters with <br> tags
$result = $database->query("
    SELECT Line_Item.*, `Lines`.Line_Name, `Lines`.Line_Location, `Part`.supplier_name ,`Part`.Platform,`Part`.Surface,`Part`.Type
    FROM Line_Item 
    INNER JOIN `Lines` ON Line_Item.`Line Produced on` = `Lines`.line_id 
    INNER JOIN `Part` ON Line_Item.`Part#` = `Part`.`Part#` 
    WHERE Line_Item.invoice_id = $invoice_id
");
$line_items = $result->fetch_all(MYSQLI_ASSOC);


$pdf = new PDF('P', PDF_UNIT,'A3', true, 'UTF-8', false);
foreach ($line_items as $item) {
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Target Metal Blanking');
$pdf->SetTitle('Steel Blank Price Quotation');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT,PDF_MARGIN_TOP+10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$pdf->SetFont('dejavusans', '', 14, '', true);
$pdf->AddPage();
// Set the fill color to gray (you can change these values to the color you want)
$pdf->SetFillColor(173, 216, 230);
$originalLeftMargin = $pdf->getMargins()['left'];
$pdf->setLeftMargin(0);
// Calculate the width of each cell
$cellWidth = $pdf->getPageWidth() / 5;
$pdf->SetY(45.5);
// Add the new fields
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell($cellWidth, 10, 'Quote # : ' . $invoice['invoice_id'], 0, 0, 'C', true);
$pdf->Cell($cellWidth, 10, 'Supplier: ' . $item['supplier_name'], 0, 0, 'C', true);
$pdf->Cell($cellWidth, 10, 'Platform: ' . $item['Platform'], 0, 0, 'C', true);
$pdf->Cell($cellWidth, 10, 'Model Year: ' . $item['model_year'], 0, 0, 'C', true);
$pdf->Cell($cellWidth, 10, 'Volume: ' . $item['Volume'], 0, 1, 'C', true);
$pdf->Ln(10);


$pdf->setCellPaddings(2,2,2,2);
$pdf->SetLeftMargin($originalLeftMargin);
$html = '';
    

$pdf->writeHTML($html, true, false, true, false, '');
$startY = $pdf->GetY();
$startX = $pdf->GetX();
$pdf->SetX( 50);  // Adjust the value as needed
$pdf->SetFont('helvetica', '', 10); // Set the font to Helvetica, regular, size 10
// parent table
// First table
$startOfFirstTableX = $pdf->GetX();
$html = '<table bgcolor="#FFC760" border="1.5" cellpadding="3" cellspacing="0" style="width:49.5%; border:1px solid #ddd; margin-top:20px;">';

    $html .='<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6" >Part Number</th><td style ="text-align:center;">' . $item['Part#'] . '</td></tr>'
    . '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6" >Part Name</th><td style ="text-align:center;">' . $item['Part Name'] . '</td></tr>'
    . '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6"> Estimated Annual Volume</th><td style ="text-align:center;">' . $item['Volume'] . ' pcs'. '</td></tr>'
    . '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Blank Die?</th><td style ="text-align:center;">' . $item['blank_die?'] . '</td></tr>'
    . '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Blank Type</th><td style ="text-align:center;">' . $item['Type']. '</td></tr>'
        . '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6" >Material Type</th><td style ="text-align:center;">' . $item['Material Type'] . '</td></tr>';
        

$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$bottomTableY= $pdf->GetY();

// Move the position to start the second table
$pdf->SetY($startY);  // Adjust the value as needed
$pdf->SetX($pdf->GetX() + 152);  // Adjust the value as needed
$pdf->setLeftMargin(50);
// Second table
$html = '<table bgcolor="#FFC760" border="1.5" cellpadding="3" cellspacing="0" style="width:60%; border:1px solid #ddd; margin-top:20px;">';

    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">MIN/NOM/MAX</th><td style ="text-align:center;">' . $item['nom?'] . '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Gauge(mm)</th><td style ="text-align:center;">' . $item['Gauge(mm)'] . ' mm'. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Gauge(in)</th><td style ="text-align:center;">' . $item['Gauge(in)'] . ' in'. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Width</th><td style ="text-align:center;">' . $item['Width(mm)'] .' mm'.  '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Width(in)</th><td style ="text-align:center;">' . $item['width(in)'] .' in'.  '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Pitch</th><td style ="text-align:center;">' . $item['Pitch(mm)'] . ' mm'. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Pitch(in)</th><td style ="text-align:center;">' . $item['Pitch(in)'] . ' in'. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Theoretical Blank Weight(Lbs)</th><td style ="text-align:center;">' . $item['Blank Weight(lb)'] .' lb' . '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Parts/Blank</th><td style ="text-align:center;">' . "1". '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Blanks Per MT</th><td style ="text-align:center;">' .number_format(2204.623/$item['Blank Weight(lb)'],3) ."/MT". '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6" >Surface</th><td style ="text-align:center;">' . $item['Surface'] . '</td></tr>';
    // Add more rows as needed

$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');


$pdf->SetX($startOfFirstTableX);
$pdf->SetY($bottomTableY+40);
$pdf->SetLeftMargin(30);
$html = '<h1 style="text-align:center;">COST</h2>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetLeftMargin(50);

$html = '<table bgcolor="#FFC760" border="1.5" cellpadding="3" cellspacing="0" style="width:80%; border:1px solid #ddd; margin-top:20px;">';
$html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">% Scrap Return to TSK</th><td style ="text-align:center;">' . $item['Scrap Consumption'] . '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Blanking Cost</th><td style ="text-align:center;">' . '$'. number_format($item['Blanking per piece cost'],3) . "/Pc". '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Freight Cost</th><td style ="text-align:center;">' . '$'. number_format($item['freight per piece cost'],3) . "/Pc". '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Total : </th><td style ="text-align:center;" bgcolor="#61FF33">' . '$'. number_format($item['freight per piece cost']+$item['Blanking per piece cost']+ $item['Packaging Per Piece Cost'],3) . "/Pc". '</td></tr>';

$html.= '</table>';


$pdf->writeHTML($html, true, false, true, false, '');



$pdf->SetFont('helvetica', '', 16);
$pdf->setFillColor(240,255,41);
$text = $contingencies;
// Set the position of the text box
$x = 10; // X position
$y = $pdf->GetY() + 80; // Y position (10 units below the current position)

// Set the width and height of the text box
$w = $pdf->getPageWidth() - 20; // Width (page width minus 10 units on each side for margin)
$h = 50; // Height

$pdf->writeHTMLCell($w, $h, $x, $y, $text, 1, 1, true, true, 'J', true);
}
// Save the PDF to a file
$pdfFilePath = __DIR__ . '../../../uploads/pdfs/Quote_' . $invoice_id . '.pdf';
$pdf->Output($pdfFilePath, 'F');

// Read the file content
$pdfContent = file_get_contents($pdfFilePath);

// Save the PDF content to the database
// Save the PDF content to the database
savePdfToDatabase($invoice_id, 'Quote_' . $invoice_id . '.pdf', $pdfContent);

// Output the PDF to the browser
$pdf->Output($pdfFilePath, 'I');