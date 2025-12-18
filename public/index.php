<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// LOAD .env UNTUK XAMPP
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use App\Router\Router;

header('Content-Type: application/json');

// LOAD ROUTES
$router = require_once __DIR__ . '/../routes/api.php';

// AMBIL URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// NORMALISASI BASE PATH
$basePath = '/bpa_api/public';

if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$uri = $uri ?: '/';


// var_dump(getenv('DB_DSN'), getenv('DB_USER'));
// exit;


// DISPATCH
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);

