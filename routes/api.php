<?php

use App\Router\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\DebugController;
use App\Middleware\ApiKeyMiddleware;
use App\Middleware\UrlKeyMiddleware;
use App\Controllers\ApiKeyController;
use App\Controllers\HrdKaryawanController;
use App\Controllers\MasterProgressController;
use App\Controllers\ApiKeyController as ControllersApiKeyController;
use App\Controllers\ApiKeyController as AppControllersApiKeyController;

$router = new Router();



// $router->get('/users', [UserController::class, 'index'],
// [AuthMiddleware::class]
// );

// Auth
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout'], 
    [AuthMiddleware::class
]);
$router->get('/users', [UserController::class, 'index']);

// ---------- MASTER ---------
$router->get('/master-progress', [
    MasterProgressController::class,
    'index'
], [
    AuthMiddleware::class
]);
$router->get('/master-progress/show', [
    MasterProgressController::class,
    'show'
], [
    AuthMiddleware::class
]);

$router->post('/master-progress', [
    MasterProgressController::class,
    'store'
], [
    AuthMiddleware::class
]);

// -----------------------------

$router->post('/debug/generate-url-key', [
    DebugController::class,
    'generateUrlKey'
]);


// $router->get('/api/{client}/hrd-karyawan', [
//     HrdKaryawanController::class,
//     'index'
// ], [
//     ClientMiddleware::class,
//     ApiKeyMiddleware::class,
//     AuthMiddleware::class,
// ]);


$router->post('/generate-api-key', [
    ApiKeyController::class,
    'generate'
]);

$router->get('/hrd-karyawan', [HrdKaryawanController::class,'index'], [
    ApiKeyMiddleware::class,
    AuthMiddleware::class
]);








return $router;