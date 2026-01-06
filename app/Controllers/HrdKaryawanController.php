<?php

namespace App\Controllers;

use App\Helpers\ModulGuard;
use App\Services\HrdKaryawanService;
use App\Repositories\HrdKaryawanRepository;

class HrdKaryawanController
{
    public function index(): void
    {
        ModulGuard::check('MASTER_KARYAWAN', 'READ');
        
        $service = new HrdKaryawanService(
            new HrdKaryawanRepository()
        );

        echo json_encode($service->listKaryawan());
    }
}
