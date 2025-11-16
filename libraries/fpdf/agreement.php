<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('https://ikusa.net/images/logo.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        // $this->Cell(30,10,'**CONTRATO DE SERVICIOS DE CATERING**',1,0,'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}
$proforma_invoice_id = $_GET['id'];
include '../../configuration.inc.php';
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

if ($sql_proforma_invoices = $mysqli->query("SELECT * FROM proforma_invoices where id = '$proforma_invoice_id '")) {
    while ( $row = $sql_proforma_invoices->fetch_assoc() ) {
        $proforma_invoice_id = $row['id']; 
    }
}
//$pdf->Cell(40, 20, $proforma_invoice_id , 1, 0, 'R', true);



$pdf->SetFillColor(255, 69, 0); // Orangered
$pdf->SetTextColor(255, 69, 0); // White
$pdf->Cell(0, 10, 'Proforma Invoice ' . $proforma_invoice_id, 0, 1, 'R');
$pdf->SetTextColor(255, 255, 255); // White
$pdf->Cell(70, 10, 'Goods/Service', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Unit Value', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Unit', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Discount', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Total', 1, 1, 'C', true);

$pdf->SetTextColor(0, 0, 0); // Black
$total = 0;

// Asegúrate de que $proforma_invoice_id está definido antes de usarlo en la consulta
//$proforma_invoice_id = $_POST['proforma_invoice_id'];


if ($proforma_invoices_details = $mysqli->query("SELECT * FROM proforma_invoices_details WHERE proforma_invoice_id = '$proforma_invoice_id'")) {
    while ($row = $proforma_invoices_details->fetch_assoc()) {
        $id = $row['id'];
        $product_id = $row['product_id'];
        $unit_value = $row['unit_value'];
        $quantity = $row['quantity'];
        $discount = $row["discount"];
        $balance = $quantity * $unit_value * (1 - $discount / 100);
        $total += $balance;

        if ($products = $mysqli->query("SELECT * FROM products WHERE id = '$product_id'")) {
            while ($product_row = $products->fetch_assoc()) {
                $product = $product_row["name"];
                $unit = $product_row["unit"];

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

// Convertir texto a ISO-8859-1
//$texto = "Total";
//$texto_convertido = mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
//$pdf->MultiCell(0, 7, $texto_convertido, 0, 1);

$pdf->Output();
?>
