<?php

namespace App\Interfaces;

interface PermissionRepositoryInterface
{
    public function getUserRole(string $username): ?string;

    public function getUserPermission(
        string $username,
        string $modul,
        string $permission
    ): ?bool;

    public function hasRolePermission(
        string $role,
        string $modul,
        string $permission
    ): bool;
}