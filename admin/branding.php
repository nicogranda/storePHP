<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use setasign\Fpdi\Tcpdf\Fpdi;

$pdf = new Fpdi();

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Nicolas Granda');
$pdf->setTitle('Branding Proposal');
$pdf->setSubject('Branding');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');


class MyPdf extends Fpdi {
    public function Header() {
        // Logo en el header
        $this->Image('https://ikusa.net/images/logo.png', 160, 10, 30, 0, 'PNG');
        $this->Ln(15); // Espacio después del logo (opcional)
    }

    public function Footer() {
        // Si quieres algo en el pie de página, agrégalo aquí
    }
}

// Usar tu clase personalizada
$pdf = new MyPdf();

// Configuración del PDF como siempre
$pdf->setPrintHeader(true);  // MUY IMPORTANTE: para que se active el método Header()
$pdf->setPrintFooter(false); // Si


// Crear nuevo PDF
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


$pdf->SetMargins(20, 20, 20);
$pdf->SetAutoPageBreak(TRUE, 20);

// Ruta al archivo base
$pdfPath = 'branding.pdf';
// $pageCount = $pdf->setSourceFile($pdfPath);

// Agregar página
$pdf->AddPage();
// $logoPath = __DIR__ . '/../../images/logo.png';
// echo "Buscando logo en: $logoPath<br>";

// if (!file_exists($logoPath)) {
//     die("🛑 Logo no encontrado en esa ruta.");
// }

// $pdf->Image(
//     '../../images/logo.PNG', // Ruta local (ajústala si es necesario)
//     160,   // X
//     10,    // Y
//     30,    // Width
//     0,     // Height (0 = proporcional)
//     'PNG', // Tipo
//     '',    // Link
//     '',    // [❌] Este campo era incorrecto, lo eliminamos
//     false, // Resize
//     300,   // DPI
//     '', '', false, false, 0, false, false
// );
$pdf->Image('https://ikusa.net/images/logo.png', 160, 10, 30, 0, 'PNG');

// Título principal
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, 'Propuesta de Branding', 0, 1, 'C');

// Introducción
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(5);
$intro = <<<EOD
Esta propuesta presenta un enfoque estratégico y creativo para desarrollar una identidad de marca sólida, coherente y diferenciadora para tu proyecto o negocio.

El objetivo es construir una imagen visual que no solo represente los valores de tu marca, sino que también conecte emocionalmente con tu público objetivo y se mantenga consistente en todos los puntos de contacto.

Desde el logotipo y la paleta de colores, hasta la tipografía y los elementos gráficos, cada decisión está pensada para potenciar el reconocimiento y la recordación de tu marca.
EOD;
$pdf->MultiCell(0, 0, $intro, 0, 'L', false, 1);

// Secciones
function addSection($pdf, $title, $content) {
    $pdf->Ln(8);
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Write(0, $title, '', 0, 'L', true);
    $pdf->Ln(2);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 0, $content, 0, 'L', false, 1);
}

// Agregar contenido por secciones
addSection($pdf, '1. Análisis de la Situación Actual', "- Revisión del branding actual\n- Fortalezas y debilidades\n- Análisis de la competencia\n- Tendencias del sector");

addSection($pdf, '2. Objetivos de Branding', "- Reposicionamiento\n- Mayor reconocimiento\n- Conexión emocional\n- Atraer a un nuevo público");

addSection($pdf, '3. Propuesta Estratégica', "- Propósito\n- Misión\n- Visión\n- Valores\n- Personalidad de marca\n- Tono de comunicación");

addSection($pdf, '4. Naming y Tagline', "Propuestas de nombre y eslogan con su justificación.");

addSection($pdf, '5. Identidad Visual', "- Moodboard\n- Paleta de colores\n- Tipografías\n- Logotipo (variantes y usos)\n- Aplicaciones: tarjetas, redes, papelería");

addSection($pdf, '6. Identidad Verbal (opcional)', "Lineamientos de tono, estilo, frases clave y copys.");

addSection($pdf, '7. Manual de Marca', "Normas de uso correcto e incorrecto del branding.");

addSection($pdf, '8. Propuesta de Implementación', "- Fases del proyecto: Descubrimiento, Estrategia, Identidad, Aplicación, Entrega\n- Entregables: Manual, Logotipo, Paleta, Tipografías, Aplicaciones, Archivos editables");

// Incrustar PDF externo
$pdfPath = 'branding-proposal.pdf';
$pageCount = $pdf->setSourceFile($pdfPath);
$templateId = $pdf->importPage(1);

$tplSize = $pdf->getTemplateSize($templateId);

$x = 3;
$y = 100;
$width = 200;

$pdf->useTemplate($templateId, $x, $y, $width);

addSection($pdf, '9. Cronograma Tentativo', "Plan distribuido en 20 días, organizados en 5 fases. Incluye contrato, briefing, diseño y entrega.");

// Salida del PDF
$pdf->Output('propuesta_branding.pdf', 'I');
?>
