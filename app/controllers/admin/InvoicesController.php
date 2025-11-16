<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../app/libraries/admin/Model.php';
require_once '../../app/models/admin/Quote.php';
require_once '../../app/models/admin/Client.php';
require_once '../../app/models/admin/QuoteDetail.php';
require_once '../../app/models/admin/Product.php';
require_once '../../app/models/admin/OperationData.php'; // Agregar esta línea
require_once "../../app/models/admin/Invoice.php";
require_once "../../app/models/admin/User.php";

use App\Models\Admin\OperationData; // Si usas namespaces, agrégalo aquí

use App\Models\Admin\Quote;
use App\Models\Admin\Client;
use App\Models\Admin\QuoteDetail;
use App\Models\Admin\Product;
use App\Models\Admin\Invoice;
use App\Models\Admin\User;

class InvoicesController {
    private $mysqli;
    private $operation;
    private $business;
    private $operation_details;
    private $product;
    private $user;
    private $require;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
        $this->operation = new Quote();
        $this->business = new Client();
        $this->operation_details = new QuoteDetail();
        $this->product = new Product();
        $this->user = new User();
        $this->require = new Invoice();
    }
        
    //     global $mysqli; // Asegurar que $mysqli est谩 disponible
    //     $this->mysqli = $mysqli; // Asignarlo a la clase
        
    //     $this->operation = new Quote();
    //     $this->business = new Client();
    //     $this->operation_details = new QuoteDetail();
    //     $this->product = new Product();
    //     $this->user = new User();
    //     $this->require= new Invoice();
        
    // }

    public function index() {
        $requires = $this->require->getAll();
        unset($_SESSION['message']);

         // Obtener el valor de búsqueda
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
         // Configuración de paginación
        $operationsPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;  // Usamos 'currentPage' en lugar de 'page'
        $offset = ($currentPage - 1) * $operationsPerPage;
        
        $operations = $this->operation->getAllPaginated($operationsPerPage, $offset); 
        
        $totalOperations = $this->operation->getTotalOperations($search); 
        $totalPages = ceil($totalOperations / $operationsPerPage);
        
        
        foreach ($requires as $key => $require) {
            $operation  = $this->operation->getById($require['quote_id']);
            if ($operation) {
                $business = $this->business->getById($operation['business_id']);
                $requires[$key]['business_name'] = $business ? $business['name'] : 'Business no encontrado';
                $requires[$key]['business_email'] = $business ? $business['email'] : 'N/A';
            
                $user = $this->user->getById($require['user_id']);
                $requires[$key]['user_alias'] = $user ? $user['username'] : 'User no encontrado';
                
                $amount = $this->operation_details->getAmount($require['quote_id']);
              
                $requires[$key]['amount'] = $amount['total'];
            }
          
        }
 
      include "../../app/views/admin/sales/invoices/index.php"; //Aca va tu views.
    }


    public function create() {
        
            $operation = [    
                'quote_id' => $_GET['operation'],
                'user_id' => $_SESSION['user_id'],
                'created_at' => date('Y-m-d H:i:s'),     // Fecha actual para created_at
                'updated_at' => date('Y-m-d H:i:s'),     // Fecha actual para updated_at
            ];
       
            $require = $this->require->create($operation);
    
            if ($require) {
                header("Location: index.php?page=invoices&action=index");
                exit;
            } else {
                echo "Error al crear la orden: " . $this->db->error;
            }

    }

public function search()
{
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    $month = isset($_POST['month']) ? $_POST['month'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';

    // Configuración de paginación
    $operationsPerPage = 10;
    $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
    $offset = ($currentPage - 1) * $operationsPerPage;

    // Consultas de la base de datos para traer las cotizaciones (invoices)
    $operations = $this->operation->getAllPaginated($operationsPerPage, $offset);
    $totalOperations = $this->operation->getTotalQuotes($search); // Cambiar por el total de facturas, no de cotizaciones
    $totalPages = ceil($totalOperations / $operationsPerPage);

    // Búsqueda de facturas
        if (!empty($search)) {
            // Búsqueda por ID
            $requires = $this->require->searchById($search);
        } elseif (!empty($month) && !empty($year)) {
            // Búsqueda por mes y año
            $requires = $this->require->searchByDate($month, $year, $operationsPerPage, $offset);
  
} elseif (empty($month) && !empty($year)) {
    // Buscar solo por año completo
    $startDate = $year . '-01-01';
    $endDate = $year . '-12-31';

    $totalOperations = $this->require->getTotalByDateRange($startDate, $endDate);
    $requires = $this->require->searchByDateRange($startDate, $endDate, $operationsPerPage, $offset);


        } else {
            echo "Ingrese un ID o seleccione un mes y año.";
            header("Location: index.php?page=invoices&action=index");
            exit;
        }
    

    // Procesar los "requires" estas son las invoices
    if (!empty($requires)) {
        foreach ($requires as &$require) {
            $operation = $this->operation->getById($require['quote_id'] ?? null);
            $business = $this->business->getById($operation['client_id'] ?? null);
       
            
            $require['business_name'] = $business ? $business['name'] : 'Business no found';
            $require['business_email'] = $business ? $business['email'] : 'N/A';
    
            // Verifica que 'quote_id' exista antes de acceder a él
             $amount = $this->operation_details->getAmount($require['quote_id']);
           // $amount = isset($require['quote_id']) ? $this->operation_details->getAmount($require['quote_id']) : ['total' => 0];
            $require['amount'] = $amount['total'];
        }
        unset($require);
        
        // Aquí puedes incluir la vista de los "requires" si es necesario
        include '../../app/views/admin/sales/invoices/index.php'; // O la vista correspondiente
    }
}



    public function show($id)
    {
        if ($id <= 0) {
            echo "ID no valido: {$id}";
            return;
        }
    
        $order = $this->require->getById($id);
        
        $operation = $this->operation->getById($order['quote_id']);
        // var_dump($operation);
        
        if ($operation) {
            
            $business = $this->business->getById($operation['business_id']);
            
            $operation['business_name'] = $business ? $business['name'] : 'Business no encontrado';
            $operation['business_email'] = $business ? $business['email'] : 'N/A';
           // $operation['client_address'] = $business ? $business['address'] : 'N/A';
           
            $operation_details = $this->operation_details->getByItemId('quote_id', $operation['id']);
    
            foreach ($operation_details as &$operation_detail) {
            
                $product = $this->product->getByItemId('id', $operation_detail['product_id']);
            
                $operation_detail['product_name'] = $product ? $product[0]['name'] : 'Product not found';
                $operation_detail['unit'] = $product ? $product[0]['unit'] : 'N/A';
            }
             include '../../app/views/admin/sales/invoices/show.php';
        } else {
            echo "No se encontr贸 la cotizaci贸n con el ID {$id}.";
        }
    }
    
    public function mail($id) {
        if ($id <= 0) {
        echo "ID no valido: {$id}";
        return;
        }
        
        // igual a show
        $order = $this->require->getById($id);
        
        $operation = $this->operation->getById($order['request_id']);
        // var_dump($operation);
        
        if ($operation) {
            
            $business = $this->business->getById($operation['business_id']);
            
            $operation['business_name'] = $business ? $business['name'] : 'Business no encontrado';
            $operation['business_email'] = $business ? $business['email'] : 'N/A';
           // $operation['client_address'] = $business ? $business['address'] : 'N/A';
           
            $operation_details = $this->operation_detail->getByItemId('operation_id', $operation['id']);
    
            foreach ($operation_details as &$operation_detail) {
            
                $product = $this->product->getByItemId('id', $operation_detail['product_id']);
            
                $operation_detail['product_name'] = $product ? $product[0]['name'] : 'Product not found';
                $operation_detail['unit'] = $product ? $product[0]['unit'] : 'N/A';
            }
            
            //
            //El config
            $mailerId = "Ikusa LLC";
            $mailerTo = 'cadenapanamericana@gmail.com';//$operation['business_email'];
        
            $mailerFrom = 'contact@ikusa.net'; // Place the E-mail of Domain
            $mailerToToo = 'ikusa.ads@gmail.com';
            $mailerReplay = $mailerFrom;
            $subject = "Order {$operation['id']}";
            
            //Credenciales para PHPMailer 
            require_once '../../app/config/email.php';
            
            //Body:
             include '../../app/views/admin/sales/invoices/mail.php';
             
             //Send
             include '../../app/libraries/inc_phpmailer.php';
             
             //Volver
             //include '../../app/views/Email/notice.php';
              header("Location: index.php?page=order&action=show&id={$order['id']}");
             } else {
            echo "No se encontr贸 la cotizaci贸n con el ID {$id}.";
        }
        //hasta aca
      
    }
         
    public function delete($id) {
        if ($this->require->delete($id)) {
            header("Location: index.php?page=order&action=index");
            exit;
        }
    }
    
}
