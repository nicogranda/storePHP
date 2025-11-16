<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class IrsController
{
    public function __construct() {}

    public function form5472()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Generar PDF
            require_once "fpdf/form5472.php";
            return;
        }

        // Si no es POST, mostrar el formulario
        include  '../../app/views/admin/irs/form5472.php';
      
    }
}

