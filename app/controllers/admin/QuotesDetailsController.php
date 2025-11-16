<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../app/libraries/admin/Model.php';
require_once '../../app/models/admin/Quote.php';
require_once '../../app/models/admin/Client.php';
require_once '../../app/models/admin/QuoteDetail.php';
require_once '../../app/models/admin/Product.php';
require_once '../../app/models/admin/Category.php';
require_once '../../app/models/admin/OperationData.php'; // Agregar esta línea

use App\Models\Admin\OperationData; // Si usas namespaces, agrégalo aquí


use App\Models\Admin\Quote;
use App\Models\Admin\Client;
use App\Models\Admin\QuoteDetail;

use App\Models\Admin\Category;
use App\Models\Admin\Product;

class QuotesDetailsController
{
    private $quoteDetail;

    public function __construct()
    {
        $this->quoteDetail = new QuoteDetail();
        $this->category = new Category();
        $this->product = new Product();
    }

    // Crear nuevo detalle de la cotización
    public function create($item)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
                $item = [
                    'quote_id' => $_POST['quote_id'],
                    'product_id' => $_POST['product_id'],
                    'quantity' => $_POST['quantity'],
                    'unit_value' => $_POST['unit_value'],
                    'discount' => $_POST['discount'],
                    'vat_rate' => $_POST['vat_rate'],
                    'note' => $_POST['note'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
               
           
                $quoteDetail = $this->quoteDetail->create($item);

                
                header("Location: index.php?page=quotes&action=show&id=".$item['quote_id']);
                
        } else { 
            
            $categories = $this->category->getAll();
            $products = $this->product->getAll();
            //var_dump($products);
            include "../../app/views/admin/sales/quotes_details/create.php";
        
        }

    }


    // Eliminar un detalle de la cotización
    public function delete($id)
    {
        $item = $this->quoteDetail->getById($id);
        $operationId = $item['quote_id'];
    
        // Eliminar el detalle
        $this->quoteDetail->delete($id);
    
        // Redirigir después de eliminar
        header("Location: index.php?page=quotes&action=show&id=" . $operationId);
        exit(); // Asegúrate de que no se ejecute más código
    }
}
