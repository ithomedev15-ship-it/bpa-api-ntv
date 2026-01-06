<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Repositories\TransHazardRepository;
use App\Repositories\TransHazardDetailRepository;
use App\Services\TransHazardService;

class TransHazardController
{
    private TransHazardService $service;

    public function __construct()
    {
        // 🔹 Manual Dependency Injection (sesuai standar project)
        $hazardRepository = new TransHazardRepository();
        $detailRepository = new TransHazardDetailRepository();

        $this->service = new TransHazardService(
            $hazardRepository,
            $detailRepository
        );
    }

    /**
     * GET /trans-hazard
     */
    public function index(): void
    {
        try {
            $data = $this->service->listHazard();
            Response::success($data);
        } catch (\Throwable $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    /**
     * POST /trans-hazard
     */
    public function store(): void
    {
        try {
            // ✅ KHUSUS form-data
            $payload = $_POST;

            if (empty($payload)) {
                throw new \Exception('Payload kosong');
            }

            $this->service->createHazard($payload);

            Response::success([
                'message' => 'Hazard berhasil disimpan'
            ]);
        } catch (\Throwable $e) {
            Response::error($e->getMessage(), 400);
        }
    }


    public function update(string $kodeHaz): void
    {
        try {
            $payload = $_POST;

            if (empty($payload)) {
                parse_str(file_get_contents('php://input'), $payload);
            }

            // ❌ jangan ambil user dari payload
            unset($payload['user']);

            $this->service->updateHazard($kodeHaz, $payload);

            Response::success([
                'message' => 'Hazard berhasil diperbarui'
            ]);
        } catch (\Throwable $e) {
            Response::error($e->getMessage(), 400);
        }
    }



}
