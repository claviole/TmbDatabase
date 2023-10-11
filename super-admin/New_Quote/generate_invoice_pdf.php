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
        $this->Cell(0, 10, 'Ford Motor Company', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(10);
        $this->Cell(0, 10, 'Steel Blank Price Quotation', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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

$result = $database->query("SELECT Line_Item.*, `lines`.Line_Name, `lines`.Line_Location FROM Line_Item INNER JOIN `lines` ON Line_Item.`Line Produced on` = `lines`.line_id WHERE Line_Item.invoice_id = $invoice_id");
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

$html = '';
    

$pdf->writeHTML($html, true, false, true, false, '');

$html = '<table border="0" cellpadding="3" cellspacing="0" style="width:30%; border:1px solid #ddd; margin-top:20px;">';

foreach ($line_items as $item) {
    $html .='<tr><th>Part#</th><td>' . $item['Part#'] . '</td></tr>'
        . '<tr><th>Part Name</th><td>' . $item['Part Name'] . '</td></tr>'
        . '<tr><th>Volume</th><td>' . $item['Volume'] . '</td></tr>'
        . '<tr><th>Material Type</th><td>' . $item['Material Type'] . '</td></tr>'
        . '<tr><th>Width(mm)</th><td>' . $item['Width(mm)'] . '</td></tr>'
        . '<tr><th>Pitch(mm)</th><td>' . $item['Pitch(mm)'] . '</td></tr>'
        . '<tr><th>Gauge(mm)</th><td>' . $item['Gauge(mm)'] . '</td></tr>'
        . '<tr><th>Pcs per Skid</th><td>' . $item['Pcs per Skid'] . '</td></tr>'
        . '<tr><th>Blanking per piece cost</th><td>' . $item['Blanking per piece cost'] . '</td></tr>'
        . '<tr><th>Packaging Per Piece Cost</th><td>' . $item['Packaging Per Piece Cost'] . '</td></tr>'
        . '<tr><th>Freight per piece cost</th><td>' . $item['freight per piece cost'] . '</td></tr>'
        . '<tr><th>Total Cost per Piece</th><td>' . $item['Total Cost per Piece'] . '</td></tr>';
}

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('Invoice_' . $invoice_id . '.pdf', 'I');