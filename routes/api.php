<?php

use App\Router\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\DebugController;
use App\Middleware\UrlKeyMiddleware;
use App\Controllers\HrdKaryawanController;
use App\Controllers\MasterProgressController;

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

// $router->get('/hrd-karyawan', [HrdKaryawanController::class, 'index'],
//     [AuthMiddleware::class
// ]);

// $router->get('/master-karyawan/{key}', [
//     HrdKaryawanController::class,
//     'index'
// ], [
//     AuthMiddleware::class,
//     UrlKeyMiddleware::class
// ]);

$router->post('/debug/generate-url-key', [
    DebugController::class,
    'generateUrlKey'
]);






return $router;