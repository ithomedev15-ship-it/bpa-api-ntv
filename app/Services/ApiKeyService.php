<?php

namespace App\Services;

use App\Database\Connection;
use App\Services\ApiKeyGenerate;
use PDO;
use InvalidArgumentException;

class ApiKeyService
{
    private PDO $db;

    public function __construct()
    {
        // pakai koneksi default bpa
        $this->db = Connection::get('bpa');
    }

    /**
     * Generate API Key & simpan ke SFT_API_KEY
     */
    public function generateAndStore(string $username): string
    {
        $username = trim($username);

        if ($username === '') {
            throw new InvalidArgumentException('Username tidak boleh kosong');
        }

        // === API KEY ===
        $apiKey = ApiKeyGenerate::generateUserMd5($username);

        // === HASHTAG (opsional, untuk security tambahan) ===
        $hashTag = hash('sha256', $apiKey);

        $stmt = $this->db->prepare("
            INSERT INTO SFT_API_KEY (
                KODE_KEY,
                CLIENT_NAME,
                HASHTAG,
                FLAG_STATUS,
                LOG_ENTRY_NAME,
                LOG_ENTRY_DATE,
                UUID
            ) VALUES (
                :kode_key,
                :client_name,
                :hashtag,
                '1',
                :entry_name,
                NOW(),
                UUID()
            )
        ");

        $stmt->execute([
            ':kode_key'    => $apiKey,
            ':client_name'=> $username,
            ':hashtag'    => $hashTag,
            ':entry_name' => $username,
        ]);

        return $apiKey;
    }

    /**
     * Validasi API Key (middleware)
     */
    public function validate(string $apiKey): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM SFT_API_KEY
            WHERE KODE_KEY = :kode_key
              AND FLAG_STATUS = '1'
            LIMIT 1
        ");

        $stmt->execute([
            ':kode_key' => $apiKey,
        ]);

        return (bool) $stmt->fetchColumn();
    }
}
