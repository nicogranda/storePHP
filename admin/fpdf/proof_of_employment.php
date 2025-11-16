<?php
require('fpdf.php');

function getSpanishDate() {
    // Obtener la fecha en inglés
    $fecha_in = date('j \d\e F \d\e Y');

    // Array de meses en español
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

    // Reemplazar el mes en inglés por español
    foreach ($meses as $en => $es) {
        if (strpos($fecha_in, $en) !== false) {
            $fecha_in = str_replace($en, $es, $fecha_in);
            break;
        }
    }

    return $fecha_in;
}

// Llamar a la función
$date = getSpanishDate();

class PDF extends FPDF
{
    // Encabezado
    function Header()
    {
        // Logo
        $this->Image('../logo.png', 10, 10, 40); // Cambia 'logo.png' con tu archivo de logo
        $this->Ln(30); // Espacio después del logo
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 14, 'Constancia de Trabajo', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        // Definir la fuente y el texto para el pie de página
        $this->SetFont('Arial', '', 8);

        $tax_0 = "RIF: J-30580417-6\nRUC: 10462\nPATENTE: 2004-1430\nIVSS: BO28319605\nINCE: 797421\nNIL: 165449-1";
        $tax = mb_convert_encoding($tax_0, 'ISO-8859-1', 'UTF-8');
        
        $pagina_ancho = $this->GetPageWidth();
        $ancho_texto = $pagina_ancho * 0.25; // 25% del ancho de la página
        
        $this->SetY(-35); // Ajusta la posición del pie de página según sea necesario

        // Centrar el texto horizontalmente en la página
        $x_pos = 20;
        $this->SetX($x_pos);

        // Escribir el texto con MultiCell
        $this->MultiCell($ancho_texto, 4, $tax, 0, 'L');

        // Dibujar las líneas verticales al final (color orangered)
        $this->SetDrawColor(241, 90, 36); // Color orangered (#F15A24)
        $this->Line($x_pos - 2, $this->GetY() - 24, $x_pos - 2, $this->GetY() ); // Línea 1
        
        /////
        $filled_0 = "Registro Mercantil Primero de la Circunscripción Judicial del Estado Bolívar con sede en Puerto Ordaz bajo el Nro. 1 Tomo A-02 Folios del 02 al 07 del 04 de enero de 1999";
        $filled = mb_convert_encoding($filled_0, 'ISO-8859-1', 'UTF-8');
        
        // Obtener el ancho de la página y calcular el 25%
        $pagina_ancho = $this->GetPageWidth();
        $ancho_texto = $pagina_ancho * 0.25; // 25% del ancho de la página

        // Posicionar el texto hacia el final de la página (pie de página)
        $this->SetY(-35); // Ajusta la posición del pie de página según sea necesario

        // Centrar el texto horizontalmente en la página
        $x_pos = ($pagina_ancho - $ancho_texto) / 2;
        $this->SetX($x_pos);

        // Escribir el texto con MultiCell
        $this->MultiCell($ancho_texto, 4, $filled, 0, 'L');

        // Dibujar las líneas verticales al final (color orangered)
        $this->SetDrawColor(241, 90, 36); // Color orangered (#F15A24)
        $this->Line($x_pos - 5, $this->GetY() - 20, $x_pos - 5, $this->GetY() ); // Línea 2
        
        /////
        
// Texto dividido en líneas individuales
$locate_1 = "Cadena Panamericana C.A.";
$locate_2 = "UD-232 Urb. Villa Loefling";
$locate_3 = "Calle Las Orquídeas. Manzana 2 Casa 3";
$locate_4 = "Puerto Ordaz, ZP 8015. Edo. Bolívar";
$locate_5 = "Venezuela";
$locate_6 = "Teléfono: +58 286 9719524";
$locate_7 = "E-mail: cadenapanamericana@gmail.com";
$locate_8 = "www.cadenapanamericana.net";

// Obtener el ancho de la página y calcular el 25%
$pagina_ancho = $this->GetPageWidth();
$ancho_texto = $pagina_ancho * 0.25; // 25% del ancho de la página

// Posicionar el texto hacia el final de la página (pie de página)
$this->SetY(-35); // Ajusta la posición del pie de página según sea necesario

// Centrar el texto horizontalmente en la página
 // Ajusta esta posición según sea necesario
$x_pos = 150;
$this->SetX($x_pos);

// Escribir "Cadena Panamericana C.A." en negrita
$this->SetFont('Arial', 'B', 8); // Negrita
$this->Cell($ancho_texto, 4, utf8_decode($locate_1), 0, 1, 'L'); // Imprimir en negrita

// Cambiar a fuente regular para el resto del texto
$this->SetFont('Arial', '', 8);


// Escribir el resto de las líneas
$this->SetX($x_pos);
$this->Cell($ancho_texto, 4, utf8_decode($locate_2), 0, 1, 'L');
$this->SetX($x_pos);
$this->Cell($ancho_texto, 4, utf8_decode($locate_3), 0, 1, 'L');
$this->SetX($x_pos);
$this->Cell($ancho_texto, 4, utf8_decode($locate_4), 0, 1, 'L');
$this->SetX($x_pos);
$this->Cell($ancho_texto, 4, utf8_decode($locate_5), 0, 1, 'L');
$this->SetX($x_pos);
$this->Cell($ancho_texto, 4, utf8_decode($locate_6), 0, 1, 'L');
$this->SetX($x_pos);
$this->Cell($ancho_texto, 4, utf8_decode($locate_7), 0, 1, 'L');
$this->SetX($x_pos);
$this->Cell($ancho_texto, 4, utf8_decode($locate_8), 0, 1, 'L');

// Dibujar las líneas verticales al final (color orangered)
$this->SetDrawColor(241, 90, 36); // Color orangered (#F15A24)
$this->Line($x_pos - 2, $this->GetY() - 32, $x_pos - 2, $this->GetY()); // Línea vertical

        
        /////
        

    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Variables
$username = 'Anajulia';
$lastname = 'Villamizar Morales';
$id = '12.050.133';
$position = 'Consultora Jurídica';
$date_from = '1 de septiembre de 2008';
$income = '12.000 Bs.(Doce mil bolívares con 00/100)';


$date = getSpanishDate();  // La fecha actual en el formato deseado


$analist_name = 'Ing. Nicolás Granda';
$analist_position = 'Presidente';
$analist_cell_phone = '+58 414 8759762';


// Texto con las variables
$texto = "Por medio de la presente hacemos constar que la Ciudadana $username $lastname";
$texto .= ", portadora de la Cédula de Identidad $id trabaja en nuestra firma en calidad de $position";
$texto .= ", desde el $date_from devengando un sueldo mensual de $income.\n\n";
$texto .= "Constancia que emitimos a petición de la parte interesada en Puerto Ordaz hoy $date.\n\n\n";

// Usar MultiCell para texto con saltos de línea
$pdf->MultiCell(0, 8, utf8_decode($texto), 0, 'L');

// Definir la posición inicial del texto
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(100, 6, utf8_decode("$analist_name")); // Limitar el ancho del texto para dejar espacio para la imagen
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 6, "$analist_position", 0, 1); // Limitar el ancho del texto
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(100, 6, utf8_decode("$analist_cell_phone")); // Limitar el ancho del texto

// Colocar la imagen al lado derecho
$x = $pdf->GetX(); // Obtener la coordenada X actual después del texto
$y = $pdf->GetY() - 30; // Ajustar la coordenada Y para alinear con el texto
$pdf->Image('../seal.png', $x + 27, $y, 30); // Colocar la imagen, ajustando X e Y




$pdf->Output();
?>
