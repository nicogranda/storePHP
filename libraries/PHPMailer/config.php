<?php
// /libraries/PHPMailer/config.php

// Cargar autoload de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Verificar que exista el archivo .env
$envFile = __DIR__ . '/../../.env';
if (!file_exists($envFile)) {
    throw new Exception('.env file not found');
}

// Leer el .env línea por línea y definir variables de entorno
$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    $line = trim($line);
    if (!$line || $line[0] === '#') continue; // Ignorar comentarios
    putenv($line); // Definir en entorno
    $parts = explode('=', $line, 2);
    if (count($parts) === 2) {
        $_ENV[$parts[0]] = $parts[1];
    }
}

// Configuración SMTP usando getenv()
$host       = getenv('MAIL_HOST') ?: 'smtp.example.com';
$SMTPAuth   = true;
$Username   = getenv('MAIL_USERNAME') ?: '';
$Password   = getenv('MAIL_PASSWORD') ?: '';
$SMTPSecure = getenv('MAIL_SMTP_SECURE') ?: 'ssl'; // ssl o tls
$Port       = getenv('MAIL_PORT') ?: 465;

// Datos del remitente
$mailerFrom    = getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@example.com';
$mailerId      = getenv('MAIL_FROM_NAME') ?: 'Mi Empresa';
$mailerReplay  = getenv('MAIL_REPLY_TO') ?: $mailerFrom; // opcional

// Mensajes
$messageSent   = 'Mensaje Enviado';
$messageError  = 'Mensaje no Enviado';

// CC opcional
$mailerToToo   = 'ikusa.ads@gmail.com';

// Opciones adicionales
$font_family   = 'roboto';
