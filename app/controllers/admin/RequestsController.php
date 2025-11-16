<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../app/libraries/admin/Model.php';
require_once '../../app/models/admin/Request.php';
require_once '../../app/models/admin/RequestDetail.php';
require_once '../../app/models/admin/Supply.php';
require_once '../../app/models/admin/Provider.php';
require_once '../../app/models/admin/User.php';
require_once '../../app/models/admin/Order.php';
require_once '../../app/models/admin/OperationData.php'; // Agregar esta línea

use App\Models\Admin\RFQ;
use App\Models\Admin\RFQDetail;
use App\Models\Admin\Supply;
use App\Models\Admin\Provider;
use App\Models\Admin\User;
use App\Models\Admin\Order;

use App\Models\Admin\OperationData; 

class RFQsController {
    private $supply;
    private $RFQ;
    private $RFQDetail;
    private $provider;
    private $user;
    
    public function __construct()
    {
        global $mysqli; // Asegurar que $mysqli está disponible
        $this->mysqli = $mysqli; // Asignarlo a la clase
    
        $this->operation = new RFQ();
        $this->operation_details = new RFQDetail();
        $this->product = new Supply();
        $this->business = new Provider();
        $this->operationData = new OperationData($this->operation, $this->business, $this->operation_details, $this->product);
        $this->require = new Order();
    }

    public function index()
    {
        // Obtener el valor de búsqueda
        $search = isset($_GET['search']) ? $_GET['search'] : '';
    
        // Configuración de paginación
        $operationsPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;  // Usamos 'currentPage' en lugar de 'page'
        $offset = ($currentPage - 1) * $operationsPerPage;
        
        $operations = $this->operation->getAllPaginated($operationsPerPage, $offset); 
        
        $totalOperations = $this->operation->getTotalOperations($search); 
        $totalPages = ceil($totalOperations / $operationsPerPage);
        
    
        foreach ($operations as &$RFQ) {
            $business = $this->business->getById($RFQ['business_id']);
            $RFQ['business_name'] = $business ? $business['name'] : 'Business no encontrado';
            $RFQ['business_email'] = $business ? $business['email'] : 'N/A';
        }
        include '../../app/views/admin/purchases/requests/index.php';
    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
            $business = [    
                'alias' => $_POST['alias'],
                'name' => $_POST['business_name'],
                'email' => $_POST['email'],
               // 'business_type_id' => $_POST['business_type_id'],
                'phone' =>"",
                'country' => "",
                'created_at' => date('Y-m-d H:i:s'),     // Fecha actual para created_at
                'updated_at' => date('Y-m-d H:i:s'),
                ];
                
            $bid = [
                //'provider_id' => '1', //$_POST['provider_id'],  // Añadido manualmente
                'created_at' => date('Y-m-d H:i:s'),     // Fecha actual para created_at
                'updated_at' => date('Y-m-d H:i:s'),     // Fecha actual para updated_at
            ];
            
            // Llama al modelo para consultar el business
            $businessModel = $this->business->getByAlias($business['alias']);
           
            if ($businessModel) {
                $bid['business_id'] = $businessModel['id'];
            } else {
                $businessModel = $this->business->create($business);
                $bid['business_id']  = $this->mysqli->insert_id;
            }
           
           
            // Llama al modelo para insertar los datos
            $RFQ = $this->operation->create($bid);
            
            if (!$RFQ) {
                die('Error al crear RFQ');
            }
            
            //Record inserting
            $RFQid = $this->mysqli->insert_id;
            
            $items = $_POST['item'];
            
            foreach ($items as $itemIndex => $item) { 
                $items[$itemIndex] = array_merge(['request_id' => $RFQid], $item);
            }
            
           // var_dump($items); // Verifica los datos antes de enviarlos
            
            $this->operation_details->store($items);
               
    
         header('Location: index.php?page=requests&action=index');
        } else {
            $products = $this->product->getAll();
            $RFQs = $this->operation->getAll();
            include '../../app/views/admin/purchases/requests/create.php';
        }
    }
    
    public function search()
    {
        $search = isset($_POST['search']) ? trim($_POST['search']) : '';
        $operationsPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $offset = ($currentPage - 1) * $operationsPerPage;
       
        // Calcular el número total de páginas
        $totalPages = 1;
        
        if ($search !== '') {
            $operations = $this->operation->getById($search); 
               
    
            if (!empty($operations) && isset($operations['id'])) {
                $operations = [$operations]; // Convertirlo en array de arrays si es un solo resultado
            }
    
            if (!is_array($operations) || empty($operations)) {
                echo "No se encontró la operación con ese ID.";
                return;
            }
    
            foreach ($operations as &$operation) {
                $business = $this->business->getById($operation['provider_id'] ?? null);
                $operation['business_name'] = $business ? $business['name'] : 'Business no found';
                $operation['business_email'] = $business ? $business['email'] : 'N/A';
            }
    
            include '../../app/views/admin/purchases/requests/index.php';
        } else {
            echo "No se ha ingresado ningún ID para buscar.";
            header("Location: index.php?page=requests&action=index");
        }
    }

    public function show($id)
    {
        //Get and Calculate
        $operation = $this->operationData->getOperationData($id,'request_id');
      
        $requireModel = $this->require->getByItemId('request_id', $operation['id']); 
            
        if (!empty($requireModel) && is_array($requireModel)) {
                $firstItem = $requireModel[0]; // Tomar el primer resultado
                $operation['required'] = $firstItem['id'] ?? null;
            } else {
                $operation['required'] = null;
            }
        
        //Show
        include '../../app/views/admin/purchases/requests/show.php';
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
           
            $operationsDetailsModel = new RFQDetail(); 

            // Llamamos al método updateOperation para actualizar ambas tablas
            $updateSuccess =$operationsDetailsModel->updateOperationDetailsByOperationId($operationId, $operationDetailData);
            
           // header("Location: index.php?page=quotes&action=show&id=" . $operationId);
            if ($updateSuccess) {
                // Si la actualización es exitosa, redirigimos o mostramos un mensaje de éxito
                //header("Location: /path_to_success_page");
               header("Location: index.php?page=requests&action=show&id=" . $operationId);

            //    exit;
            } else {
                // Si algo falla, mostramos un mensaje de error
                echo "Error al actualizar la cotización";
            }
        }

public function print($id)
    {
        //Area de data, debo crear una class que haga esto y poder reusarla, aca en show y en mail.
        $operation = $this->operation->getById($id);
        
        $vat = 0; $total = 0;
        if ($operation) {
            
            $business = $this->business->getById($operation['provider_id']);
            
            $operation['business_alias'] = $business ? $business['alias'] : 'Cliente no encontrado';
            $operation['business_name'] = $business ? $business['name'] : 'Cliente no encontrado';
            $operation['business_email'] = $business ? $business['email'] : 'N/A';
           // $operation['business_address'] = $business ? $business['address'] : 'N/A';
            
            $operation_details = $this->operation_details->getByItemId('request_id', $operation['id']);
        
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
        include '../../app/views/admin/purchases/requests/print.php';
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
           
            $operation_details = $this->operation_details->getByItemId('request_id', $operation['id']);
    
            foreach ($operation_details as &$operation_detail) {
            
                $product = $this->product->getByItemId('id', $operation_detail['product_id']);
            
                $operation_detail['product_name'] = $product ? $product[0]['name'] : 'Product not found';
                $operation_detail['unit'] = $product ? $product[0]['unit'] : 'N/A';
            }

            $vat = 0; $total = 0;
            
            //El config
            $mailerId = "Traffic";
            $mailerTo = $operation['business_email'];
        
            $mailerFrom = 'contact@ikusa.net'; // Place the E-mail of Domain
            $mailerToToo = 'ikusa.ads@gmail.com';
            $mailerReplay = $mailerFrom;
            $subject = "Solicitud de Precios {$operation['id']}";
            
            //Credenciales para PHPMailer 
            require_once '../../app/config/email.php';
            
            //Body:
             include '../../app/views/admin/purchases/requests/mail.php';
            
             //Send
             include '../../app/libraries/inc_phpmailer.php';
             
             //Volver
             //include '../../app/views/Email/notice.php';
              header("Location: index.php?page=requests&action=show&id={$id}");
             } else {
            echo "No se encontró la cotización con el ID {$id}.";
        }
        //hasta aca
      
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
            $quote_details = $this->operation_details->deleteItemsByOperationId('request_id', $quote1['id']);
    
            // Eliminar la cotización principal
            $quote = $this->operation->delete($id);
    
            $this->mysqli->commit(); // Confirmar transacción
    
            // Redirigir después de una eliminación exitosa
            header("Location: index.php?page=requests&action=index");
            exit;
            
        } catch (Exception $e) {
            $this->mysqli->rollback(); // Revertir cambios en caso de error
            throw new Exception("Error al eliminar la RFQ: " . $e->getMessage());
        }
    }
}

