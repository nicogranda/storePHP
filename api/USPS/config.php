<?php
// config.php

function loadEnv(string $path): void {
    if (!file_exists($path)) {
        throw new RuntimeException(".env file not found at $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue; // Ignorar líneas vacías y comentarios
        }

        if (!str_contains($line, '=')) {
            continue; // Ignorar líneas sin '='
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"'"); // Quitar comillas si las hay

        if (getenv($key) === false) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Carga las variables de entorno desde el archivo .env (ajusta la ruta si es necesario)
loadEnv(__DIR__ . '/../../.env');

// Variables globales para usar en tu app
$consumer_key = getenv('CONSUMER_KEY');
$consumer_secret = getenv('CONSUMER_SECRET');
$account_number = getenv('ACCOUNT_NUMBER');
