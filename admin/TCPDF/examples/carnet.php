<?php
require_once('tcpdf_include.php');

// Definir el tamaño de la tarjeta de crédito en milímetros
$cardWidth = 85.60;
$cardHeight = 53.98;

// Crear nuevo documento PDF con tamaño personalizado
$pdf = new TCPDF('L', 'mm', array($cardWidth, $cardHeight), true, 'UTF-8', false);
 // 1 para CMYK, 0 para RGB

// Establecer información del documento
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Nicola Asuni');
$pdf->setTitle('Carnet TCPDF');
$pdf->setSubject('Ejemplo TCPDF');
$pdf->setKeywords('TCPDF, PDF, tarjeta, ejemplo');

// Eliminar el encabezado y pie de página
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Establecer márgenes
$pdf->setMargins(5, 5, 5);
$pdf->setAutoPageBreak(false, 0);

// Agregar una página
$pdf->AddPage();

// Configurar fuente
$pdf->setFont('helvetica', '', 10);

// Contenido HTML
$html = <<<EOD
<h2 style="text-align:center;">Mi Tarjeta de Presentación</h2>
<p style="text-align:center;">Nombre: <b>Juan Pérez</b></p>
<p style="text-align:center;">Teléfono: <b>+34 123 456 789</b></p>
<p style="text-align:center;">Email: <b>juan@example.com</b></p>
EOD;
//$pdf->setColorMode(1);
$pdf->SetFillColor(100, 0, 100, 0); // Verde en RGB
$pdf->RoundedRect(25, 2, 56, 50, 5, '1111', 'F');


// Imprimir contenido en la tarjeta
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// Salida del PDF
$pdf->Output('tarjeta_credito.pdf', 'I');
