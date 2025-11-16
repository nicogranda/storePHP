<?php
// Mostrar errores solo en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) session_start();
// Incluye tus credenciales USPS
include 'config.php'; // asegúrate que tenga $client_id y $client_secret

header('Content-Type: application/json');

try {
     $rawData = file_get_contents('php://input');
     $data = json_decode($rawData, true);

    //  $destination = isset($data['zip']) ? trim($data['zip']) : null;

    //  if (!$destination || !preg_match('/^\d{5}$/', $destination)) {
    //      throw new InvalidArgumentException('ZIP Code inválido o ausente.');
    //  }
      
    $destination = $data['zip'];
    

    $summary = $_SESSION['cart_summary'] ?? [
        'total_weight' => 0,
        'max_width' => 0,
        'max_height' => 0,
        'total_length' => 0
    ];
    
    $weightTotal = $summary['total_weight'];
    $width       = $summary['max_width'];
    $height      = $summary['max_height'];
    $length      = $summary['total_length'];

    // $weightTotal = 5;
    // $length = 2.5;
    // $width = 7.5;
    // $height = 1.2;
  
    $origin = '77494'; // código de origen

    // Aquí la magia: pedir token USPS
    $token = getAccessToken();
    if (!$token) {
        throw new RuntimeException('No se pudo obtener el token de USPS');
    }
    
    // Obtener tarifas
    $rateResponse = getRate($token, $origin, $destination, $weightTotal, $length, $width, $height);
    $price = $rateResponse['rates'][0]['price'] ?? $rateResponse['totalBasePrice'] ?? 0;
    
    // Obtener fecha estimada de entrega
    $deliveryResponse = getDeliveryEstimate($token, $origin, $destination);
    $delivery_time = $deliveryResponse['deliveryTimeEstimates'][0]['estimatedDeliveryDate'] ?? 'Delivery date not available.';
    
    // Obtener ciudad y estado por ZIP
    // $originCityState = getCityStateByZip($origin);
    // $destinationCityState = getCityStateByZip($destination);
    // Obtener ciudad y estado desde USPS API
    $originCityState = getCityStateUsps($origin, $token);
    $destinationCityState = getCityStateUsps($destination, $token);

    
    // Armar JSON final
    $response = [
        'price' => $price,
        'delivery_time' => $delivery_time,
        'weight' => $weightTotal,
        'origin' => [
            'zipCode' => $origin,
            'city' => $originCityState['city'] ?? null,
            'state' => $originCityState['state'] ?? null
        ],
        'destination' => [
            'zipCode' => $destination,
            'city' => $destinationCityState['city'] ?? null,
            'state' => $destinationCityState['state'] ?? null
        ]
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

// Funciones

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
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        error_log("Error cURL getAccessToken: " . curl_error($curl));
        curl_close($curl);
        return null;
    }

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Decodificar JSON devuelto por USPS
    $json = json_decode($response, true);

    // Verificar código HTTP y estructura de respuesta
    if ($httpCode !== 200 || !isset($json['access_token'])) {
        error_log("Error USPS token HTTP $httpCode: " . $response);
        return null;
    }

    return $json['access_token'];
}

function getRate(
    string $token, 
    string $origin, 
    string $destination, 
    float $weightTotal,
    float $length,
    float $width,
    float $height
): array {
    global $account_number;

    $payload = [
        'originZIPCode' => $origin,
        'destinationZIPCode' => $destination,
        'weight' => $weightTotal,
        'length' => $length,
        'width' => $width,
        'height' => $height,
        'mailClass' => 'USPS_GROUND_ADVANTAGE',
        'processingCategory' => 'MACHINABLE',
        'rateIndicator' => 'SP',
        'destinationEntryFacilityType' => 'NONE',
        'priceType' => 'CONTRACT',
        'mailingDate' => date('Y-m-d'),
        'accountType' => 'EPS',
        'accountNumber' => $account_number,
        'hasNonstandardCharacteristics' => false
    ];

    $curl = curl_init('https://apis.usps.com/prices/v3/base-rates/search');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ],
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        $err = curl_error($curl);
        curl_close($curl);
        throw new RuntimeException("Error al obtener tarifa: $err");
    }

    curl_close($curl);

    // ✅ Decodificar el JSON para usarlo en el carrito
    $data = json_decode($response, true);

    return $data; // <-- ahora $rateResponse['rates'][0]['price'] funcionará
}

function getDeliveryEstimate(string $token, string $origin, string $destination): ?string {
    $payload = [
        'originZIPCode' => $origin,
        'destinationZIPCode' => $destination,
        'mailClass' => 'USPS_GROUND_ADVANTAGE',
        'mailingDate' => date('Y-m-d'),
    ];

    $curl = curl_init('https://apis.usps.com/delivery-time-estimates');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ],
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        curl_close($curl);
        return null;
    }

    curl_close($curl);

    $json = json_decode($response, true);
    return $json;
    // return $json['estimatedDeliveryDate'] ?? null;
}

// function getCityStateByZip($zip) {
//     $url = "https://api.zippopotam.us/us/" . urlencode($zip);

//     $curl = curl_init($url);
//     curl_setopt_array($curl, [
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_HTTPGET => true,
//         CURLOPT_HTTPHEADER => [
//             'Accept: application/json'
//         ],
//     ]);

//     $response = curl_exec($curl);

//     if ($response === false) {
//         error_log("cURL error: " . curl_error($curl));
//         curl_close($curl);
//         return ['city' => null, 'state' => null];
//     }

//     curl_close($curl);

//     $data = json_decode($response, true);
//     if (isset($data['places'][0]['place name']) && isset($data['places'][0]['state abbreviation'])) {
//         return [
//             'city' => $data['places'][0]['place name'],
//             'state' => $data['places'][0]['state abbreviation']
//         ];
//     } else {
//         error_log("ZIP no encontrado o estructura inesperada: " . print_r($data, true));
//         return ['city' => null, 'state' => null];
//     }
// }

function getCityStateUsps(string $zip, string $token): array {
    $url = 'https://apis.usps.com/addresses/v3/city-state?ZIPCode=' . urlencode($zip);

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPGET => true,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Authorization: Bearer ' . $token,
        ],
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        error_log("❌ cURL error (getCityStateUsps): " . curl_error($curl));
        curl_close($curl);
        return ['city' => null, 'state' => null];
    }

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // 📜 Log temporal para verificar respuesta real
    error_log("🔎 USPS city-state [$zip] HTTP $httpCode: $response");

    if ($httpCode !== 200) {
        return ['city' => null, 'state' => null];
    }

    $json = json_decode($response, true);

    // USPS debería responder algo como:
    // { "city": "Katy", "state": "TX", "ZIPCode": "77494" }
    if (isset($json['city']) && isset($json['state'])) {
        return [
            'city' => $json['city'],
            'state' => strtoupper($json['state'])
        ];
    }

    // En algunos entornos USPS la respuesta puede venir anidada:
    if (isset($json['address']['city']) && isset($json['address']['state'])) {
        return [
            'city' => $json['address']['city'],
            'state' => strtoupper($json['address']['state'])
        ];
    }

    error_log("⚠️ Estructura inesperada USPS [$zip]: " . print_r($json, true));
    return ['city' => null, 'state' => null];
}



