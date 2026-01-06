<?php

namespace App\Repositories;

use PDO;
use App\Database\Connection;
use App\Interfaces\MasterHazKategoriInterface;

class MasterHazKategoriRepository implements MasterHazKategoriInterface
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
                KODE_HAZ_KATEGORI,
                NAMA_HAZ_KATEGORI,
                NOTE,
                FLAG_STATUS
            FROM SFT_MASTER_HAZ_KATEGORI
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
}