<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\ModulGuard;
use App\Services\MasterProgressService;

class MasterProgressController
{
    public function index(): void
    {
        ModulGuard::check('MASTER_PROGRESS', 'READ');
        
        $filters = [
            'status' => $_GET['status'] ?? null,
            'tipe'   => $_GET['tipe'] ?? null,
        ];

        $data = (new MasterProgressService())->list($filters);

        Response::success($data);
    }

    public function show(string $kode): void
    {
        try {
            $data = (new MasterProgressService())->detail($kode);
            Response::success($data);
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 404);
        }
    }

    public function store(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!is_array($payload)) {
            $payload = $_POST;
        }

        try {
            // sementara pakai username dari token / session
            $username = 'system'; // nanti bisa diambil dari middleware

            (new MasterProgressService())->create($payload, $username);

            Response::success([
                'message' => 'Master progress berhasil ditambahkan'
            ], 201);

        } catch (\Exception $e) {
            Response::error($e->getMessage(), 422);
        }
    }
}
