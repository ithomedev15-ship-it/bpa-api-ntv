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
use App\Controllers\MasterHazKategoriController;
use App\Controllers\TransHazardController;
use App\Controllers\MasterProgressController;
use App\Middleware\ModulMiddleware;
use App\Middleware\UrlSignatureMiddleware;

$router = new Router();

// ==================================--======================================
// ==================================-AUTHENTICATION-======================================
$router->post('/login', [AuthController::class, 'login'],[ModulMiddleware::class]);
$router->post('/logout', [AuthController::class, 'logout'], 
    [
        AuthMiddleware::class,
        ModulMiddleware::class
]);

// ==================================-MASTER-======================================
// $router->get('master-hazard-kategori', [MasterHazKategoriController::class, 'index'],
// [
//     UrlSignatureMiddleware::class,
//     AuthMiddleware::class,
//     ModulMiddleware::class,
// ]);
$router->get('master-hazard-kategori/{urlkey}', [
    MasterHazKategoriController::class,
    'index'
], [
    UrlSignatureMiddleware::class,
    AuthMiddleware::class,
    ModulMiddleware::class,
]);

$router->get('/users', [UserController::class, 'index']);

//----------------PROGRESS-------------------
$router->get('/master-progress', [
    MasterProgressController::class,
    'index'
], [
    AuthMiddleware::class,
    ModulMiddleware::class
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

// --------------KARYAWAN-----------------
$router->get('/hrd-karyawan', [HrdKaryawanController::class,'index'], [
    AuthMiddleware::class,
    ModulMiddleware::class,
]);


// ==================================-TRANSAKSI-======================================
$router->get('/hazards', [
    TransHazardController::class,
    'index'
], [
    AuthMiddleware::class,
]);

$router->post('/trans-hazard', [
    TransHazardController::class,
    'store'
], [
    AuthMiddleware::class,
]);

$router->put('trans-hazard/{kode_haz}', [
    TransHazardController::class,
    'update'
], [
    AuthMiddleware::class,
]);


// ==================================--======================================
$router->get('/debug/api-key', [
    DebugController::class,
    'generateApiKey'
]);

$router->post('/generate-api-key', [
    ApiKeyController::class,
    'generate'
]);










return $router;