<?php
// Mostrar errores solo en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Ajusta la ruta si es necesario

header('Content-Type: application/json');

try {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    if (empty($data['carrier'])) {
        throw new InvalidArgumentException('Carrier es obligatorio');
    }
    if (empty($data['tracking_number'])) {
        throw new InvalidArgumentException('Tracking number es obligatorio');
    }
    if (empty($data['origin_zip']) || empty($data['destination_zip']) || empty($data['weight'])) {
        throw new InvalidArgumentException('Faltan datos para la cotización: origin_zip, destination_zip, weight');
    }

    // Obtener información de envío con USPS
    $token = getAccessToken();
    if (!$token) {
        throw new RuntimeException('No se pudo obtener el token de USPS');
    }

    $shippingInfo = getUspsShippingInfo(
        $data['origin_zip'],
        $data['destination_zip'],
        floatval($data['weight']),
        $token
    );

    // Guardar o actualizar tracking info (ejemplo básico)
    $saved = saveTrackingInfo($data['carrier'], $data['tracking_number'], $shippingInfo);

    echo json_encode([
        'success' => true,
        'shipping' => $shippingInfo,
        'tracking_saved' => $saved,
        'message' => 'Información de envío y tracking procesados correctamente'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

// --- Funciones ---

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

    return $data['access_token'];
}

function getUspsShippingInfo(string $origin, string $destination, float $weight, string $token): array {
    $rate = getRate($token, $origin, $destination, $weight);
    $delivery = getDeliveryEstimate($token, $origin, $destination);
    $originCityState = getCityStateUsps($origin, $token);
    $destinationCityState = getCityStateUsps($destination, $token);

    return [
        'rate' => $rate,
        'estimatedDeliveryDate' => $delivery,
        'originCityState' => $originCityState,
        'destinationCityState' => $destinationCityState
    ];
}

function getRate(string $token, string $origin, string $destination, float $weight): array {
    $payload = [
        'originZIPCode' => $origin,
        'destinationZIPCode' => $destination,
        'weight' => $weight,
        'length' => 10,
        'width' => 10,
        'height' => 5,
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

function getCityStateUsps(string $zip, string $token): array {
    $url = 'https://apis.usps.com/addresses/v3/' . $zip;
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

    $json = json_decode($response, true);

    if (isset($json['address']['city']) && isset($json['address']['state'])) {
        return [
            'city' => $json['address']['city'],
            'state' => $json['address']['state']
        ];
    }

    return ['city' => null, 'state' => null];
}

/**
 * Guarda la info del carrier y tracking (stub, adapta a tu BD o sistema)
 * 
 * @param string $carrier Nombre del transportista
 * @param string $tracking Tracking number / etiqueta
 * @param array $shippingInfo Datos de envío calculados (tarifa, fechas, etc)
 * @return bool true si se guardó, false si no
 */
function saveTrackingInfo(string $carrier, string $tracking, array $shippingInfo): bool {
    // Aquí deberías conectar y guardar en tu base de datos o sistema
    // Ejemplo básico: escribir en archivo para demo

    $data = [
        'carrier' => $carrier,
        'tracking_number' => $tracking,
        'shipping_info' => $shippingInfo,
        'timestamp' => date('Y-m-d H:i:s'),
    ];

    $filename = __DIR__ . '/tracking_log.json';

    $log = [];
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        $log = json_decode($content, true) ?? [];
    }

    $log[] = $data;

    return file_put_contents($filename, json_encode($log, JSON_PRETTY_PRINT)) !== false;
}
