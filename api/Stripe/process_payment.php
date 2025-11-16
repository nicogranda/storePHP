<?php
// api/Stripe/payment_process.php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Stripe PHP SDK

$config = require __DIR__ . '/config.php';
\Stripe\Stripe::setApiKey($config['stripe']['secret_key']);

// Leer JSON enviado por fetch
$input = json_decode(file_get_contents('php://input'), true);
// file_put_contents(__DIR__ . '/debug_log.txt', "[".date('Y-m-d H:i:s')."] POST: ".print_r($input,true)."\n", FILE_APPEND);

$amount = $input['amount'] ?? null;
$paymentMethod = $input['payment_method'] ?? null;
$email = $input['email'] ?? null;

if (!$amount || !$paymentMethod) {
    echo json_encode(['status' => 'error','message' => 'Faltan parámetros']);
    exit;
}

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'usd',
        'payment_method' => $paymentMethod,
        'confirm' => true,
        'receipt_email' => $email,
        // Solo métodos que no requieren redirección
        'automatic_payment_methods' => [
            'enabled' => true,
            'allow_redirects' => 'never'
        ],
    ]);
    
    echo json_encode([
        'status' => 'success',
        'payment_intent_id' => $paymentIntent->id,
        'clientSecret' => $paymentIntent->client_secret,
        'message' => 'PaymentIntent creado'
    ]);
} catch (\Stripe\Exception\CardException $e) {
    // Errores de tarjeta
    echo json_encode(['status'=>'error','message'=>$e->getError()->message]);
    exit;
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Errores de API
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
    exit;
} catch (\Exception $e) {
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
    exit;
}
