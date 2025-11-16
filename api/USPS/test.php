<?php
include 'config.php'; // aquí están $consumer_key, $consumer_secret, $account_number

$origin = '77494';
$destination = '30092';
$weightTotal = 1;


// include 'rate.php';  // ruta correcta a tu rate.php

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
$token = getAccessToken(); // tu función para obtener token

$priceTypes = ['COMMERCIAL', 'RETAIL'];

foreach ($priceTypes as $type) {
    try {
        $rate = getRate($token, $origin, $destination, $weightTotal, $type);
        echo json_encode([
            'success' => true,
            'priceType' => $type,
            'rate' => $rate
        ], JSON_PRETTY_PRINT);
        exit;
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'priceType' => $type,
            'error' => $e->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}

// Función getRate modificada para recibir priceType
function getRate(string $token, string $origin, string $destination, float $weightTotal, string $priceType): array {
    global $account_number;

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
        'priceType' => $priceType,
        'mailingDate' => date('Y-m-d'),
        'accountType' => 'EPS',
        'accountNumber' => $account_number
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
    curl_close($curl);

    $json = json_decode($response, true);
    if (empty($json['rates'][0])) {
        throw new RuntimeException('Tarifa no disponible en la respuesta');
    }

    return $json['rates'][0];
}
