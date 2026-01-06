<?php

namespace App\Repositories;

use PDO;
use App\Database\Connection;
use App\Interfaces\ApiKeyRepositoryInterface;


class ApiKeyRepository implements ApiKeyRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::get('default');
    }

    public function isActiveKey(string $username, string $apiKey): bool
    {
        $sql = "
            SELECT COUNT(*) 
            FROM SFT_API_KEY
            WHERE USERNAME = :username
              AND API_KEY = :api_key
              AND IS_ACTIVE = 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'username' => strtoupper($username),
            'api_key'  => $apiKey,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }
}
