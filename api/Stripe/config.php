<?php
// api/Stripe/config.php
use Dotenv\Dotenv;

$projectRoot = __DIR__ . '/../../'; // desde api/Stripe hasta la raíz

if (file_exists($projectRoot . 'vendor/autoload.php')) {
    require_once $projectRoot . 'vendor/autoload.php';
}

if (file_exists($projectRoot . '.env')) {
    $dotenv = Dotenv::createImmutable($projectRoot);
    $dotenv->safeLoad();
}

return [
    'stripe' => [
        'public_key'  => $_ENV['STRIPE_API_KEY'] ?? '',
        'secret_key'  => $_ENV['STRIPE_SECRET_KEY'] ?? '',
        'environment' => $_ENV['STRIPE_ENVIRONMENT'] ?? 'test',
        'api_version' => $_ENV['STRIPE_API_VERSION'] ?? '2023-08-16',
        'success_url' => $_ENV['STRIPE_SUCCESS_URL'] ?? '',
        'cancel_url'  => $_ENV['STRIPE_CANCEL_URL'] ?? '',
    ],
];

