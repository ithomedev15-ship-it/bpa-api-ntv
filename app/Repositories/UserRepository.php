<?php

namespace App\Repositories;

use PDO;
use App\Database\Connection;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::get('bpa');
    }

    public function all(): array
    {
        return $this->db
            ->query("SELECT id, name, email FROM users")
            ->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, name, email FROM users WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email) VALUES (:name, :email)"
        );

        $stmt->execute([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function findByUsername(string $username): ?array
    {
        $sql = "
            SELECT *
            FROM SFT_MASTER_USER
            WHERE USERNAME = :username
              AND FLAG_STATUS = '1'
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);

        return $stmt->fetch() ?: null;
    }
}