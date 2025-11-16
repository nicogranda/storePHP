<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../app/libraries/admin/Model.php';
require_once '../../app/models/admin/Quote.php';
require_once '../../app/models/admin/Client.php';
require_once '../../app/models/admin/QuoteDetail.php';
require_once '../../app/models/admin/Product.php';
require_once '../../app/models/admin/OperationData.php'; // Agregar esta línea
require_once '../../app/models/admin/Invoice.php';

use App\Models\Admin\OperationData; // Si usas namespaces, agrégalo aquí

use App\Models\Admin\Quote;
use App\Models\Admin\Client;
use App\Models\Admin\QuoteDetail;
use App\Models\Admin\Product;
use App\Models\Admin\Invoice;

class QuotesController
{
    // Declaramos todas las propiedades que se usan
    private $mysqli;
    private $operation;
    private $business;
    private $operation_details;
    private $product;
    private $operationData;
    private $require;

    public function __construct()
    {
        global $mysqli;
        $this->mysqli = $mysqli;

        // Instancias de modelos
        $this->operation = new Quote();
        $this->business = new Client();
        $this->operation_details = new QuoteDetail();
        $this->product = new Product();
        $this->operationData = new OperationData(
            $this->operation,
            $this->business,
            $this->operation_details,
            $this->product
        );
        $this->require = new Invoice();
    }
    
    public function index()
    {
        // Obtener el valor de búsqueda
        $search = isset($_GET['search']) ? $_GET['search'] : '';
    
        // Verificar si existe $_SESSION['message'] Mensaje cuando se envia un email
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message']; // Guardar el mensaje temporalmente
            unset($_SESSION['message']); // Vaciar la sesión después de leerla
        } else {
            $message = ''; // Si no existe, dejarlo vacío
        }
        // Configuración de paginación
        $operationsPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;  // Usamos 'currentPage' en lugar de 'page'
        $offset = ($currentPage - 1) * $operationsPerPage;
    
        // Consultas de la base de datos para traer las cotizaciones
        $operations = $this->operation->getAllPaginated($operationsPerPage, $offset);  // Pasar 'search' aquí
        
        $totalOperations = $this->operation->getTotal($search);  // Pasar 'search' también
        
        // Calcular el número total de páginas
        $totalPages = ceil($totalOperations / $operationsPerPage);

        // Para cada cotización, obtener el cliente correspondiente
        foreach ($operations as &$operation) {
            $clientModel = $this->business->getById($operation['business_id']);
           
            $operation['client_name'] = $clientModel ? $clientModel['name'] : 'Cliente no encontrado';
            $operation['client_email'] = $clientModel ? $clientModel['email'] : 'N/A';
            
            $amount = $this->operation_details->getAmount($operation['id']);
            $operation['amount'] = $amount['total'];
            
            $requireModel = $this->require->getByItemId('quote_id', $operation['id']); 
            //var_dump($requireModel);
            
            if (!empty($requireModel) && is_array($requireModel)) {
                $firstItem = $requireModel[0]; // Tomar el primer resultado
                $operation['required'] = $firstItem['id'] ?? null;
            } else {
                $operation['required'] = null;
            }

        }
       
        
        unset($operation);
        //var_dump($operations);
        
        // Pasar las cotizaciones con los datos del cliente a la vista
        include '../../app/views/admin/sales/quotes/index.php';
    }
    
    public function search()
    {
        $search = isset($_POST['search']) ? trim($_POST['search']) : '';
        $month = isset($_POST['month']) ? $_POST['month'] : '';
        $year = isset($_POST['year']) ? $_POST['year'] : '';
    
    
        // Configuración de paginación
        $operationsPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;  // Usamos 'currentPage' en lugar de 'page'
        $offset = ($currentPage - 1) * $operationsPerPage;

        // Consultas de la base de datos para traer las cotizaciones
        $operations = $this->operation->getAllPaginated($operationsPerPage, $offset);  // Pasar 'search' aquí
        
        $totalOperations = $this->operation->getTotalQuotes($search);  // Pasar 'search' también
        
        // Calcular el número total de páginas
        $totalPages = ceil($totalOperations / $operationsPerPage);
        
        $quotesPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $offset = ($currentPage - 1) * $quotesPerPage;
        
        if (!empty($search)) {
            $operation = $this->operation->searchById($search);
            $operations = $operation ? [$operation] : [];


            //$operations = $this->operation->searchById($search);
        } elseif (!empty($month) && !empty($year)) {
            // Búsqueda por mes y año
            $operations = $this->operation->searchByDate($month, $year, $quotesPerPage, $offset);
            
        } elseif (empty($month) && !empty($year)) {
   
        // Buscar solo por año completo
        $startDate = $year . '-01-01';
        $endDate = $year . '-12-31';
        $totalOperations = $this->operation->getTotalByDateRange($startDate, $endDate);
        $operations = $this->operation->searchByDateRange($startDate, $endDate, $quotesPerPage, $offset);

        } else {
            echo "Ingrese un ID o seleccione un mes y año.";
            header("Location: index.php?page=quotes&action=index");
            exit;
        }
        
        if ($operations) {
            foreach ($operations as &$operation) {
                $client = $this->business->getById($operation['business_id']);

                $operation['client_name'] = $client ? $client['name'] : 'Cliente no encontrado';
                $operation['client_email'] = $client ? $client['email'] : 'N/A';
                
                $amount = $this->operation_details->getAmount($operation['id']);
                $operation['amount'] = $amount['total'];
                            
                $requireModel = $this->require->getByItemId('quote_id', $operation['id']); 
            //var_dump($requireModel);
            
                if (!empty($requireModel) && is_array($requireModel)) {
                    $firstItem = $requireModel[0]; // Tomar el primer resultado
                    $operation['required'] = $firstItem['id'] ?? null;
                } else {
                    $operation['required'] = null;
                }
            }
            unset($operation);
        
            include '../../app/views/admin/sales/quotes/index.php';
        
        } else {
            echo "No se encontraron cotizaciones para los criterios ingresados.";
        }
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
            $business = [    
                'alias' => $_POST['alias'],
                'name' => $_POST['business_name'],
                'email' => $_POST['email'],
                'business_type' =>"",
                'phone' =>"",
                'country' => "",
                'created_at' => date('Y-m-d H:i:s'),     // Fecha actual para created_at
                'updated_at' => date('Y-m-d H:i:s'),
                ];
                
            $bid = [

                'created_at' => date('Y-m-d H:i:s'),     // Fecha actual para created_at
                'updated_at' => date('Y-m-d H:i:s'),     // Fecha actual para updated_at
                 'approval' => 0,
            ];
            
            // Llama al modelo para consultar el business
            $businessModel = $this->business->getByAlias($business['alias']);
           
            if ($businessModel) {
                $bid['business_id'] = $businessModel['id'];
            } else {
                $businesModel = $this->business->create($business);
                $bid['business_id']  = $this->mysqli->insert_id;
            }
 
          
            // Llama al modelo para insertar los datos
            $operation = $this->operation->create($bid);
            
             if (!$operation) {
                die('Error al crear Quote');
            }
            
            //Record inserting
            $operationId = $this->mysqli->insert_id;
            
            $items = $_POST['item'];
            
            //$OperationDetailModel = new QuoteDetail();
            //var_dump($OperationDetailModel);
            //$OperationDetailModel->store($OperationDetailModel);

            foreach ($items as $itemIndex => $item) { 
                $items[$itemIndex] = array_merge(['quote_id' => $operationId], $item);
            }
            
           // var_dump($items); // Verifica los datos antes de enviarlos
            
            $this->operation_details->store($items);
    
    
         header('Location: index.php?page=quotes&action=index');
        } else {
            $products = $this->product->getAll();
            //var_dump($products);
            include '../../app/views/admin/sales/quotes/create.php';
        }
    }    
    
    public function show($id)
    {

        //Get
          $operation = $this->operationData->getOperationData($id,'quote_id');
     
        $requireModel = $this->require->getByItemId('quote_id', $operation['id']); 
            //var_dump($requireModel);
            
        if (!empty($requireModel) && is_array($requireModel)) {
                $firstItem = $requireModel[0]; // Tomar el primer resultado
                $operation['required'] = $firstItem['id'] ?? null;
            } else {
                $operation['required'] = null;
            }
        
        //Show
        include '../../app/views/admin/sales/quotes/show.php';
        

    }
    
     public function update($operationId, $data)
    {
         //var_dump($data);
            $alias = $data['operation']['\'alias\'']; // Mostrará: Cadena Panamericana
           
            $business = $this->business->getByAlias($alias);
            $businessId = $business['id'];
            
            $operationModel = $this->operation; // Instancia del modelo Quote (tabla 'quotes')
            $OperationUpdated = $operationModel->updateOperationByBusinessId($operationId, $businessId);
         
            // Se espera que $data sea un array con los datos de las cotizaciones y sus detalles
            $operationDetailData = $data['operation_detail']; // Detalles para la tabla operations_details
            //var_dump($operationDetailData);
            // Crear instancias de los modelos
           
            $operationsDetailsModel = new QuoteDetail(); 

            // Llamamos al método updateOperation para actualizar ambas tablas
            $updateSuccess =$operationsDetailsModel->updateOperationDetailsByOperationId($operationId, $operationDetailData);
            
           // header("Location: index.php?page=quotes&action=show&id=" . $operationId);
            if ($updateSuccess) {
                // Si la actualización es exitosa, redirigimos o mostramos un mensaje de éxito
                //header("Location: /path_to_success_page");
               header("Location: index.php?page=quotes&action=show&id=" . $operationId);

            //    exit;
            } else {
                // Si algo falla, mostramos un mensaje de error
                echo "Error al actualizar la cotización";
            }
        }
    
     public function mail($id) {
        if ($id <= 0) {
        echo "ID no valido: {$id}";
        return;
        }
        
        $operation = $this->operation->getById($id);
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

            $vat = 0; $total = 0;
            
            //El config
            $mailerId = "Ikusa";
            $mailerTo = $operation['business_email'];
        
            $mailerFrom = 'contact@ikusa.net'; // Place the E-mail of Domain
            $mailerToToo = 'ikusa.ads@gmail.com';
            $mailerReplay = $mailerFrom;
            $subject = "Quote {$operation['id']}";
            
            //Credenciales para PHPMailer 
            require_once '../../app/config/email.php';
            
            //Body:
             include '../../app/views/admin/sales/quotes/mail.php';
            
             //Send
             include '../../app/libraries/inc_phpmailer.php';
             
             //Volver
              header("Location: index.php?page=quotes&action=show&id={$id}");
             } else {
            echo "No se encontró la cotización con el ID {$id}.";
        }
        //hasta aca
      
    }
    
    public function print($id)
    {
        //Area de data, debo crear una class que haga esto y poder reusarla, aca en show y en mail.
        $operation = $this->operation->getById($id);
        
        $vat = 0; $total = 0;
        if ($operation) {
            
            $business = $this->business->getById($operation['client_id']);
            
            $operation['business_alias'] = $business ? $business['alias'] : 'Cliente no encontrado';
            $operation['business_name'] = $business ? $business['name'] : 'Cliente no encontrado';
            $operation['business_email'] = $business ? $business['email'] : 'N/A';
            $operation['business_address'] = $business ? $business['address'] : 'N/A';
            
            $operation_details = $this->operation_details->getByItemId('quote_id', $operation['id']);
        
            foreach ($operation_details as &$operation_detail) {
                $product = $this->product->getByItemId('id', $operation_detail['product_id']);
                
                    if (!empty($product) && is_array($product)) {
                        // Si getByItemId devuelve un array con un solo producto, accedemos al primer elemento
                        $product = reset($product);  
                    }
                    
                
                //var_dump($product); // Asegúrate de que aquí solo ves un producto a la vez
                 
                $operation_detail['product_name'] = $product['name'] ?? 'Product not found';
                $operation_detail['unit'] = $product['unit'] ?? 'N/A';
                
                  // Cálculo de vat_item y balance
                $vat_item = $operation_detail['quantity'] * $operation_detail['unit_value'] * (1 - $operation_detail['discount'] / 100) * ($operation_detail['vat_rate'] / 100);
                $balance = $operation_detail['quantity'] * $operation_detail['unit_value'] * (1 - $operation_detail['discount'] / 100) + $vat_item;
                $vat = $vat + $vat_item;
                $total = $total + $balance +$vat;
            }
        }
        include '../../app/views/admin/sales/quotes/print.php';
    }
    
    public function delete($id)
    {
        // Validar que $id sea numérico
        if (!is_numeric($id)) {
            throw new Exception("ID inválido.");
        }
    
        $this->mysqli->begin_transaction(); // Iniciar transacción
    
        try {
            // Obtener la cotización
            $quote1 = $this->operation->getById($id);
            if (!$quote1) {
                throw new Exception("Cotización no encontrada.");
            }
    
            // Eliminar los detalles de la cotización
            $quote_details = $this->operation_details->deleteItemsByOperationId('quote_id', $quote1['id']);
    
            // Eliminar la cotización principal
            $quote = $this->operation->delete($id);
    
            $this->mysqli->commit(); // Confirmar transacción
    
            // Redirigir después de una eliminación exitosa
            header("Location: index.php?page=quotes&action=index");
            exit;
            
        } catch (Exception $e) {
            $this->mysqli->rollback(); // Revertir cambios en caso de error
            throw new Exception("Error al eliminar la cotización: " . $e->getMessage());
        }
    }

 
}