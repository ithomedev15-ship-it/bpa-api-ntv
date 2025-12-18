<?php

namespace App\Services;

use App\Helpers\Uuid;
use App\Repositories\MasterProgressRepository;

class MasterProgressService
{
    private MasterProgressRepository $repo;

    public function __construct()
    {
        $this->repo = new MasterProgressRepository();
    }

    public function list(array $filters = []): array
    {
        // Default: hanya data aktif
        if (!isset($filters['status'])) {
            $filters['status'] = '1';
        }

        return $this->repo->getAll($filters);
    }

    public function detail(string $kode): array
    {
        $data = $this->repo->findByKode($kode);

        if (!$data) {
            throw new \Exception('Master progress tidak ditemukan');
        }

        return $data;
    }

    public function create(array $input, string $username): void
    {
        if (empty($input['kode_progress'])) {
            throw new \Exception('Kode progress wajib diisi');
        }

        if (empty($input['nama_progress'])) {
            throw new \Exception('Nama progress wajib diisi');
        }

        if (empty($input['tipe'])) {
            throw new \Exception('Tipe wajib diisi');
        }

        $data = [
            'kode_progress'  => $input['kode_progress'],
            'nama_progress'  => $input['nama_progress'],
            'tipe'           => $input['tipe'],
            'note'           => $input['note'] ?? null,

            'flag_idx'       => 0,
            'flag_level'     => 1,
            'flag_entry'     => '1',
            'flag_fav'       => '0',
            'flag_lock'      => '0',
            'flag_system'    => '0',
            'flag_default'   => '0',
            'flag_status'    => '1',

            'log_entry_name' => $username,
            'uuid'           => Uuid::v4(),
        ];
        $this->repo->create($data);
    }



}
