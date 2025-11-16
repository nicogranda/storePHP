<?php
// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// Obtener la fecha en español
function getSpanishDate() {
    $fecha_in = date('j \d\e F \d\e Y');
    $meses = [
        'January' => 'enero',
        'February' => 'febrero',
        'March' => 'marzo',
        'April' => 'abril',
        'May' => 'mayo',
        'June' => 'junio',
        'July' => 'julio',
        'August' => 'agosto',
        'September' => 'septiembre',
        'October' => 'octubre',
        'November' => 'noviembre',
        'December' => 'diciembre',
    ];
    foreach ($meses as $en => $es) {
        if (strpos($fecha_in, $en) !== false) {
            $fecha_in = str_replace($en, $es, $fecha_in);
            break;
        }
    }
    return $fecha_in;
}

$date = getSpanishDate();

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicolás Granda');
$pdf->SetTitle('Constancia de Trabajo');
$pdf->SetSubject('Constancia');
$pdf->SetKeywords('TCPDF, PDF, constancia, trabajo');

// Desactivar el header y el footer predeterminados
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Establecer margen superior (encabezado)
$pdf->SetHeaderMargin(0);

// Establecer margen inferior (pie de página)
$pdf->SetFooterMargin(0);

// Establecer márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// add a page
$pdf->AddPage();

// set font
$pdf->SetFont('helvetica', '', 12);

// Logo
$pdf->Image('../../logo.png', 10, 10, 50);
$pdf->Ln(70); // Espacio después del logo

// Variables
$username = 'Anajulia';
$lastname = 'Villamizar Morales';
$id = 'V 12.050.133';
$position = 'Consultora Jurídica';
$date_from = '1 de septiembre de 2008';
$income = '12.000 Bs.(Doce mil bolívares con 00/100)';
$analist_name = 'Ing. Nicolás Granda Bauza';
$analist_position = 'Presidente';
$analist_cell_phone = '+58 414 8759762';

$tax_info = "RIF: J-30580417-6\nRUC: 10462\nPATENTE: 2004-1430\nIVSS: BO28319605\nINCE: 797421\nNIL: 165449-1";
$filled = "Registro Mercantil Primero de la Circunscripción Judicial del Estado Bolívar con sede en Puerto Ordaz bajo el Nro. 1 Tomo A-02 Folios del 02 al 07 del 04 de enero de 1999";
$located = "<b>Cadena Panamericana C.A.</b><BR>UD-232 Urb. Villa Loefling<BR>Calle Las Orquídeas. Manzana 2 Casa 3<BR>Puerto Ordaz, ZP 8015. Edo. Bolívar<BR>Venezuela<BR>Teléfono: +58 286 9719524<BR>E-mail: cadenapanamericana@gmail.com";

// Texto principal con las variables
$html = 
'<h1 style="text-align:center;">Constancia de Trabajo</h1><br><br><br>

<p style="text-align:justify;">Por medio de la presente hacemos constar que la Ciudadana <b>'.$username.' '.$lastname.'</b> portadora de
la Cédula de Identidad <b>'.$id.'</b>, trabaja en nuestra firma en calidad de <b>'.$position.'</b>, desde el '.$date_from.' , devengando un sueldo mensual de <b>'.$income.'.</b><br><br>
Constancia que emitimos a petición de la parte interesada en Puerto Ordaz hoy, '.$date.'.<br><br>

</p>
<p>'.$analist_name.'<br>'
   .$analist_position.'<br>
<span style="font-size:8;">'.$analist_cell_phone.'</span><br>
</p>';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln(100); // Espacio después del texto principal

// Footer personalizado (tax_info, filled, located)
$pdf->SetY(-50); // Posicionar el contenido en la parte inferior
$pdf->SetFont('helvetica', '', 8); // Tamaño de la fuente del footer

// Obtener la posición actual del eje Y
$y_position = $pdf->GetY();

// Footer - Dividido en tres columnas
// Columna Izquierda (tax_info)
$pdf->SetXY(20, $y_position); // Posicionar en el eje X (izquierda)
$pdf->MultiCell(50, 5, $tax_info, 0, 'L', false);

// Columna Central (filled)
$pdf->SetXY(70, $y_position); // Posicionar en el centro
$pdf->MultiCell(50, 5, $filled, 0, 'L', false);

// Columna Derecha (located)
$pdf->SetXY(150, $y_position); // Ajustar hacia la izquierda en el eje X
$pdf->writeHTMLCell(60, 5, 140, $y_position, $located, 0, 1, 0, true, 'L', true);

// Footer - Dividido en tres columnas con borde en la izquierda
// Columna Izquierda (tax_info)
$pdf->SetXY(20, $y_position); // Posicionar en el eje X (izquierda)
$pdf->MultiCell(50, 5, $tax_info, 'L', 'L', false); // 'L' aplica borde en el lado izquierdo

// Columna Central (filled)
$pdf->SetXY(70, $y_position); // Posicionar en el centro
$pdf->MultiCell(50, 5, $filled, 'L', 'L', false); // 'L' aplica borde en el lado izquierdo

// Columna Derecha (located)
$pdf->SetXY(150, $y_position); // Ajustar hacia la izquierda en el eje X
$pdf->writeHTMLCell(60, 5, 140, $y_position, $located, 'L', 1, 0, true, 'L', true); // 'L' aplica borde en el lado izquierdo


// Output PDF
$pdf->Output('constancia_trabajo.pdf', 'I');
