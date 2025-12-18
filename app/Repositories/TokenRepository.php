<?php

namespace App\Repositories;

use App\Database\Connection;

class TokenRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::get('bpa');
    }

    public function create(string $userId): string
    {
        $plain = bin2hex(random_bytes(32));
        $hashed = hash('sha256', $plain);

        $sql = "
            INSERT INTO personal_access_tokens
            (tokenable_id, tokenable_type, token, created_at)
            VALUES (:id, 'USER', :token, NOW())
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id'    => $userId,
            'token' => $hashed,
        ]);

        return $plain;
    }

   public function deleteByToken(string $plainToken): void
    {
        $hashed = hash('sha256', $plainToken);

        $db = Connection::get('bpa');

        $stmt = $db->prepare(
            "DELETE FROM personal_access_tokens WHERE token = :token"
        );

        $stmt->execute([
            'token' => $hashed,
        ]);
    }
}