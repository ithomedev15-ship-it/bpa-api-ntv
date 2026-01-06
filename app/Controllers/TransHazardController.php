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


    public function update(): void
    {
        try {
            $payload = $_POST;

            if (empty($payload['kode_haz'])) {
                throw new \Exception('Kode hazard wajib');
            }

            $this->service->updateHazard(
                $payload['kode_haz'],
                $payload
            );

            Response::success([
                'message' => 'Hazard berhasil diupdate'
            ]);
        } catch (\Throwable $e) {
            Response::error($e->getMessage(), 400);
        }
    }



}
