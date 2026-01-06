<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Repositories\TransSafetyTalkRepository;
use App\Services\TransSafetyTalkService;

class TransSafetyTalkController
{
    private TransSafetyTalkService $service;

    public function __construct()
    {
        $repo = new TransSafetyTalkRepository();
        $this->service = new TransSafetyTalkService($repo);
    }

    public function index()
    {
        $data = $this->service->index();

        Response::success($data);
    }

   public function store(): void
    {
        try {
            // ✅ KHUSUS form-data (SAMA SEPERTI HAZARD)
            $payload = $_POST;

            if (empty($payload)) {
                throw new \Exception('Payload kosong');
            }

            // ✅ USER DARI MIDDLEWARE (SAMA SEPERTI HAZARD)
            $username = $_SERVER['AUTH_USER'];

            $this->service->create($payload, $username);

            Response::success([
                'message' => 'Safety Talk berhasil disimpan'
            ]);
        } catch (\Throwable $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    public function update(): void
    {
        try {
            if (empty($_POST['kode_stalk'])) {
                throw new \Exception('Kode Safety Talk wajib');
            }

            $this->service->update(
                $_POST['kode_stalk'], // ✅ DIKIRIM
                $_POST,
                $_SERVER['AUTH_USER']
            );

            Response::success([
                'message' => 'Safety Talk berhasil diupdate'
            ]);
        } catch (\Throwable $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    public function delete(): void
    {
        try {
            if (empty($_POST['kode_stalk'])) {
                throw new \Exception('Kode Safety Talk wajib');
            }

            $this->service->delete($_POST['kode_stalk']);

            Response::success([
                'message' => 'Safety Talk berhasil dihapus'
            ]);
        } catch (\Throwable $e) {
            Response::error($e->getMessage(), 400);
        }
    }


}
