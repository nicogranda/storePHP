<?php
// Mostrar errores solo en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluye tus credenciales USPS
include 'config.php'; // asegúrate que tenga $client_id y $client_secret

header('Content-Type: application/json');

try {
     $rawData = file_get_contents('php://input');
     $data = json_decode($rawData, true);

    // $cart = $data['cart'] ?? [];
     $destination = "30092";//$data['zip'];

    // if (!$destination || !preg_match('/^\d{4,10}$/', $destination)) {
    //     throw new InvalidArgumentException('Código postal inválido');
    // }

    $weightTotal = 1;
    // foreach ($cart as $item) {
    //     $weightTotal += isset($item['weight']) ? floatval($item['weight']) : 1;
    // }

    // if ($weightTotal <= 0) {
    //     throw new InvalidArgumentException('El carrito está vacío o el peso es inválido');
    // }

    $origin = '77494'; // código de origen


    // Aquí la magia: pedir token USPS
    $token = getAccessToken();
    if (!$token) {
        throw new RuntimeException('No se pudo obtener el token de USPS');
    }

    $token = getAccessToken();

exit;

    $shipping = getUspsShippingInfo($origin, $destination, $weightTotal, $token);

    echo json_encode([
        'price' => $shipping['rate']['price'] ?? 0,
        'delivery_time' => $shipping['estimatedDeliveryDate'] ?? 'Fecha no disponible',
        'weight' => $weightTotal,
        'origin' => [
            'zipCode' => $origin,
            'city' => $shipping['originCityState']['city'] ?? null,
            'state' => $shipping['originCityState']['state'] ?? null
        ],
        'destination' => [
            'zipCode' => $destination,
            'city' => $shipping['destinationCityState']['city'] ?? null,
            'state' => $shipping['destinationCityState']['state'] ?? null
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

// Funciones

function getAccessToken(): ?string {
    global $client_id, $client_secret;

    $curl = curl_init('https://apis.usps.com/oauth2/v3/token');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        error_log("Error cURL getAccessToken: " . curl_error($curl));
        curl_close($curl);
        return null;
    }
    curl_close($curl);
    
    $data = json_decode($response, true);
    
    if (!isset($data['access_token'])) {
        error_log("No se encontró access_token en respuesta: $response");
        return null;
    }
    
    $token = $data['access_token'];
    //error_log("USPS Token recibido: $token");
    
    return $token;
    }

function getUspsShippingInfo(string $origin, string $destination,string $weightTotal, string $token): array {
    $rate = getRate($token, $origin, $destination, $weightTotal);
    $delivery = getDeliveryEstimate($token, $origin, $destination);
    $originCityState = getCityStateByZip($origin);
    $destinationCityState = getCityStateByZip($destination);
    // $originCityState = getCityStateUsps($origin, $token);
    // $destinationCityState = getCityStateUsps($destination, $token);

    return [
        'rate' => $rate,
        'estimatedDeliveryDate' => $delivery,
        'originCityState' => $originCityState,
        'destinationCityState' => $destinationCityState
    ];
}

function getRate(string $token, string $origin, string $destination, float $weightTotal): array {
    $payload = [
        'originZIPCode' => $origin,
        'destinationZIPCode' => $destination,
        'weight' => $weightTotal,
        'length' => 0.10,
        'width' => 0.10,
        'height' => 0.05,
        'mailClass' => 'USPS_GROUND_ADVANTAGE',
        'processingCategory' => 'MACHINABLE',
        'rateIndicator' => 'SP',
        'destinationEntryFacilityType' => 'NONE',
        'priceType' => 'COMMERCIAL',
        'mailingDate' => date('Y-m-d'),
        'accountType' => 'EPS',
        'accountNumber' => '903798905'
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

    // Log completo para debug
    //error_log("Respuesta API USPS tarifas: " . $response);

    curl_close($curl);

    $json = json_decode($response, true);
    if (empty($json['rates'][0])) {
        throw new RuntimeException('Tarifa no disponible en la respuesta');
    }

    return $json['rates'][0];
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
    return $json['estimatedDeliveryDate'] ?? null;
}

function getCityStateByZip($zip) {
    $url = "https://api.zippopotam.us/us/" . urlencode($zip);

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPGET => true,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json'
        ],
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        error_log("cURL error: " . curl_error($curl));
        curl_close($curl);
        return ['city' => null, 'state' => null];
    }

    curl_close($curl);

    $data = json_decode($response, true);
    if (isset($data['places'][0]['place name']) && isset($data['places'][0]['state abbreviation'])) {
        return [
            'city' => $data['places'][0]['place name'],
            'state' => $data['places'][0]['state abbreviation']
        ];
    } else {
        error_log("ZIP no encontrado o estructura inesperada: " . print_r($data, true));
        return ['city' => null, 'state' => null];
    }
}

// No lo uso, lo dejo por si lo arreglan
function getCityStateUsps(string $zip, string $token): array {
    $url = 'https://apis.usps.com/addresses/v3/?ZIPCode=' . urlencode($zip);

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPGET => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ],
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        error_log("Error cURL getCityStateUsps: " . curl_error($curl));
        curl_close($curl);
        return ['city' => null, 'state' => null];
    }
    curl_close($curl);

    // error_log("Respuesta USPS dirección ($zip): $response");

    $json = json_decode($response, true);

    if (isset($json['address']['city']) && isset($json['address']['state'])) {
        return [
            'city' => $json['address']['city'],
            'state' => $json['address']['state']
        ];
    }

    error_log("Estructura inesperada para dirección USPS ZIP $zip: " . print_r($json, true));
    return ['city' => null, 'state' => null];
}

