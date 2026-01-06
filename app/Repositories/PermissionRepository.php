<?php

namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use App\Database\Connection;
use PDO;

class PermissionRepository implements PermissionRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::get('default');
    }

    // ambil role user
    public function getUserRole(string $username): ?string
    {
        $sql = "
            SELECT KODE_ROLE
            FROM SFT_MASTER_USER
            WHERE USERNAME = :username
              AND FLAG_STATUS = 1
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);

        return $stmt->fetchColumn() ?: null;
    }

    // cek override user
    public function getUserPermission(
        string $username,
        string $modul,
        string $permission
    ): ?bool {
        $sql = "
            SELECT FLAG_STATUS
            FROM SFT_APP_USER_PERMISSIONS
            WHERE USERNAME = :username
              AND KODE_MODUL = :modul
              AND KODE_PERMISSIONS = :permission
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'username'   => $username,
            'modul'      => $modul,
            'permission' => $permission,
        ]);

        $result = $stmt->fetchColumn();

        if ($result === false) {
            return null; // tidak ada override
        }

        return $result == 1;
    }

    // cek permission dari role
    public function hasRolePermission(
        string $role,
        string $modul,
        string $permission
    ): bool {
        $sql = "
            SELECT 1
            FROM SFT_APP_ROLE_PERMISSIONS
            WHERE KODE_ROLE = :role
              AND KODE_MODUL = :modul
              AND KODE_PERMISSIONS = :permission
              AND FLAG_STATUS = 1
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'role'       => $role,
            'modul'      => $modul,
            'permission' => $permission,
        ]);

        return (bool) $stmt->fetchColumn();
    }
}