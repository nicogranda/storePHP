<?php
require('fpdf.php');

// Conexión a la base de datos
//$mysqli = new mysqli('host', 'user', 'password', 'database');

include '../../partials/database.php';


if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
$quote_id = $_GET['id'];

//$proforma_invoice_id = 299;
//$_POST['proforma_invoice_id'];

// Crear una nueva instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Logo
//$pdf->Image('../../images/favicon.png', 10, 6, 30);

// Título
//$pdf->SetXY(10, 10);
//$pdf->Cell(30, 10, 'quote', 0, 1, 'C');

// Ruta a tu archivo de logo
$logoPath = 'https://ikusa.net/images/logo.png'; // Ajusta esto a la ruta de tu logo

// Añadir logo
$pdf->Image($logoPath, 120, 5, 30); // Ajusta la posición y el tamaño del logo

// Texto "Request" en Arial, tamaño 12
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 5, 'Quote', 0, 1, 'C'); // Altura reducida a 5

// Cambia el color del texto a rojo
$pdf->SetTextColor(255, 0, 0);

// Cambia la fuente a Times New Roman en negrita, tamaño 12
$pdf->SetFont('Times', 'B', 12);

// Asegura que el $quote_id tenga 6 dígitos rellenando con ceros a la izquierda
$formatted_quote_id = str_pad($quote_id, 7, '0', STR_PAD_LEFT);

// Muestra el $quote_id formateado (ejemplo: 0000623) en rojo, debajo de "Request"
$pdf->Cell(30, 5, $formatted_quote_id, 0, 1, 'C'); // Altura reducida a 5

// Restablece el color a negro si es necesario para el texto siguiente
$pdf->SetTextColor(0, 0, 0);

// Si necesitas más espacio antes del siguiente contenido, puedes agregar un salto de línea
$pdf->Ln(10);

// Añade un salto de línea si lo necesitas para el siguiente contenido
$pdf->Ln(20);

$x = 120 + 30 + 10; // 120 es la posición inicial de la imagen, 30 es el ancho de la imagen y 10 el espacio de separación
$y = 5; // Mantienes la misma altura que la imagen

// Cambia el tamaño de la fuente a 8 puntos
$pdf->SetFont('Arial', '', 8); // 'Arial' es la fuente, '' es para texto normal, y 8 es el tamaño

// Establece la posición del texto
$pdf->SetXY($x, $y);
$pdf->Cell(0, 5, "Ikusa LLC", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 5, "8735 Dunwoody Place, Ste R", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 5, "Atlanta, GA 30350", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 5, "United States", 0, 1);

// Obtén los datos del presupuesto
$quotes = $mysqli->query("SELECT * FROM quotes WHERE id = '$quote_id'");

if ($quotes && $row = $quotes->fetch_assoc()) {
    $client_id = $row['client_id'];

    // Obtén los datos del cliente
    $clients = $mysqli->query("SELECT * FROM clients WHERE id = '$client_id'");
    
    if ($clients && $client = $clients->fetch_assoc()) {
        // Añade los datos del cliente al PDF
        $x = 20;
        $y = 30;
        $pdf->SetXY($x, $y);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, "Client:", 0, 1);
        $pdf->SetX($x);
        $pdf->Cell(0, 5, "Name: " . $client['name'], 0, 1);
        $pdf->SetX($x);
        $pdf->Cell(0, 5, "Address: " . $client['address'], 0, 1);
        $pdf->SetX($x);
        $pdf->Cell(0, 5, "City: " . $client['city'], 0, 1);
        $pdf->SetX($x);
        $pdf->Cell(0, 5, "Zip: " . $client['zip'], 0, 1);
        $pdf->SetX($x);
        $pdf->Cell(0, 5, "Country: " . $client['country'], 0, 1);
    } else {
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 5, "Client information not found.", 0, 1);
    }
} else {
    $pdf->SetXY($x, $y);
    $pdf->Cell(0, 5, "quote information not found.", 0, 1);
}

$x = 10; // 
$y = 70;
$pdf->SetXY($x, $y);
// Cabecera de la tabla
$pdf->SetFillColor(255, 69, 0); // Orangered
$pdf->SetTextColor(255, 255, 255); // White
$pdf->Cell(70, 10, 'Goods/Service', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Unit Value (EUR)', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Unit', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Discount', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Total (EUR)', 1, 1, 'C', true);

// Contenido de la tabla
$pdf->SetTextColor(0, 0, 0); // Black
$total = 0;


if ($quotes_details = $mysqli->query("SELECT * FROM quotes_details WHERE quote_id = '$quote_id' ")) {
    while ($row = $quotes_details->fetch_assoc()) {
        $id = $row['id'];
        $product_id = $row['product_id'];
        $unit_value = $row['unit_value'];
        $quantity = $row['quantity'];
        $discount = $row["discount"];
        $balance = $quantity * $unit_value * (1 - $discount / 100);
        $total += $balance;

        if ($products = $mysqli->query("SELECT * FROM products WHERE id = '$product_id' ")) {
            while ($row = $products->fetch_assoc()) {
                $product = $row["name"];
                $unit = $row["unit"];

                $pdf->Cell(70, 10, $product, 1);
                $pdf->Cell(20, 10, $quantity, 1, 0, 'R');
                $pdf->Cell(30, 10, number_format($unit_value, 2), 1, 0, 'R');
                $pdf->Cell(20, 10, $unit, 1, 0, 'R');
                $pdf->Cell(20, 10, number_format($discount, 2), 1, 0, 'R');
                $pdf->Cell(30, 10, number_format($balance, 2), 1, 1, 'R');
            }
        }
    }
}

$pdf->Cell(160, 10, 'Total', 1);
$pdf->Cell(30, 10, number_format($total, 2), 1, 1, 'R');


// Output the PDF
$pdf->Output('I', 'proforma_invoice.pdf');
?>
