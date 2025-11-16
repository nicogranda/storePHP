<?php
ob_start();
// include class
require ('fpdf.php');

// Crear el objeto FPDF
$pdf = new FPDF();
$pdf->AddPage(); // Agrega la primera página

// Ahora puedes usar $pdf
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Form 5472 - Página 1',0,1,'C');

// Asignar variables con valores conocidos o vacíos si no se reciben
$corp_name = $_POST['corp_name'] ?? 'Ikusa LLC';
$corp_address = $_POST['corp_address'] ?? '8735 Dunwoody Place, Suite 6, Atlanta, GA 30350';
$ein = $_POST['ein'] ?? '87-2680481';
$total_assets = $_POST['total_assets'] ?? 3000.00;

$shareholder_name = $_POST['shareholder_name'] ?? 'Nicolas Granda Bauza';
$shareholder_country = $_POST['shareholder_country'] ?? 'Spain';
$shareholder_ftin = $_POST['shareholder_ftin'] ?? '';

$line_9 = $_POST['line_9'] ?? 0.00;
$line_10 = $_POST['line_10'] ?? 0.00;
$line_14 = $_POST['line_14'] ?? 0.00;
$line_15 = $_POST['line_15'] ?? 1942.25;
$line_21 = $_POST['line_21'] ?? 2847.34;

$csa_description = $_POST['csa_description'] ?? '';
$csa_benefit_share = $_POST['csa_benefit_share'] ?? '';
$beat_payments = $_POST['beat_payments'] ?? 0.00;
$beat_tax_benefits = $_POST['beat_tax_benefits'] ?? 0.00;


// --- Página 1 ---
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Form 5472 - Página 1',0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,10,'Corporacion: ' . $corp_name,0,1);
$pdf->Cell(0,10,'Dirección: ' . $corp_address,0,1);
$pdf->Cell(0,10,'EIN: ' . $ein,0,1);
$pdf->Cell(0,10,'Activos Totales: $' . number_format($total_assets, 2),0,1);

// --- Página 2 ---
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);

// Encabezado de la tabla (con descripción más ancha y altura más baja)
$pdf->SetFillColor(240, 240, 240);
$cellHeight = 5; // altura más baja
$descWidth = 150;

$pdf->Cell(10, $cellHeight, '#', 1, 0, 'C', true);
$pdf->Cell($descWidth, $cellHeight, 'Descripción', 1, 0, 'C', true); // más ancha
$pdf->Cell(30, $cellHeight, 'Monto', 1, 1, 'C', true); // también más ancha para compensar

$lineas = [
    [9, "Ventas de inventario"],
    [10, "Ventas de propiedad tangible"],
    [11, "Pagos recibidos por contribución de plataforma"],
    [12, "Pagos recibidos por reparto de costos"],
    ["13a", "Alquileres recibidos (no intangibles)"],
    ["13b", "Regalías recibidas (no intangibles)"],
    [14, "Ventas/licencias de propiedad intangible"],
    [15, "Servicios técnicos, científicos, etc."],
    [16, "Comisiones recibidas"],
    ["17b", "Montos prestados – saldo final o promedio mensual"],
    [18, "Intereses recibidos"],
    [19, "Primas de seguros recibidas"],
    [20, "Comisiones por garantías de préstamos recibidas"],
    [21, "Otros ingresos recibidos"],
    [22, "**Total líneas 9 a 21**"],

    [23, "Compras de inventario"],
    [24, "Compras de propiedad tangible"],
    [25, "Pagos por contribución de plataforma"],
    [26, "Pagos por reparto de costos"],
    ["27a", "Alquileres pagados (no intangibles)"],
    ["27b", "Regalías pagadas (no intangibles)"],
    [28, "Licencias/compras de propiedad intangible"],
    [29, "Pagos por servicios técnicos, ingeniería, etc."],
    [30, "Comisiones pagadas"],
    ["31b", "Montos prestados – saldo final o promedio mensual"],
    [32, "Intereses pagados"],
    [33, "Primas de seguros pagadas"],
    [34, "Comisiones por garantías de préstamos pagadas"],
    [35, "Otros pagos realizados"],
    [36, "**Total líneas 23 a 35**"],
];

foreach ($lineas as $linea) {
    $num = $linea[0];
    $desc = $linea[1];
    $is_total = strpos($desc, "Total") !== false;

    // Si es total, usar negrita
    if ($is_total) {
        $pdf->SetFont('', 'B');
    }

// Calcular altura necesaria según el texto
$nbLines = $pdf->GetStringWidth($desc) > $descWidth ? ceil($pdf->GetStringWidth($desc) / $descWidth) : 1;
$actualHeight = $cellHeight * $nbLines;

// Imprimir número
$pdf->Cell(10, $actualHeight, $num, 1, 0, 'C');

// Guardar posición X e Y antes del multicell
$x = $pdf->GetX();
$y = $pdf->GetY();

// Imprimir descripción
$pdf->MultiCell($descWidth, $cellHeight, $desc, 1);

// Volver a X original + ancho descripción
$pdf->SetXY($x + $descWidth, $y);

// Imprimir monto vacío
$pdf->Cell(30, $actualHeight, '', 1, 1, 'R');


    if ($is_total) {
        $pdf->SetFont('', '');
    }
}

$pdf->Ln(5);

$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Form 5472 - Página 2',0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,10,'Accionista extranjero: ' . $shareholder_name,0,1);
$pdf->Cell(0,10,'País: ' . $shareholder_country,0,1);
$pdf->Cell(0,10,'FTIN: ' . $shareholder_ftin,0,1);

// --- Página 3 ---
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Form 5472 - Página 3 (Transacciones)',0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,10,'Ventas inventario: $' . number_format($line_9, 2),0,1);
$pdf->Cell(0,10,'Ventas propiedad tangible: $' . number_format($line_10, 2),0,1);
$pdf->Cell(0,10,'Licencias intangibles: $' . number_format($line_14, 2),0,1);
$pdf->Cell(0,10,'Servicios técnicos: $' . number_format($line_15, 2),0,1);
$pdf->Cell(0,10,'Otros pagos: $' . number_format($line_21, 2),0,1);

// --- Página 4 ---
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Form 5472 - Página 4 (CSA y BEAT)',0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->MultiCell(0,7,'CSA: ' . $csa_description);
$pdf->Cell(0,10,'% Beneficios CSA: ' . $csa_benefit_share . '%',0,1);
$pdf->Cell(0,10,'Pagos 59A: $' . number_format($beat_payments,2),0,1);
$pdf->Cell(0,10,'Beneficios BEAT: $' . number_format($beat_tax_benefits,2),0,1);

if (headers_sent($file, $line)) {
    die("¡Ya se enviaron headers en $file línea $line!");
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="Form5472_Completo.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output('I', 'Form5472_Completo.pdf');
ob_end_flush();

?>
