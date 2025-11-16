<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_status() === PHP_SESSION_NONE && session_start();

require_once __DIR__ . '/../models/Model.php';
require_once __DIR__ . '/../models/Order.php';

use App\Models\Order;

class PaymentController
{
    private Order $order;
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
        $this->order = new Order($db);
    }

    /**
     * Procesar pago exitoso de Stripe
     */
    public function processPayment()
    {
        // Incluir lógica de Stripe success
       // require_once __DIR__ . '/../../api/Stripe/success.php';

        // Obtener datos de sesión
        $mailerTo       = $_SESSION['email'] ?? null;
        $amount         = $_SESSION['amount'] ?? 0;
        $cartItems      = $_SESSION['cart_dropdown'] ?? [];
        $couponCode     = $_SESSION['coupon_code'] ?? '';
        $discountAmount = $_SESSION['discount_amount'] ?? 0;
        $shippingPrice  = $_SESSION['shipping_price'] ?? 0;

        $username = $_SESSION['username'] ?? '';
        $address  = $_SESSION['address'] ?? '';
        $zipcode  = $_SESSION['zipcode'] ?? '';
        $city     = $_SESSION['city'] ?? '';
        $state    = $_SESSION['state'] ?? '';

        if (!$mailerTo) {
            echo json_encode(['status' => 'error', 'message' => 'No se proporcionó email.']);
            exit;
        }

        if (empty($cartItems)) {
            echo json_encode(['status' => 'error', 'message' => 'El carrito está vacío.']);
            exit;
        }

        // Normalizar carrito: asegurar que todos los items tengan variant_id y product_id
        $normalizedCart = [];
        foreach ($cartItems as $item) {
            $variantId = $item['variant_id'] ?? ($item['variants'][0]['id'] ?? null);
            $productId = $item['product_id'] ?? ($item['variants'][0]['product_id'] ?? null);

            if (!$variantId || !$productId) {
                throw new \Exception("Producto '{$item['name']}' no tiene variante válida.");
            }

            $normalizedCart[] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'name'       => $item['name'] ?? 'Producto',
                'price'      => $item['price'] ?? 0,
                'qty'        => $item['qty'] ?? 1,
                'attributes' => $item['attributes'] ?? ($item['variants'][0]['attributes'] ?? [])
            ];
        }

        // Preparar datos para la orden
        $orderData = [
            'user_id'         => $_SESSION['user_id'] ?? null,
            'total'           => max(0, $amount - $discountAmount + $shippingPrice),
            'shipping_address'=> $address,
            'shipping_zip'    => $zipcode,
            'shipping_cost'   => $shippingPrice,
            'discount_code'   => $couponCode,
            'discount_amount' => $discountAmount
        ];

        try {
            // Guardar la orden y detalles
            $orderId = $this->order->createOrder($orderData, $normalizedCart);
            $_SESSION['order_id'] = $orderId;

            // Incluir vista que genera el correo
            $cartItems = $normalizedCart; // para la vista
            include __DIR__ . '/../views/components/OrderNotification.php'; 
            // $subject y $body quedan listos desde la vista

            // Limpiar carrito de sesión
            unset($_SESSION['cart_dropdown'], $_SESSION['cart_message'], $_SESSION['item_message']);
            $cartCount = 0;

            // Enviar correo usando PHPMailer configurado en tus includes
            require_once __DIR__ . '/../../libraries/PHPMailer/config.php';
            require_once __DIR__ . '/../../libraries/PHPMailer/inc_phpmailer.php';
            
            unset($_SESSION['cart']);

          
            //echo json_encode(['status' => 'success', 'order_id' => $orderId]);
            
            header("Location: index.php?page=notification");
            exit;

        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
