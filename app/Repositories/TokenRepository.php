<?php

namespace App\Repositories;

use App\Database\Connection;

class TokenRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::get('auth');
    }

    public function create(
        string $userId,
        ?string $username = null
    ): string {
        $plain  = bin2hex(random_bytes(32));
        $hashed = hash('sha256', $plain);

        $sql = "
            INSERT INTO personal_access_tokens
            (
                tokenable_id,
                tokenable_type,
                name,
                token,
                created_at
            )
            VALUES
            (
                :id,
                'USER',
                :name,
                :token,
                NOW()
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id'    => $userId,              // USR003
            'name'  => $username ?? $userId, // fallback aman
            'token' => $hashed,
        ]);

        return $plain; // dikirim ke client
    }

   public function deleteByToken(string $plainToken): void
    {
        $hashed = hash('sha256', $plainToken);

        $db = Connection::get('auth');

        $stmt = $db->prepare(
            "DELETE FROM personal_access_tokens WHERE token = :token"
        );

        $stmt->execute([
            'token' => $hashed,
        ]);
    }
}