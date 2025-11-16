<?php
require_once __DIR__ . '/env.php';

$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_user = $_ENV['DB_USER'] ?? '';
$db_password = $_ENV['DB_PASS'] ?? '';
$db_db = $_ENV['DB_NAME'] ?? '';

define('DB_HOST',  $db_host);  // it changes based on the configuration 
define('DB_USER', $db_user);
define('DB_PASS', $db_password);
define('DB_NAME',  $db_db);

  $mysqli = @new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db
  );

$mysqli->set_charset("utf8mb4");

  if ($mysqli->connect_error) {
    echo 'Errno: '.$mysqli->connect_errno;
    echo '<br>';
    echo 'Error: '.$mysqli->connect_error;
    exit();
  }