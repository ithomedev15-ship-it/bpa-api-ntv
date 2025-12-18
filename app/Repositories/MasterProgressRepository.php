<?php

namespace App\Repositories;

use App\Database\Connection;
use App\Interfaces\MasterProgressRepositoryInterface;
use PDO;

class MasterProgressRepository implements MasterProgressRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::get('bpa');
    }

    public function getAll(array $filters = []): array
    {
        $sql = "
            SELECT
                KODE_PROGRESS,
                NAMA_PROGRESS,
                TIPE,
                NOTE,
                FLAG_STATUS
            FROM SFT_MASTER_PROGRESS
            WHERE 1=1
        ";

        $params = [];

        // Filter status (aktif saja default)
        if (isset($filters['status'])) {
            $sql .= " AND FLAG_STATUS = :status";
            $params['status'] = $filters['status'];
        }

        // Filter tipe
        if (!empty($filters['tipe'])) {
            $sql .= " AND TIPE = :tipe";
            $params['tipe'] = $filters['tipe'];
        }

        $sql .= " ORDER BY FLAG_IDX ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function findByKode(string $kode): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM SFT_MASTER_PROGRESS
            WHERE KODE_PROGRESS = :kode
            LIMIT 1
        ");

        $stmt->execute(['kode' => $kode]);

        return $stmt->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $sql = "
            INSERT INTO SFT_MASTER_PROGRESS (
                KODE_PROGRESS,
                NAMA_PROGRESS,
                TIPE,
                NOTE,
                FLAG_IDX,
                FLAG_LEVEL,
                FLAG_ENTRY,
                FLAG_FAV,
                FLAG_LOCK,
                FLAG_SYSTEM,
                FLAG_DEFAULT,
                FLAG_STATUS,
                LOG_ENTRY_NAME,
                LOG_ENTRY_DATE,
                UUID
            ) VALUES (
                :kode_progress,
                :nama_progress,
                :tipe,
                :note,
                :flag_idx,
                :flag_level,
                :flag_entry,
                :flag_fav,
                :flag_lock,
                :flag_system,
                :flag_default,
                :flag_status,
                :log_entry_name,
                NOW(),
                :uuid
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'kode_progress'  => $data['kode_progress'],
            'nama_progress'  => $data['nama_progress'],
            'tipe'           => $data['tipe'],
            'note'           => $data['note'],
            'flag_idx'       => $data['flag_idx'],
            'flag_level'     => $data['flag_level'],
            'flag_entry'     => $data['flag_entry'],
            'flag_fav'       => $data['flag_fav'],
            'flag_lock'      => $data['flag_lock'],
            'flag_system'    => $data['flag_system'],
            'flag_default'   => $data['flag_default'],
            'flag_status'    => $data['flag_status'],
            'log_entry_name' => $data['log_entry_name'],
            'uuid'           => $data['uuid'],
        ]);
    }

}