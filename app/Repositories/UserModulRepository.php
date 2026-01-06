<?php

namespace App\Repositories;

use App\Database\Connection;
use PDO;

class UserModulRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::get('auth');
    }

    public function getModulStatusByUser(string $kodeUser): array
    {
        $sql = "
             SELECT
                m.KODE_MODUL,
                COALESCE(um.FLAG_ALLOW, m.FLAG_DEFAULT, 0) AS STATUS
            FROM SFT_APP_MODUL m
            LEFT JOIN SFT_APP_USER_MODUL um
                ON um.KODE_MODUL = m.KODE_MODUL
               AND um.KODE_USER = :user
            WHERE m.FLAG_STATUS = 1
            ORDER BY m.FLAG_IDX
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user' => $kodeUser
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
