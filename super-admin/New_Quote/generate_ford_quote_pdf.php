<?php
require_once('../../libraries/TCPDF-main/tcpdf.php');
include '../../connection.php';
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
        $this->Image('../../images/company_header.png',10,6,100);
        $this->Image('../../images/ford.png',$this->getPageWidth() - 100, 6, 100);
        
        $this->SetFont('helvetica', 'B', 20);
        $this->SetX(100);
        $this->Cell(100, 10, 'Ford Motor Company', 'LRT', false, 'C', true, '', 0, false, 'M', 'M');
        $this->Ln(10);
        $this->SetX(100);
        $this->Cell(100, 10, 'Steel Blank Price Quotation', 'LRB', false, 'C', true, '', 0, false, 'M', 'M');
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
    SELECT Line_Item.*, `Lines`.Line_Name, `Lines`.Line_Location, `Part`.supplier_name ,`Part`.Platform,`Part`.Surface
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
$pdf->setCellPaddings(2,2,2,2);

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

    $html .='<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Supplier Name</th><td style ="text-align:center;">' . $item['supplier_name'] . '</td></tr>'
        .'<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6" >Stamper Location</th><td style ="text-align:center;">' . $item['Line_Location'] . '</td></tr>'
        .'<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6" >Part Number</th><td style ="text-align:center;">' . $item['Part#'] . '</td></tr>'
        . '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6" >Material Type</th><td style ="text-align:center;">' . $item['Material Type'] . '</td></tr>'
        . '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6" >Platform</th><td style ="text-align:center;">' . $item['Platform'] . '</td></tr>'
        . '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6" >Part Name</th><td style ="text-align:center;">' . $item['Part Name'] . '</td></tr>'
        . '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6" >Surface</th><td style ="text-align:center;">' . $item['Surface'] . '</td></tr>';

$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$bottomTableY= $pdf->GetY();

// Move the position to start the second table
$pdf->SetY($startY);  // Adjust the value as needed
$pdf->SetX($pdf->GetX() + 152);  // Adjust the value as needed
$pdf->setLeftMargin(50);
// Second table
$html = '<table bgcolor="#FFC760" border="1.5" cellpadding="3" cellspacing="0" style="width:50%; border:1px solid #ddd; margin-top:20px;">';

    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Volume</th><td style ="text-align:center;">' . $item['Volume'] . ' pcs'. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Gauge</th><td style ="text-align:center;">' . $item['Gauge(mm)'] . ' mm'. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Width</th><td style ="text-align:center;">' . $item['Width(mm)'] .' mm'.  '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Pitch</th><td style ="text-align:center;">' . $item['Pitch(mm)'] . ' mm'. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Density</th><td style ="text-align:center;">' . $item['Density'] . '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Blank Weight</th><td style ="text-align:center;">' . $item['Blank Weight(kg)'] .' kg' . '</td></tr>';
    // Add more rows as needed

$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetX($startOfFirstTableX);
$pdf->SetY($bottomTableY);
$pdf->SetLeftMargin(-10);
$html = '<h2 style="text-align:center;">Material Cost Build-Up</h2>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetLeftMargin(50);

$html = '<table bgcolor="#FFC760" border="1.5" cellpadding="3" cellspacing="0" style="width:49.5%; border:1px solid #ddd; margin-top:20px;">';

    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6"></th><td style ="text-align:center;font-weight:bold">' . '$ per KG'. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Base Price </th><td style ="text-align:center;">' . '$'. number_format($item['material_cost' ] /$item['Blank Weight(kg)'],3) . '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Packaging</th><td style ="text-align:center;">' . '$'. number_format($item['Packaging Per Piece Cost' ]* $item['Blank Weight(kg)'],3) . '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Total :</th><td style ="text-align:center; background-color:#78FF00; ">' . '$'. number_format(($item['Packaging Per Piece Cost' ]* $item['Blank Weight(kg)'])+($item['material_cost' ] /$item['Blank Weight(kg)']),3) . '</td></tr>';

$html.= '</table>';


$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetLeftMargin(110);
$pdf->SetY($bottomTableY);

$html = '<h2 style="text-align:center;">Blank Cost Build-up</h2>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetLeftMargin(167);
$html = '<table bgcolor="#FFC760" border="1.5" cellpadding="3" cellspacing="0" style="width:50%; border:1px solid #ddd; margin-top:20px;">';

    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6"></th><td style ="text-align:center;font-weight:bold">' . '$ per Blank'. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Steel Cost in Blank</th><td style ="text-align:center;">' . '$'. ($item['material_cost' ]/$blankWeightlbs)*$blankWeightKg . '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Blank Processing Cost</th><td style ="text-align:center;">' . '$'. $item['Blanking per piece cost' ] . '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Freight</th><td style ="text-align:center;">' . '$' . $item['freight per piece cost']. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Packaging Cost:</th><td style ="text-align:center;">' . '$'. $item['Packaging Per Piece Cost'] .'</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Wash and Lube</th><td style ="text-align:center;">' . '$'. $item['wash_and_lube']/$item['Volume']. '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Material Cost Markup</th><td style ="text-align:center;">' . '$'.(($item['material_cost_markup']/ $item['material_cost'])*(($item['material_cost']/$item['blankWeightlbs'])*$item['blankWeightKg'])) . '</td></tr>';
    $html .= '<tr><th style ="text-align:center;font-weight:bold" bgcolor="#ADD8E6">Total Delivered per Blank Cost</th><td style ="text-align:center; background-color:#78FF00; ">' . '$'. number_format((($item['material_cost_markup']/ $item['material_cost'])*(($item['material_cost']/$item['blankWeightlbs'])*$item['blankWeightKg']))+($item['wash_and_lube']/$item['Volume'])+$item['Packaging Per Piece Cost']+$item['freight per piece cost']+$item['Blanking per piece cost']+($item['material_cost' ]/$blankWeightlbs)*$blankWeightKg,3) . '</td></tr>';

$html.= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

$text =$contingencies;

// Set the position of the text box
$x = 10; // X position
$y = $pdf->GetY() + 120; // Y position (10 units below the current position)

// Set the width and height of the text box
$w = $pdf->getPageWidth() - 20; // Width (page width minus 10 units on each side for margin)
$h = 50; // Height

$pdf->writeHTMLCell($w, $h, $x, $y, $text, 1, 1, false, true, 'J', true);
}
// Save the PDF to a file
$pdfFilePath = __DIR__ . '../../../uploads/pdfs/Quote_' . $invoice_id . '.pdf';
$pdf->Output($pdfFilePath, 'F');

// Read the file content
$pdfContent = file_get_contents($pdfFilePath);

// Save the PDF content to the database
// You need to implement the savePdfToDatabase function
savePdfToDatabase($invoice_id, 'Quote_' . $invoice_id . '.pdf', $pdfContent);

// Output the PDF to the browser
$pdf->Output($pdfFilePath, 'I');