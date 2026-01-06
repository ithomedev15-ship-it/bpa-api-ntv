<?php

namespace App\Services;

use App\Helpers\Auth;
use InvalidArgumentException;
use App\Interfaces\TransHazardRepositoryInterface;
use App\Interfaces\TransHazardDetailRepositoryInterface;

class TransHazardService
{
    private TransHazardRepositoryInterface $hazardRepo;
    private TransHazardDetailRepositoryInterface $detailRepo;

    public function __construct(
        TransHazardRepositoryInterface $hazardRepo,
        TransHazardDetailRepositoryInterface $detailRepo
    ) {
        $this->hazardRepo = $hazardRepo;
        $this->detailRepo = $detailRepo;
    }

    /**
     * LIST (existing)
     */
    public function listHazard(): array
    {
        return $this->hazardRepo->getAll();
    }

    /**
     * CREATE HEADER + DETAIL
     */
    public function createHazard(array $payload): bool
    {
        if (empty($payload['header'])) {
            throw new InvalidArgumentException('Data header wajib diisi');
        }

        $header  = $payload['header'];
        $details = $payload['details'] ?? [];

        // 🔒 Ambil user dari AUTH (TOKEN.NAME)
        $creator = Auth::username();

        if (!$creator) {
            throw new \Exception('User tidak terautentikasi');
        }

        $this->hazardRepo->begin();

        try {
            // 🔹 HEADER
            $this->hazardRepo->create([
                'kode_haz'          => $header['kode_haz'],
                'tanggal'           => $header['tanggal'],
                'waktu'             => $header['waktu'],
                'kode_haz_kategori' => $header['kode_haz_kategori'],
                'kode_progress'     => $header['kode_progress'],
                'pic'               => $header['pic'],
                'deskripsi'         => $header['deskripsi'],
                'deadline'          => $header['deadline'],
                'flag_status'       => '1',

                // ✅ dari auth, bukan payload
                'log_entry_name'    => $creator,
            ]);

            // 🔹 DETAIL (optional)
            foreach ($details as $row) {
                $this->detailRepo->create([
                    'kode_haz_d1'        => $row['kode_haz_d1'],
                    'kode_haz'           => $header['kode_haz'],
                    'tanggal'            => $row['tanggal'],
                    'waktu'              => $row['waktu'],
                    'pic'                => $row['pic'],
                    'tindakan_perbaikan' => $row['tindakan_perbaikan'],
                    'note'               => $row['note'],
                    'flag_status'        => '1',

                    // ✅ konsisten dari auth
                    'log_entry_name'     => $creator,
                ]);
            }

            $this->hazardRepo->commit();
            return true;

        } catch (\Throwable $e) {
            $this->hazardRepo->rollback();
            throw $e;
        }
    }


    public function updateHazard(string $kodeHaz, array $payload): bool
    {
        $existing = $this->hazardRepo->findByKode($kodeHaz);
        if (!$existing) {
            throw new \Exception('Hazard tidak ditemukan');
        }

        $user = Auth::username();
        if (!$user) {
            throw new \Exception('User tidak terautentikasi');
        }

        $data = [
            'kode_haz_kategori' => $payload['kode_haz_kategori']
                ?? $existing['KODE_HAZ_KATEGORI'],

            'kode_progress' => $payload['kode_progress']
                ?? $existing['KODE_PROGRESS'],

            'pic' => $payload['pic']
                ?? $existing['PIC'],

            'deskripsi' => $payload['deskripsi']
                ?? $existing['DESKRIPSI'],

            'deadline' => $payload['deadline']
                ?? $existing['DEADLINE'],

            // ✅ dari auth, bukan dari request
            'user' => $user,
        ];

        $this->hazardRepo->begin();

        try {
            $this->hazardRepo->update($kodeHaz, $data);
            $this->hazardRepo->commit();
            return true;
        } catch (\Throwable $e) {
            $this->hazardRepo->rollback();
            throw $e;
        }
    }


}
