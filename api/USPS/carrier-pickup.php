<?php
// 👆 Sin espacios antes de esto

// Mostrar errores (solo para desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// El contenido que se envía es JSON
header('Content-Type: application/json');

// Asegúrate de que no haya output previo
ob_start();

//JSON 
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

include 'config.php';

try {
    $token = getAccessToken();
    if (!$token) {
        throw new Exception("No se pudo obtener token USPS");
    }

        $data = [
            'pickupDate' => date('Y-m-d', strtotime('+1 day')),
            'firstName' => 'Yusmira',
            'lastName' => 'Granda',
            'firm' => 'IKUSA LLC',
            'streetAddress' => '28123 Canyo Wren Dr.',
            'secondaryAddress' => '',
            'city' => 'Katy',
            'state' => 'Texas',
            'ZIPCode' => '77494',
            'ZIPPlus4' => '',
            'email' => 'ikusa.creative@gmail.com',
            'packageType' => 'FIRST-CLASS_PACKAGE_SERVICE',
            'packageCount' => 1,
            'weight' => 0.40,
            'packageLocation' => 'FRONT_DOOR',
            'specialInstructions' => 'Ring the bell twice',
            'dogPresent' => false
        ];
    // Extraer datos directamente desde $data
    $pickupDate = $data['pickupDate'];
    $firstName = $data['firstName'];
    $lastName = $data['lastName'];
    $firm = $data['firm'];
    $streetAddress = $data['streetAddress'];
    $secondaryAddress = $data['secondaryAddress'];
    $city = $data['city'];
    $state = $data['state'];
    $zipCode = $data['ZIPCode'];
    $zipPlus4 = $data['ZIPPlus4'];
    $urbanization = $data['urbanization'] ?? '';

    $email = $data['email'];
    $packageType = $data['packageType'];
    $packageCount = $data['packageCount'];
    $estimatedWeight = $data['weight'];
    $packageLocation = $data['packageLocation'];
    $specialInstructions = $data['specialInstructions'];
    $dogPresent = $data['dogPresent'];

    // Structure to USPS
    $pickupData = [
        'pickupDate' => $pickupDate,
        'pickupAddress' => [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'firm' => $firm,
            'address' => [
                'streetAddress' => $streetAddress,
                'secondaryAddress' => $secondaryAddress,
                'city' => $city,
                'state' => $state,
                'ZIPCode' => $zipCode,
                'ZIPPlus4' => $zipPlus4,
                'urbanization' => $urbanization
            ],
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


    // USPS API
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

    if ($httpCode !== 200) {
        throw new Exception("Error USPS HTTP $httpCode: $response");
    }

    echo $response;

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

function getAccessToken(): ?string {
    global $client_id, $client_secret;

    $curl = curl_init('https://apis.usps.com/oauth2/v3/token');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'scope' => 'usps.shipping.pickups'
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    if (!$response) return null;

    $data = json_decode($response, true);

    // Log del token y del scope recibido
    error_log("Token recibido: " . ($data['access_token'] ?? 'Ninguno'));
    error_log("Scope recibido: " . ($data['scope'] ?? 'Ninguno'));

    return $data['access_token'] ?? null;
}
