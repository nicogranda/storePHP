<?php
require 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../images/favicon.png" type="image/ico" />
    <link rel="stylesheet" href="css/style.css" type="text/css" charset="utf-8" />
    <!-- <link rel="stylesheet" href="css/x.css" type="text/css" charset="utf-8" /> -->
    <link rel="stylesheet" href="css/nav.css" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="js/search_brand.js"></script>
</head>
<body>
    
<?php

require '../app/config/settings.php';
require '../app/config/connection.php';


// Determina la página a cargar
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include '../app/views/admin/partials/nav.php';
    
// Carga la vista correspondiente o muestra un 404 si la página no existe
switch ($page) {
    
    case 'home':
        include '../app/views/admin/home.php';
        break;
        
    case 'users':
        include '../app/views/users.php';
        break;
        
    case 'logout':
        //include 'app/views/users/logout.php';
       include '../app/views/admin/auth/logout.php';
        break;    
        
    case 'order':
        require_once "../app/controllers/admin/OrdersController.php";
        $controller = new OrdersController($mysqli);
    
        if ($_GET['action'] === 'create') {
            $controller->create(); // Llama al método que maneja GET y POST
        }
        
        if ($_GET['action'] === 'index') {
            $controller->index(); // Llama al método que maneja GET y POST
        }

        if ($_GET['action'] === 'search') {
            $controller->search(); // Pasar el ID al método
        }
        
        if ($_GET['action'] === 'mail') {
          $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
           $controller->mail($id); // Ver el POST[mail] abajo 6/2/2025
        }
        
        if ($_GET['action'] === 'show') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
            $controller->show($id); // Pasar el ID al método
        }
        
        if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
            $controller->delete($_GET['id']); // Pasa el id de la orden
        }

        break;

    case 'categories':
        require_once "../app/controllers/admin/CategoriesController.php";
        $controller = new CategoriesController();
    
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    
        switch($action) {
            case 'create':
                $controller->create(); // GET y POST
                break;
            case 'upload':
                $controller->upload(); // solo para Dropzone
                break;
            case 'delete':
                $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                if ($id > 0) {
                    $controller->delete($id);
                } else {
                    echo "ID inválido";
                }
                break;
    
            case 'index':
            default:
                $controller->index();
                break;
            }
            break;
        
    case 'products':
        require_once "../app/controllers/admin/ProductsController.php";
        $controller = new ProductsController();

        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        switch($action) {
            case 'create':
                $controller->create(); // GET y POST
                break;
            case 'upload':
                $controller->upload(); // solo para Dropzone
                break;
            case 'delete':
                $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                if ($id > 0) {
                    $controller->delete($id);
                } else {
                    echo "ID inválido";
                }
                break;

            case 'index':
            default:
                $controller->index();
                break;
        }
        break;

    case 'clients':
        require_once "../../app/controllers/admin/ClientsController.php";
        $controller = new ClientsController($mysqli);
        
        if ($_GET['action'] === 'index') {
            $controller->index(); // Llama al método que maneja GET y POST
        }
        break;
        
        if ($_GET['action'] === 'search') {
            $controller->search(); // Llama al método que maneja GET y POST
        }
        break;

    case 'quotes':
        require_once "../../app/controllers/admin/QuotesController.php";
        $controller = new QuotesController($mysqli);
        
        if ($_GET['action'] === 'index') {
            $controller->index(); // Llama al método que maneja GET y POST
        }
        
        if ($_GET['action'] === 'create') {
           $controller->create(); // Ver el POST[mail] abajo 6/2/2025
        }
      
        if ($_GET['action'] === 'search') {
            $controller->search(); // Llama al método que maneja GET y POST
        }
      
        if ($_GET['action'] === 'show') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
            $controller->show($id); // Pasar el ID al método
        }
        
        if ($_GET['action'] === 'update') {
            $id = isset($_POST['operation_id']) ? (int) $_POST['operation_id'] : 0;
            $data = $_POST;
            $controller->update($id, $data);
        }

        if ($_GET['action'] === 'delete') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
            $controller->delete($id); // Pasar el ID al método
        }
        
        if ($_GET['action'] === 'print') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
            header("Location: /admin/fpdf/quote.php?id=".$id);
        }
        
        if ($_GET['action'] === 'mail') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            $controller->mail($id);
        }

    break;

    case 'quote_details':
       require_once "../../app/controllers/admin/QuotesDetailsController.php";
       $controller = new QuotesDetailsController($mysqli);
        
        //
        if ($_GET['action'] === 'create') {
           $id = isset($_POST['operation_id']) ? (int) $_POST['operation_id'] : 0;
           $data = $_POST;
           $controller->create($data); // Llama al método que maneja GET y POST
        }
        //
        if ($_GET['action'] === 'delete') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
           $controller->delete($id); // Pasar el ID al método
        }
    break;
    
    case 'requests':
        require_once "../../app/controllers/admin/RequestsController.php";
        $controller = new RFQsController();
        
        if ($_GET['action'] === 'index') {
          $controller->index(); // Llama al método que maneja GET y POST
              
        }
      
        if ($_GET['action'] === 'create') {
            $controller->create(); // Llama al método que maneja GET y POST
        }
        
        
        if ($_GET['action'] === 'search') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
            $controller->search(); // Llama al método que maneja GET y POST
        }
      
        if ($_GET['action'] === 'show') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
            $controller->show($id); // Pasar el ID al método
        }

        if ($_GET['action'] === 'update') {
            $id = isset($_POST['operation_id']) ? (int) $_POST['operation_id'] : 0;
            $data = $_POST;
            $controller->update($id, $data);
        }
        
        if ($_GET['action'] === 'print') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
            $controller->print($id); // Pasar el ID al método
        }
        
        if ($_GET['action'] === 'mail') {
          $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
           $controller->mail($id); // Ver el POST[mail] abajo 6/2/2025
        }
        
        if ($_GET['action'] === 'delete') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
            $controller->delete($id); // Pasar el ID al método
        }
        
    break;

    case 'supplies':
        require_once "../../app/controllers/admin/SuppliesController.php";
        $controller = new SuppliesController();

        if ($_GET['action'] === 'index') {
           $controller->index(); // Llama al método que maneja GET y POST
        }
        
        if ($_GET['action'] === 'search') {
           $controller->search(); // Llama al método que maneja GET y POST
        }
        
        if ($_GET['action'] === 'read') {
           $controller->read(); // Llama al método que maneja GET y POST
        }


    break;
       
    case 'deliveries':
        include 'app/views/sales/deliveries.php';
        break;

    case 'invoices':
        require_once "../../app/controllers/admin/InvoicesController.php";
        $controller = new InvoicesController($mysqli);
    
        if ($_GET['action'] === 'create') {
            $controller->create(); // Llama al método que maneja GET y POST
        }
        
        if ($_GET['action'] === 'index') {
            $controller->index(); // Llama al método que maneja GET y POST
        }

        if ($_GET['action'] === 'search') {
            $controller->search(); // Pasar el ID al método
        }
        
        if ($_GET['action'] === 'mail') {
          $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
           $controller->mail($id); // Ver el POST[mail] abajo 6/2/2025
        }
        
        if ($_GET['action'] === 'show') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
            $controller->show($id); // Pasar el ID al método
        }
        
        if ($_GET['action'] === 'print') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Asegurarse de que sea un número entero
            header("Location: /admin/fpdf/invoice.php?id=".$id);
        }
        if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
            $controller->delete($_GET['id']); // Pasa el id de la orden
        }

        break;    

    case 'collections':
        include 'app/views/collections.php';
        break;

    case 'E-mail':
        require_once "../../app/controllers/admin/EmailsController.php";
        $controller = new MailController();
        

        if ($_GET['action'] === 'create') {
            $controller->create(); // Llama al método que maneja GET y POST
        }
        break;  
              
    case 'carnets':
        require_once "../../app/controllers/admin/QuotesController.php";
        $controller = new QuotesController($mysqli);
        
        if ($_GET['action'] === 'print') {
            //header("Location: /admin/TCPDF/examples/example_001.php");
            header("Location: /admin/TCPDF/examples/carnets.php");
        }              
        break;  
           
     case 'irs':
        require_once "../../app/controllers/admin/IrsController.php";
        $controller = new IrsController();
        if ($_GET['action'] === '5472') {
            //  require_once "fpdf/form5472.php";
            $controller->form5472();
        }
          if ($_GET['action'] === '1120') {
          include '../../app/views/admin/irs/form1120.php';
        }
        break;
        
    default:
        include 'app/views/404.php';
        break;
}



//include 'app/views/partials/footer.php';
?>
</body>
</html>
