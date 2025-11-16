<?php
// index.php
ob_start();
session_start();
require 'app/config/settings.php';
require 'app/config/assets.php';
require 'app/config/connection.php';

// Determina la pÃ¡gina a cargar
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$lang = "EN";

// SEO
require 'app/config/seo.php';

if ($page == "admin") { }
else {
    include 'app/views/partials/header.php';
?>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title><?= htmlspecialchars($seoData['title']) ?></title>
        <meta name="description" content="<?= htmlspecialchars($seoData['description']) ?>">
        <meta name="keywords" content="<?= htmlspecialchars($seoData['keywords']) ?>">

        <!-- Open Graph -->
        <meta property="og:title" content="<?= htmlspecialchars($seoData['og_title']) ?>">
        <meta property="og:description" content="<?= htmlspecialchars($seoData['og_description']) ?>">
        <meta property="og:image" content="https://ikusa.net/<?= $seoData['og_image']; ?>">

        <!-- Twitter Card -->
        <meta name="twitter:title" content="<?= htmlspecialchars($seoData['twitter_title']) ?>">
        <meta name="twitter:description" content="<?= htmlspecialchars($seoData['twitter_description']) ?>">
        <meta name="twitter:image" content="<?= htmlspecialchars($seoData['twitter_image']) ?>">
        
        <link rel="stylesheet" href="/assets/css/styles.css">
        
    
        <!--<script src="https://analytics.ahrefs.com/analytics.js" data-key="DIcP1rZNgssMJwOSBznW1Q" async></script>-->
    </head>
    <?php
}
// use App\Controllers\HeaderController;

// Carga la vista correspondiente o muestra un 404 si la pÃ¡gina no existe
switch ($page) {
    
    case 'home':
        require_once "app/controllers/PortfolioController.php";
        $controller = new PortfolioController();
    
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    
        switch($action) {
            case 'index':
            default:
                $controller->index($lang);
                break;
            case 'show':
                $category = isset($_GET['category']) ? $_GET['category'] : '';
                $controller->show($lang, $category);
                break;
        }
        break;

    case 'portfolio':
        require_once "app/controllers/PortfolioController.php";
        $controller = new PortfolioController();
    
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    
        switch($action) {
            case 'index':
            default:
                $controller->index($lang);
                break;
            case 'show':
                $category = isset($_GET['category']) ? $_GET['category'] : '';
                $controller->show($lang, $category);
                break;
        }
        break;
        case 'card':
            require_once "app/controllers/CardController.php";
            $controller = new CardController();
        
            $action = isset($_GET['action']) ? $_GET['action'] : 'index';
            $product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
        
            switch($action) {
                case 'show':
                    $controller->show($lang, $product_id);
                    break;
                case 'index':
                default:
                    $controller->index($lang);
                    break;
            }
            break;        

    case 'cart':
        require_once "app/controllers/CartController.php";
        // $controller = new CartController();
        $controller = new \App\Controllers\CartController();

        $action = isset($_GET['action']) ? $_GET['action'] : 'add';
    
        switch($action) {
            case 'add':
            default:
                $controller->add();
                break;
            case 'show':
                $controller->show();
                break;  
            case 'remove':
                $id = $_GET['id'];
                $controller->remove();
                break;   
                     
        }
        break;

        // case 'stripe':
        //     $action = $_GET['action'] ?? 'checkout';
        //     switch ($action) {
        //         case 'checkout':
        //             require_once "api/Stripe/checkout.php";
        //             break;
        //         case 'success':
        //             require_once "app/controllers/PaymentController.php";
        //             break;
        //         // default:
        //         //     include 'app/views/404.php';
        //         //     break;
        //     }
        //     break;
            case 'stripe':
                $action = $_GET['action'] ?? 'checkout';
                switch ($action) {
                    case 'checkout':
                        require_once "api/Stripe/checkout.php";
                        break;
                    case 'success':
                        require_once "app/controllers/PaymentController.php";
            
                        $paymentController = new PaymentController($mysqli);
                        $paymentController->processPayment(); // ⚡ Llamada al flujo de pago
                        break;
                }
                break;
            
            case 'notification':
                require 'api/Stripe/success.php';
                break;

    case 'about':
        include 'app/views/about.php';
        break;    
        
    case 'contact':
        define('ACCESS_GRANTED', true);
        require_once "app/controllers/ContactController.php";
        $contactController = new ContactController();
        $contactController->mail();
    break;

    case 'email':
        header('Content-Type: application/json');
    
        // Cargar configuración PHPMailer
        require_once __DIR__ . '/libraries/PHPMailer/config.php';
      
    
        // Leer POST JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $mailerTo = $input['email'] ?? null;
        $amount   = $input['amount'] ?? 0;
    
        if (!$mailerTo) {
            echo json_encode(['status' => 'error', 'message' => 'No se proporcionó email.']);
            exit;
        }
    
        $subject = 'Confirmación de pagoxx';
        $body    = "<p>Hola,</p>
                    <p>Hemos recibido tu pago de €" . number_format($amount, 2, ',', '.') . ".</p>
                    <p>Gracias por tu compra.</p>";
    
        require_once __DIR__ . '/libraries/PHPMailer/inc_phpmailer.php';
        exit;
    
    
    

    case 'RFQ':
        require_once "app/controllers/RFQ/RFQsController.php";
        $controller = new RFQsController();
        
        if ($_GET['action'] === 'create') {
            $controller->create(); // Llama al mÃ©todo que maneja GET y POST
        }
        
        if ($_GET['action'] === 'notice') {
            include 'app/views/RFQ/notice.php';// Llama al mÃ©todo que maneja GET y POST
        }
        break;  
        
    case 'admin':
        // require_once "app/views/auth/login.php";
        require_once "app/controllers/admin/AuthsController.php";
        $controller = new AuthsController();
     
        if ($_GET['action'] === 'auth') {
          $controller->auth();
        }
        break;
    
    // Blogs  
    
    //Legals
        
    case 'legal-notice':
        include 'app/views/legals/legal-notice.php';
        break;  
        
    case 'cookies-policy':
        include 'app/views/legals/cookies-policy.php';
        break;
        
    case 'data-security-policy':
        include 'app/views/legals/data-security-policy.php';
        break;
        
    //Delivery    YA SE USAN EN CART COMO
    // case 'usps0':
    //     include 'app/views/USPS/create.php';
    //     break; 
        
    // case 'usps':
    //     include 'app/views/USPS/show.php';
    //     break;
 
    default:
        include 'app/views/404.php';
        break;
}


//    include 'app/views/partials/footer.php';

// ðŸ§  AÃ±adimos el rastreador global del carrito
include 'app/views/components/CartTracker.php';

ob_end_flush();
?>
