<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use setasign\Fpdi\Tcpdf\Fpdi;

$pdf = new Fpdi();

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Nicolas Granda');
$pdf->setTitle('IRS Form 5472');
$pdf->setSubject('irs');
$pdf->setKeywords('TCPDF, PDF, 5472, irs, guide');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Ruta al archivo base
$pdfPath = '5472.pdf';
$pageCount = $pdf->setSourceFile($pdfPath);

$pdf->SetMargins(0, 0, 0);        // Márgenes izquierdo, superior, derecho
$pdf->SetAutoPageBreak(false, 0); // Desactiva salto de página automático


$year = '2024';
$start_mounth = 'January';
$end_mounth = 'December';
$company = 'Ikusa LLC';
$address = '8735 Downwody';
$ein = '87-2680481';
$countries = "United States, Spain";
$shareholder = 'Nicolas Granda Bauza - Calle General Freire, 5 Piso 2 Apartamento A Irun, 20303, Guipuzcoa, Spain';
$object = 'Marketing and web development services';
$code = '541800';

// Recorremos todas las páginas
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    $pdf->AddPage('P', 'LETTER');

    $templateId = $pdf->importPage($pageNo);
    $size = $pdf->getTemplateSize($templateId);

    $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);

    // Escribimos solo en la primera página
    if ($pageNo === 1) {
        $pdf->SetFont('Helvetica', '', 9);

        $pdf->SetXY(100, 29);
        $pdf->Write(0, $start_mounth);

        $pdf->SetXY(120, 29);
        $pdf->Write(0, $year);

        $pdf->SetXY(143, 29);
        $pdf->Write(0, $end_mounth);

        $pdf->SetXY(163, 29);
        $pdf->Write(0, $year);

        $pdf->SetXY(15, 45);
        $pdf->Write(0, $company);

        $pdf->SetXY(170, 45);
        $pdf->Write(0, $ein);

        $pdf->SetXY(15, 54);
        $pdf->Write(0, $address);

        $pdf->SetXY(15, 63);
        $pdf->Write(0, "Atlanta, 30350, GA");
        
        $pdf->SetXY(55, 67.5);
        $pdf->Write(0, $object);
        
        $pdf->SetXY(180, 67.5);
        $pdf->Write(0, $code);
        
        $pdf->SetXY(100, 80);
        $pdf->Write(0, '1');
        
        $pdf->SetXY(150, 105);
        $pdf->Write(0, $countries);
        
        $pdf->SetFont('dejavusans', '', 16); // DejaVu Sans es unicode-friendly
      
        $pdf->SetXY(197, 113); // Coordenadas del checkbox
        $pdf->Write(0, '✓'); 
        $pdf->SetXY(197, 121); // Coordenadas del checkbox
        $pdf->Write(0, '✓');
        
        $pdf->SetXY(96, 133.5); // Coordenadas del checkbox
        $pdf->Write(0, '✓'); 
        
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(15, 143);
        $pdf->Write(0, $shareholder);
    }
}

$pdf->Output('f5472.pdf', 'I');
