<?php

namespace App\Repositories;

use App\Database\Connection;
use App\Interfaces\HrdKaryawanRepositoryInterface;
use PDO;

class HrdKaryawanRepository implements HrdKaryawanRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        // pakai DB HRD (external)
        $this->db = Connection::get('hrd');
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT 
                NIK,
                NAMA,
                ALIAS
            FROM HRD_KARY
            ORDER BY NIK
        ");

        return $stmt->fetchAll();
    }
}
