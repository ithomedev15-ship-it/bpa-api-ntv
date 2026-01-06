<?php

namespace App\Controllers;

use App\Helpers\ModulGuard;
use App\Services\MasterHazKategoriService;
use App\Repositories\MasterHazKategoriRepository;

class MasterHazKategoriController
{
    public function index(): void
    {
        ModulGuard::check('MASTER_HAZARD_KATEGORI', 'READ');
        
        $service = new MasterHazKategoriService(
            new MasterHazKategoriRepository()
        );

        echo json_encode($service->listKategori());
    }
}
