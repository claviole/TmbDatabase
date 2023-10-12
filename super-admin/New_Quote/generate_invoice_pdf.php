<?php
require_once('../../libraries/TCPDF-main/tcpdf.php');
include '../../connection.php';

class PDF extends TCPDF
{
    // Page header
    public function Header()
    {
        global $invoice; // Make sure $invoice is accessible in this scope

        // Fetch and format the invoice date
        $invoice_date = $invoice['invoice_date'];
        $formatted_date = date('F j, Y', strtotime($invoice_date));
        $this->Image('../../images/company_header.png',10,6,100);
        $this->Image('../../images/ford.png',$this->getPageWidth() - 100, 6, 100);
        
        $this->SetFont('helvetica', 'B', 20);
        $this->setFillColor(255, 199, 96);
        $this->SetDrawColor(0,0,0);
        $this->SetX(100);
        $this->Cell(100, 10, 'Ford Motor Company', 'LRT', false, 'C', true, '', 0, false, 'M', 'M');
        $this->Ln(10);
        $this->SetX(100);
        $this->Cell(100, 10, 'Steel Blank Price Quotation', 'LRB', false, 'C', true, '', 0, false, 'M', 'M');
        $this->Ln(10);
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
$result = $database->query("
    SELECT Line_Item.*, `lines`.Line_Name, `lines`.Line_Location, `part`.supplier_name ,`part`.Platform,`part`.Surface
    FROM Line_Item 
    INNER JOIN `lines` ON Line_Item.`Line Produced on` = `lines`.line_id 
    INNER JOIN `part` ON Line_Item.`Part#` = `part`.`Part#` 
    WHERE Line_Item.invoice_id = $invoice_id
");
$line_items = $result->fetch_all(MYSQLI_ASSOC);


$pdf = new PDF('P', PDF_UNIT,'A3', true, 'UTF-8', false);
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
$html = '<table bgcolor="#FFC760" border="1.5" cellpadding="3" cellspacing="0" style="width:49.5%; border:1px solid #ddd; margin-top:20px;">';
foreach ($line_items as $item) {
    $html .='<tr><th bgcolor="#ADD8E6">Supplier Name</th><td>' . $item['supplier_name'] . '</td></tr>'
        .'<tr><th bgcolor="#ADD8E6" >Stamper Location</th><td>' . $item['Line_Location'] . '</td></tr>'
        .'<tr><th bgcolor="#ADD8E6" >Part Number</th><td>' . $item['Part#'] . '</td></tr>'
        . '<tr><th bgcolor="#ADD8E6" >Material Type</th><td>' . $item['Material Type'] . '</td></tr>'
        . '<tr><th bgcolor="#ADD8E6" >Platform</th><td>' . $item['Platform'] . '</td></tr>'
        . '<tr><th bgcolor="#ADD8E6" >Part Name</th><td>' . $item['Part Name'] . '</td></tr>'
        . '<tr><th bgcolor="#ADD8E6" >Surface</th><td>' . $item['Surface'] . '</td></tr>';
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Move the position to start the second table
$pdf->SetY($startY);  // Adjust the value as needed
$pdf->SetX($pdf->GetX() + 152);  // Adjust the value as needed

// Second table
$html = '<table bgcolor="#FFC760" border="1.5" cellpadding="3" cellspacing="0" style="width:50%; border:1px solid #ddd; margin-top:20px;">';
foreach($line_items as $item){
    $html .= '<tr><th bgcolor="#ADD8E6">Volume</th><td>' . $item['Volume'] . ' pcs'. '</td></tr>';
    $html .= '<tr><th bgcolor="#ADD8E6">Gauge</th><td>' . $item['Gauge(mm)'] . ' mm'. '</td></tr>';
    $html .= '<tr><th bgcolor="#ADD8E6">widht</th><td>' . $item['Width(mm)'] .' mm'.  '</td></tr>';
    $html .= '<tr><th bgcolor="#ADD8E6">Pitch</th><td>' . $item['Pitch(mm)'] . ' mm'. '</td></tr>';
    $html .= '<tr><th bgcolor="#ADD8E6">Density</th><td>' . $item['Density'] . '</td></tr>';
    $html .= '<tr><th bgcolor="#ADD8E6">Blank Weight</th><td>' . $item['Blank Weight(kg)'] .' kg' . '</td></tr>';
    // Add more rows as needed
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('Invoice_' . $invoice_id . '.pdf', 'I');