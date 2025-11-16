<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require ("../../app/config/connection.php");

// // Leer el cuerpo de la solicitud
// $input = file_get_contents('php://input');
// $data = json_decode($input, true);
$alias = $_POST['alias'];

// Verificar si 'alias' está presente en los datos recibidos
if (isset($alias)) {
    //$alias = $data['alias'];

    // Asumiendo que $mysqli es tu conexión a la base de datos
    $sql = "SELECT * FROM clients WHERE alias = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $alias);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();

    if ($client) {
        echo json_encode(['success' => true, 'data' => $client]);
      
    } else {
        echo json_encode(['success' => false, 'message' => 'Client not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Alias not provided']);
}
?>
