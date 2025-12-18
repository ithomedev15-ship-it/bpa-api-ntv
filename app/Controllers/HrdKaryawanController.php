<?php

namespace App\Controllers;

use App\Repositories\HrdKaryawanRepository;
use App\Services\HrdKaryawanService;

class HrdKaryawanController
{
    public function index(): void
    {
        $service = new HrdKaryawanService(
            new HrdKaryawanRepository()
        );

        echo json_encode($service->listKaryawan());
    }
}
