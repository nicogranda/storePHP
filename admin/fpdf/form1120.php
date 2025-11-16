<?php
require('fpdf.php');

// Recibir datos del formulario
$name = $_POST['name'] ?? '';
$address = $_POST['address'] ?? '';
$cityStateZip = $_POST['city_state_zip'] ?? '';
$ein = $_POST['ein'] ?? '';
$dateIncorporated = $_POST['date_incorporated'] ?? '';
$totalAssets = $_POST['total_assets'] ?? 0;

// Datos financieros simplificados
$line_1a = $_POST['line_1a'] ?? 0;
$line_1b = $_POST['line_1b'] ?? 0;
$line_2 = $_POST['line_2'] ?? 0;
$line_5 = $_POST['line_5'] ?? 0;
$line_6 = $_POST['line_6'] ?? 0;

$line_12 = $_POST['line_12'] ?? 0;
$line_13 = $_POST['line_13'] ?? 0;
$line_22 = $_POST['line_22'] ?? 0;
$line_26_desc = $_POST['line_26_desc'] ?? '';
$line_26 = $_POST['line_26'] ?? 0;

$officer_name = $_POST['officer_name'] ?? '';
$officer_title = $_POST['officer_title'] ?? '';
$signature_date = $_POST['signature_date'] ?? '';

// Cálculos
$gross_income = $line_1a - $line_1b;
$gross_profit = $gross_income - $line_2;
$total_deductions = $line_12 + $line_13 + $line_22 + $line_26;
$taxable_income = $gross_profit + $line_5 + $line_6 - $total_deductions;

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Form 1120 - U.S. Corporation Income Tax Return (Resumen)', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);
$pdf->Cell(0, 10, "Corporacion: $name", 0, 1);
$pdf->Cell(0, 10, "Direccion: $address", 0, 1);
$pdf->Cell(0, 10, "Ciudad/Estado/ZIP: $cityStateZip", 0, 1);
$pdf->Cell(0, 10, "EIN: $ein", 0, 1);
$pdf->Cell(0, 10, "Fecha de Incorporacion: $dateIncorporated", 0, 1);
$pdf->Cell(0, 10, "Activos Totales: $ " . number_format($totalAssets, 2), 0, 1);

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Ingresos y Deducciones', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Ventas Brutas (1a): $ " . number_format($line_1a, 2), 0, 1);
$pdf->Cell(0, 10, "Devoluciones (1b): $ " . number_format($line_1b, 2), 0, 1);
$pdf->Cell(0, 10, "Ingreso Neto (1c): $ " . number_format($gross_income, 2), 0, 1);
$pdf->Cell(0, 10, "Costo de Bienes Vendidos (2): $ " . number_format($line_2, 2), 0, 1);
$pdf->Cell(0, 10, "Ganancia Bruta (3): $ " . number_format($gross_profit, 2), 0, 1);
$pdf->Cell(0, 10, "Intereses (5): $ " . number_format($line_5, 2), 0, 1);
$pdf->Cell(0, 10, "Rentas (6): $ " . number_format($line_6, 2), 0, 1);
$pdf->Cell(0, 10, "Total Deducciones: $ " . number_format($total_deductions, 2), 0, 1);
$pdf->Cell(0, 10, "Ingreso Imponible: $ " . number_format($taxable_income, 2), 0, 1);

$pdf->Ln(10);
$pdf->Cell(0, 10, "Firmado por: $officer_name", 0, 1);
$pdf->Cell(0, 10, "Cargo: $officer_title", 0, 1);
$pdf->Cell(0, 10, "Fecha: $signature_date", 0, 1);

$pdf->Output('I', 'Form1120_Resumen.pdf');
