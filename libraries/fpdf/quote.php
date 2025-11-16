<?php
require('fpdf.php');

// Conexión a la base de datos
include '../../../app/config/connection.php';

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
$quote_id = $_GET['id'];

$pdf = new FPDF('P', 'mm', 'A4'); // Mantén la codificación en UTF-8
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Logo
$logoPath = 'https://ikusa.net/images/logo.png';
$pdf->Image($logoPath, 120, 5, 30);

// Texto "Quote"
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 5, utf8_decode('Quote'), 0, 1, 'L');

// Formatea el ID con ceros a la izquierda
$pdf->SetTextColor(255, 0, 0);
$pdf->SetFont('Times', 'B', 16);


//Number
$formatted_quote_id = str_pad($quote_id, 9, '0', STR_PAD_LEFT);
$pdf->Cell(30, 5, utf8_decode($formatted_quote_id), 0, 1, 'L');

$pdf->SetTextColor(0, 0, 0);


// Añadir información del cliente
$quotes = $mysqli->query("SELECT * FROM quotes WHERE id = '$quote_id'");
if ($quotes && $row = $quotes->fetch_assoc()) {
    $client_id = $row['client_id'];
    $created_at = $row['created_at'];
    $clients = $mysqli->query("SELECT * FROM clients WHERE id = '$client_id'");

    if ($clients && $client = $clients->fetch_assoc()) {
$pdf->SetFont('Arial', '', 10);

//Date
$formatted_date = date("m-d-Y", strtotime($created_at));
$pdf->Cell(30, 5, utf8_decode($formatted_date), 0, 1, 'L');

//Business
$x = 120 + 30 + 10; // 120 es la posición inicial de la imagen, 30 es el ancho de la imagen y 10 el espacio de separación
$y = 5; // Mantienes la misma altura que la imagen


// Establece la posición del texto
$pdf->SetFont('Arial', 'B', 8); // Cambia 'Arial' por la fuente que estés usando
$pdf->SetXY($x, $y);
$pdf->Cell(0, 4, "Ikusa LLC", 0, 1);
$pdf->SetFont('Arial', '', 8); // Volver al estilo normal
$pdf->SetX($x);
$pdf->Cell(0, 4, "8735 Dunwoody Place, Ste R", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 4, "Atlanta, GA 30350", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 4, "United States", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 4, "https://ikusa.net", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 4, "DUNS: 100861837", 0, 1);


$pdf->SetFont('Arial', '', 10); // Cambia 'Arial' por la fuente que estés usando
$pdf->Ln(10);
$pdf->Cell(20, 5, utf8_decode("Client: "), 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, utf8_decode($client['name']), 0, 1);

// Muestra los detalles de la dirección, alineados debajo del nombre
$pdf->SetX(30); // Ajusta X para alinear con el nombre del cliente
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, utf8_decode($client['address']), 0, 1);

$pdf->SetX(30); // Ajusta X nuevamente para las siguientes líneas
$pdf->Cell(0, 5, utf8_decode($client['city'] . ', ' . $client['state'] . ', ' . $client['zip_code']), 0, 1);

$pdf->SetX(30); // Alinea la última celda también
$pdf->Cell(0, 5, utf8_decode($client['country']), 0, 1);

    } else {
        $pdf->Cell(0, 5, utf8_decode("Client information not found."), 0, 1);
    }
}
//$formatted_date = date("F d, Y", strtotime($created_at));
//$pdf->Cell(30, 5, utf8_decode($formatted_date), 0, 1, 'L');



// Tabla de productos y detalles
$pdf->Ln(10);
$pdf->SetFillColor(255, 69, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(50, 10, utf8_decode('Goods/Service'), '', 0, 'C', true);
$pdf->Cell(20, 10, 'Quantity', '', 0, 'C', true);
$pdf->Cell(35, 10, utf8_decode('Unit Value'), '', 0, 'C', true);
$pdf->Cell(15, 10, 'Unit', '', 0, 'C', true);
$pdf->Cell(25, 10, utf8_decode('Discount (%)'), '', 0, 'C', true);
$pdf->Cell(20, 10, 'Vat Rate', '', 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode('Total'), '', 1, 'C', true);

$pdf->SetTextColor(0, 0, 0);

$total = $subtotal = $vat = 0;

$quotes_details = $mysqli->query("SELECT * FROM quotes_details WHERE quote_id = '$quote_id'");
while ($row = $quotes_details->fetch_assoc()) {
    $product_id = $row['product_id'];
    $note = $row['note'];
    $unit_value = $row['unit_value'];
    $quantity = $row['quantity'];
    $discount = $row['discount'];
    $vat_rate = $row['vat_rate'];

    // Calcular balance
    $balance = $quantity * $unit_value * (1 - $discount / 100) * (1 + $vat_rate / 100);
    $subtotal += $balance;

$products = $mysqli->query("SELECT * FROM products WHERE id = '$product_id'");
while ($product = $products->fetch_assoc()) {
    // Nombre en negritas
    $pdf->SetFont('Arial', 'B', 10); // Cambia a negritas
    $pdf->Cell(50, 5, utf8_decode($product["name"]), '', 0);

    // Resto de las columnas
    $pdf->SetFont('Arial', '', 10); // Regresa a texto normal
    $pdf->Cell(20, 5, $quantity, '', 0, 'R');
    $pdf->Cell(35, 5, number_format($unit_value, 2), '', 0, 'R');
    $pdf->Cell(15, 5, utf8_decode($product["unit"]), '', 0, 'R');
    $pdf->Cell(25, 5, number_format($discount, 2), '', 0, 'R');
    $pdf->Cell(20, 5, number_format($vat_rate, 2), '', 0, 'R');
    $pdf->Cell(30, 5, number_format($balance, 2), '', 1, 'R');

    // Nota debajo de "name"
    $pdf->SetX(10); // Mueve el cursor a la posición inicial de "name"
    $pdf->MultiCell(50, 5, utf8_decode($note), ''); // Nota sin bordes, ajustada solo a la columna de "name"

    // Línea de separación
    $pdf->Cell(195, 0, '', 'T', 1); // Línea horizontal que abarca todo el ancho
}


}

// Totales
$total = $subtotal + $vat;
$pdf->Cell(165, 10, 'Sub-total', '', 0, 'L');
$pdf->Cell(30, 10, number_format($subtotal, 2), '', 1, 'R');
$pdf->Cell(165, 10, utf8_decode('Sales tax not applicable'), '', 0, 'L');
$pdf->Cell(30, 10, number_format($vat, 2), '', 1, 'R');
$pdf->Cell(165, 10, 'Total', 'B', 0, 'L');
$pdf->Cell(30, 10, number_format($total, 2), 'B', 1, 'R');

// $x = 160; // 
// $y = 270;
// $pdf->SetXY($x, $y);
// $pdf->SetX($x);
// $pdf->Cell(0, 4, "https://ikusa.net", 0, 1);

// Pie de página
$y = 270; // Posición vertical para los íconos

$lineY = $y - 5; // Coordenada Y para la línea de separación antes de los íconos

// Texto "Método de pago"
$pdf->SetFont('Arial', 'B', 12); // Fuente en negrita
$pdf->SetXY(150, $lineY); // Establecer la posición horizontal a la derecha de la página
$pdf->Cell(0, 10, utf8_decode('Método de pago'), 0, 1, 'R'); // Escribir el texto a la derecha

// Imagen del método de pago (Bizum)
$pdf->Image('https://ikusa.net/images/banks/bizum.png', 165, $lineY + 10, 20); // Posición debajo del texto
$pdf->Image('https://ikusa.net/images/banks/zelle.png', 145, $lineY + 10, 20);
// Íconos
$icons = [
    'https://ikusa.net/images/rrss/instagram.png',
    'https://ikusa.net/images/rrss/facebook.png',
    'https://ikusa.net/images/rrss/linkedIn.png'
];

// Tamaño de cada ícono
$iconSize = 10;

// Calcular el espacio total que ocuparán los íconos
$totalWidth = count($icons) * $iconSize + (count($icons) - 1) * 5; // Añadir un espacio entre íconos

// Calcular la posición inicial para centrar los íconos
$x = (210 - $totalWidth) / 2; // Centrado en una página de 210mm de ancho (A4)

// Dibujar los íconos
foreach ($icons as $icon) {
    $pdf->Image($icon, $x, $y, $iconSize); // Insertar cada ícono
    $x += $iconSize + 5; // Incrementar la posición horizontal para el próximo ícono
}

// Salida del PDF
$pdf->Output('I', 'quote_' . $quote_id . '.pdf');
?>
