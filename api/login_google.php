<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Cross-Origin-Opener-Policy: same-origin-allow-popups');
header('Cross-Origin-Embedder-Policy: unsafe-none');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Google Client
$client = new Google_Client(['client_id' => $_ENV['GOOGLE_CLIENT_ID']]);

if ($client instanceof Google_Client) {
    error_log("✅ Google_Client cargado correctamente con CLIENT_ID: " . $_ENV['GOOGLE_CLIENT_ID']);
} else {
    error_log("❌ Falló la carga de Google_Client");
}

// Leer JSON POST
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['credential'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Token no recibido']);
    exit;
}

$credential = $input['credential'];

// Verificar token JWT de Google
$payload = $client->verifyIdToken($credential);

if (!$payload) {
    http_response_code(401);
    echo json_encode(['error' => 'Token inválido']);
    exit;
}

// Datos del usuario
$google_id = $payload['sub'];
$email = $payload['email'];
$name = $payload['name'];
$provider = "Google";
// $picture = $payload['picture'];

// Conexión a base de datos
require_once '../../app/config/connection.php';
$email_esc = $mysqli->real_escape_string($email);
$name_esc  = $mysqli->real_escape_string($name);

// Verificar si el usuario existe
$query = $mysqli->query("SELECT id, role FROM users WHERE email = '$email_esc'");

if ($query->num_rows === 0) {
    // Insertar nuevo usuario con rol por defecto 'user'
    $default_role = 'user';
    $mysqli->query("INSERT INTO users (name, email, provider, provider_id, role) VALUES ('$name_esc', '$email_esc', '$provider', '$google_id', '$default_role')");
    $user_id = $mysqli->insert_id;
    $user_role = $default_role;
} else {
    $row = $query->fetch_assoc();
    $user_id = $row['id'];
    $user_role = $row['role'];
}

// Iniciar sesión
$_SESSION['user_id'] = $user_id;
$_SESSION['user'] = [
    'id' => $user_id,
    'email' => $email,
    'name' => $name,
    'role' => $user_role,
    // 'picture' => $picture
];

// Responder con usuario incluyendo role
echo json_encode([
    'success' => true,
    'user' => $_SESSION['user']
]);
