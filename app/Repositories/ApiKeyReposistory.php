<?php

namespace App\Repositories;

use PDO;
use App\Interfaces\ApiKeyRepositoryInterface;

class ApiKeyRepository implements ApiKeyRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function store(string $apiKey, string $username): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO api_keys (api_key, client_name, is_active, created_at)
            VALUES (:api_key, :username, 1, NOW())
        ");

        $stmt->execute([
            ':api_key'  => $apiKey,
            ':username' => $username,
        ]);
    }

    public function findActiveByKey(string $apiKey): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM api_keys
            WHERE api_key = :api_key
              AND is_active = 1
            LIMIT 1
        ");

        $stmt->execute([
            ':api_key' => $apiKey,
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }
}
