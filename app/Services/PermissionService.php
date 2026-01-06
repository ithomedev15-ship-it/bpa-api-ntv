<?php

namespace App\Services;

use App\Interfaces\PermissionRepositoryInterface;

class PermissionService
{
    private PermissionRepositoryInterface $repo;

    public function __construct(PermissionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function check(
        string $username,
        string $modul,
        string $permission
    ): bool {
        // 1️⃣ cek override user
        $userPermission = $this->repo->getUserPermission(
            $username,
            $modul,
            $permission
        );

        if ($userPermission !== null) {
            return $userPermission;
        }

        // 2️⃣ cek role
        $role = $this->repo->getUserRole($username);
        if (!$role) {
            return false;
        }

        return $this->repo->hasRolePermission(
            $role,
            $modul,
            $permission
        );
    }
}