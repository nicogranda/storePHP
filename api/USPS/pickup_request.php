<?php
// Mostrar errores (solo en desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
ob_start();

include 'config.php'; // usa $consumer_key y $consumer_secret

try {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    // Datos por defecto para pruebas locales
    if (empty($data)) {
        $data = [
            'pickupDate' => date('Y-m-d', strtotime('+2 day')),
            'firstName' => 'Yusmira',
            'lastName' => 'Granda',
            'firm' => 'IKUSA LLC',
            'streetAddress' => '28123 Canyon Wren Dr.',
            'secondaryAddress' => '',
            'city' => 'Katy',
            'state' => 'TX',
            'ZIPCode' => '77494',
            'ZIPPlus4' => '', // opcional
            'urbanization' => '',
            'email' => 'ikusa.creative@gmail.com',
            'packageType' => 'USPS_GROUND_ADVANTAGE',
            'packageCount' => 1,
            'weight' => 0.40,
            'packageLocation' => 'FRONT_DOOR',
            'specialInstructions' => 'Ring the bell twice',
            'dogPresent' => false
        ];
    }

    // Extraer variables para mayor claridad
    $pickupDate = $data['pickupDate'];
    $firstName = $data['firstName'];
    $lastName = $data['lastName'];
    $firm = $data['firm'] ?? '';
    $streetAddress = $data['streetAddress'];
    $secondaryAddress = $data['secondaryAddress'] ?? '';
    $city = $data['city'];
    $state = $data['state'];
    $zipCode = $data['ZIPCode'];
    $zipPlus4 = $data['ZIPPlus4'] ?? '';
    $urbanization = $data['urbanization'] ?? '';
    $email = $data['email'] ?? '';
    $packageType = $data['packageType'];
    $packageCount = (int)$data['packageCount'];
    $estimatedWeight = (float)$data['weight'];
    $packageLocation = $data['packageLocation'];
    $specialInstructions = $data['specialInstructions'] ?? '';
    $dogPresent = (bool)$data['dogPresent'];

    // Obtener token OAuth2 de USPS
    $token = getAccessToken();
    if (!$token) {
        throw new Exception("No se pudo obtener token USPS");
    }

    // Construir address con validación de ZIPPlus4
    $address = [
        'streetAddress' => $streetAddress,
        'secondaryAddress' => $secondaryAddress,
        'city' => $city,
        'state' => $state,
        'ZIPCode' => $zipCode,
        'urbanization' => $urbanization
    ];

    // Solo incluir ZIPPlus4 si tiene 4 dígitos válidos
    if (!empty($zipPlus4) && preg_match('/^\d{4}$/', $zipPlus4)) {
        $address['ZIPPlus4'] = $zipPlus4;
    }

    // Construir payload de pickup
    $pickupData = [
        'pickupDate' => $pickupDate,
        'pickupAddress' => [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'firm' => $firm,
            'address' => $address,
            'contact' => [
                [
                    'email' => $email
                ]
            ]
        ],
        'packages' => [
            [
                'packageType' => $packageType,
                'packageCount' => $packageCount
            ]
        ],
        'estimatedWeight' => $estimatedWeight,
        'pickupLocation' => [
            'packageLocation' => $packageLocation,
            'specialInstructions' => $specialInstructions,
            'dogPresent' => $dogPresent
        ]
    ];

    // Enviar request a USPS Carrier Pickup
    $ch = curl_init('https://apis.usps.com/pickup/v3/carrier-pickup');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($pickupData),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        throw new Exception('Error en CURL: ' . curl_error($ch));
    }

    curl_close($ch);

    if ($httpCode >= 400) {
        throw new Exception("Error USPS HTTP $httpCode: $response");
    }

    $decoded = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Respuesta USPS no es JSON válido: $response");
    }

    echo json_encode([
        'success' => true,
        'pickup_confirmed' => $decoded
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Obtiene el token OAuth2 para USPS usando tus credenciales actuales
 */
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
    curl_close($curl);

    if (!$response) return null;

    $data = json_decode($response, true);
    if (empty($data['access_token'])) {
        error_log("Token USPS no recibido: " . $response);
        return null;
    }

    error_log("✅ Token USPS OK");
    return $data['access_token'];
}
