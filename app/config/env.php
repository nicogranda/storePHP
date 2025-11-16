<?php
// app/config/env.php

// Incluye Composer autoload
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// Carga variables de entorno desde la raíz del proyecto
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
