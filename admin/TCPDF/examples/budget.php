<?php
require_once('tcpdf_include.php');
require_once('../../../app/config/connection.php'); // Ajusta la ruta según la ubicación de tu archivo database.php
$budget_id = $_GET['id'];

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Autor del Documento');
$pdf->SetTitle('Budget');
$pdf->SetSubject('Budget Details');
$pdf->SetKeywords('TCPDF, PDF, budget, proforma');

// set default header data
$pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, 'Budget', 'Request: '.$budget_id , array(0,64,255), array(0,64,128));
$pdf->SetFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->SetHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetHeaderMargin(15); // Ajusta el margen del encabezado a 0


// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->SetImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
$pdf->SetFont('dejavusans', '', 10, '', true);

// Add a page
$pdf->AddPage();
// Ruta a tu archivo de logo
$logoPath = 'https://ikusa.net/images/logo.png'; // Ajusta esto a la ruta de tu logo

// Añadir logo
$pdf->Image($logoPath, 15, 0, 30); // Ajusta la posición y el tamaño del logo

// Establecer el color

// Cabecera de la tabla
$pdf->SetFillColor(27, 68, 156); // 
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

if ($budgets_details = $mysqli->query("SELECT * FROM budgets_details WHERE budget_id = '$budget_id' ")) {
    while ($row = $budgets_details->fetch_assoc()) {
        $product_id = $row['product_id'];
        $unit_value = $row['unit_value'];
        $quantity = $row['quantity'];
        $discount = $row['discount'];
       
        if ($products = $mysqli->query("SELECT name, unit FROM products WHERE id = '$product_id'")) {
            // Verifica si la consulta devuelve algún resultado
            if ($products->num_rows > 0) {
                $product_row = $products->fetch_assoc();
                $product = $product_row['name'];
                $unit = $product_row['unit'];
                $balance = $quantity * $unit_value * (1 - $discount / 100);
                $total += $balance;
                $pdf->Cell(70, 10, $product, 1);
                $pdf->Cell(20, 10, $quantity, 1, 0, 'R');
                $pdf->Cell(30, 10, number_format($unit_value, 2), 1, 0, 'R');
                $pdf->Cell(20, 10, $unit, 1, 0, 'R');
                $pdf->Cell(20, 10, number_format($discount, 2), 1, 0, 'R');
                $pdf->Cell(30, 10, number_format($balance, 2), 1, 1, 'R');
            } else {
                // Maneja el caso cuando no se encuentra el producto
                //$product = 'Unknown Product';
                //$unit = 'Unknown Unit';
            }
            
            // Imprime los datos en el PDF
         
        } else {
            // Maneja el caso cuando la consulta falla
         
        }
    
    }
}

// Total
$pdf->Cell(160, 10, 'Total', 1);
$pdf->Cell(30, 10, number_format($total, 2), 1, 1, 'R');

// Close and output PDF document
$pdf->Output('budget.pdf', 'I');
?>
