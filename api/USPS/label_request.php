<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

include 'config.php'; // Debe definir: $consumer_key, $consumer_secret, $account_number

try {
    // Datos de ejemplo (reemplaza por tu entrada real si hace falta)
    $data = [
        "toAddress" => [
            "firstName" => "Nick",
            "lastName" => "Fury",
            "streetAddress" => "2700 S Jefferson Ave",
            "secondaryAddress" => "STE 150",
            "city" => "St. Louis",
            "state" => "MO",
            "ZIPCode" => "63104"
        ],
        "fromAddress" => [
            "firstName" => "Sam",
            "lastName" => "Wilson",
            "streetAddress" => "45 L St SW",
            "city" => "Washington",
            "state" => "DC",
            "ZIPCode" => "20024"
        ],
        "packageDescription" => [
            "mailClass" => "USPS_GROUND_ADVANTAGE",
            "rateIndicator" => "SP",
            "weightUOM" => "lb",
            "weight" => 0.5,
            "dimensionsUOM" => "in",
            "length" => 9,
            "height" => 6,
            "width" => 0.25,
            "processingCategory" => "NONSTANDARD",
            "mailingDate" => date('Y-m-d'),
            "destinationEntryFacilityType" => "NONE"
        ]
    ];

    // --- Paso 1: Obtener OAuth token dinámicamente ---
    $oauthToken = getOAuthToken();
    if (!$oauthToken) throw new Exception("No se pudo obtener el token OAuth de USPS.");

    // --- Paso 2: Obtener Payment Authorization token ---
    $paymentToken = getPaymentAuthorizationToken($oauthToken);
    if (!$paymentToken) throw new Exception("No se pudo obtener el token de autorización de pago.");

    // --- Paso 3: Crear la etiqueta ---
    $labelResponse = createLabel($oauthToken, $paymentToken, $data);

    echo json_encode([
        "success" => true,
        "trackingNumber" => $labelResponse['trackingNumber'] ?? null,
        "labelData" => $labelResponse['label'] ?? null,
        "rawResponse" => $labelResponse
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}


// ------------------ FUNCIONES ------------------

function getOAuthToken(): ?string {
    global $consumer_key, $consumer_secret;

    if (empty($consumer_key) || empty($consumer_secret)) {
        throw new Exception("Faltan consumer_key o consumer_secret en config.php");
    }

    $curl = curl_init('https://apis.usps.com/oauth2/v3/token');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $consumer_key,
            'client_secret' => $consumer_secret
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_TIMEOUT => 15,
        // CURLOPT_SSL_VERIFYPEER => true, // descomenta en producción si tu entorno lo permite
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        $err = curl_error($curl);
        curl_close($curl);
        throw new Exception("cURL error al pedir OAuth token: $err");
    }

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $data = json_decode($response, true);
    if ($httpCode !== 200) {
        $msg = isset($data['error']) ? json_encode($data) : $response;
        throw new Exception("Error OAuth ($httpCode): $msg");
    }

    if (!isset($data['access_token'])) {
        throw new Exception("Respuesta OAuth inválida: " . json_encode($data));
    }

    return $data['access_token'];
}


function getPaymentAuthorizationToken(string $oauthToken): ?string {
    // JSON de roles según el que te enviaron
    $roles = [
        [
            "roleName" => "PAYER",
            "CRID" => "50155474",
            "MID" => "903800759",
            "manifestMID" => "903800759",
            "tipo de cuenta" => "EPS",
            "Número de cuenta" => "1000249960"
        ],
        [
            "roleName" => "LABEL_OWNER",
            "CRID" => "50155474",
            "MID" => "903800759",
            "manifestMID" => "903800759",
            "tipo de cuenta" => "EPS",
            "Número de cuenta" => "1000249960"
        ]
    ];

    $payload = json_encode(['roles' => $roles], JSON_UNESCAPED_UNICODE);

    $curl = curl_init('https://apis.usps.com/payments/v3/payment-authorization');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $oauthToken
        ],
        CURLOPT_TIMEOUT => 15,
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        $err = curl_error($curl);
        curl_close($curl);
        throw new Exception("cURL error en payment-authorization: $err");
    }

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $data = json_decode($response, true);

    if ($httpCode !== 200) {
        throw new Exception("USPS Payment Auth error ($httpCode): " . $response);
    }

    if (!isset($data['paymentAuthorizationToken'])) {
        throw new Exception("No se encontró paymentAuthorizationToken en la respuesta: " . json_encode($data));
    }

    return $data['paymentAuthorizationToken'];
}


function createLabel(string $oauthToken, string $paymentToken, array $data): array {
    global $account_number;

    if (empty($account_number)) {
        throw new Exception("Falta \$account_number en config.php");
    }

    $payload = [
        "accountNumber" => $account_number,
        "fromAddress" => $data['fromAddress'],
        "toAddress" => $data['toAddress'],
        "packageDescription" => $data['packageDescription'],
        "labelSpecification" => [
            "labelFormat" => "PDF",
            "labelSize" => "4X6",
            "printDensity" => "300"
        ]
    ];

    $curl = curl_init('https://apis.usps.com/labels/v3/label');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $oauthToken,
            'X-Payment-Authorization-Token: ' . $paymentToken
        ],
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        $err = curl_error($curl);
        curl_close($curl);
        throw new Exception("cURL error al crear etiqueta: $err");
    }

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $dataResp = json_decode($response, true);

    if ($httpCode >= 400) {
        throw new Exception("USPS Label HTTP error $httpCode: " . $response);
    }

    // Devuelve lo que retorne la API (tracking, label, etc.)
    return is_array($dataResp) ? $dataResp : ['raw' => $response];
}
