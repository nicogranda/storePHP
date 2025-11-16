<?php
// Mostrar errores solo en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

// Incluye tus credenciales USPS
include 'config.php'; // Debe contener $consumer_key y $consumer_secret

try {
    // 1️⃣ Generar token OAuth automáticamente
    $token = getAccessToken();
    if (!$token) {
        throw new RuntimeException('No se pudo obtener el token OAuth de USPS');
    }

    // 2️⃣ Crear Payment Authorization (requiere roles PAYER + LABEL_OWNER)
    $paymentAuth = createPaymentAuthorization($token);
    if (!isset($paymentAuth['paymentAuthorizationToken'])) {
        throw new RuntimeException('No se pudo generar el paymentAuthorizationToken');
    }

    // 3️⃣ Respuesta combinada (token OAuth + autorización de pago)
    $response = [
        'success' => true,
        'OAuth_Token' => $token,
        'PaymentAuthorizationToken' => $paymentAuth['paymentAuthorizationToken'],
        'Roles' => $paymentAuth['roles'] ?? []
    ];

    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

//////////////////// FUNCIONES ////////////////////

function getAccessToken(): ?string {
    global $consumer_key, $consumer_secret;

    $curl = curl_init('https://apis.usps.com/oauth2/v3/token');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $consumer_key,
            'client_secret' => $consumer_secret
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        error_log('Error cURL OAuth: ' . curl_error($curl));
        curl_close($curl);
        return null;
    }

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $data = json_decode($response, true);
    if ($status !== 200 || !isset($data['access_token'])) {
        error_log("OAuth error ($status): " . $response);
        return null;
    }

    return $data['access_token'];
}

function createPaymentAuthorization(string $token): array {
    // Reemplazá con tus datos reales USPS (EPS, CRID, MID, etc.)
    $roles = [
        [
            "roleName" => "PAYER",
            "accountType" => "EPS",
            "accountNumber" => "1000249960", // EPS activo
            "CRID" => "50155474"
        ],
        [
            "roleName" => "LABEL_OWNER",
            "CRID" => "50155474",
            "MID" => "903800759",
            "manifestMID" => "903800759"
        ]
    ];

    $payload = ["roles" => $roles];

    $curl = curl_init('https://apis.usps.com/payments/v3/payment-authorization');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        throw new RuntimeException('Error al crear autorización: ' . curl_error($curl));
    }

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $data = json_decode($response, true);
    if ($status !== 200) {
        throw new RuntimeException("Error USPS ($status): " . json_encode($data));
    }

    return $data;
}
