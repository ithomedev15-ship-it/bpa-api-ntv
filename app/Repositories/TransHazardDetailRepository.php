<?php

namespace App\Repositories;

use PDO;
use App\Database\Connection;
use App\Interfaces\TransHazardDetailRepositoryInterface;

class TransHazardDetailRepository implements TransHazardDetailRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::get('bpa');
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO SFT_TRANS_HAZ_D1 (
                KODE_HAZ_D1,
                KODE_HAZ,
                TANGGAL,
                WAKTU,
                PIC,
                TINDAKAN_PERBAIKAN,
                NOTE,
                FLAG_STATUS,
                LOG_ENTRY_NAME,
                LOG_ENTRY_DATE
            ) VALUES (
                :kode_haz_d1,
                :kode_haz,
                :tanggal,
                :waktu,
                :pic,
                :tindakan_perbaikan,
                :note,
                :flag_status,
                :log_entry_name,
                NOW()
            )
        ");

        return $stmt->execute([
            'kode_haz_d1'         => $data['kode_haz_d1'],
            'kode_haz'            => $data['kode_haz'],
            'tanggal'             => $data['tanggal'],
            'waktu'               => $data['waktu'],
            'pic'                 => $data['pic'],
            'tindakan_perbaikan'  => $data['tindakan_perbaikan'],
            'note'                => $data['note'],
            'flag_status'         => $data['flag_status'],
            'log_entry_name'      => $data['log_entry_name'],
        ]);
    }

    public function update(string $kodeHazD1, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE SFT_TRANS_HAZ_D1
            SET
                PIC                = :pic,
                TINDAKAN_PERBAIKAN = :tindakan,
                NOTE               = :note,
                LOG_UPDATE_NAME    = :user,
                LOG_UPDATE_DATE    = NOW()
            WHERE KODE_HAZ_D1 = :kode
        ");

        return $stmt->execute([
            'pic'      => $data['pic'],
            'tindakan' => $data['tindakan_perbaikan'],
            'note'     => $data['note'],
            'user'     => $data['user'],
            'kode'     => $kodeHazD1
        ]);
    }

}
