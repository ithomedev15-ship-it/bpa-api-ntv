<?php

namespace App\Services;

use App\Repositories\UserModulRepository;

class UserModulService
{
    private UserModulRepository $repo;

    public function __construct(UserModulRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getUserModulOutput(string $kodeUser): array
    {
        $rows = $this->repo->getModulStatusByUser($kodeUser);

        $result = [];

        foreach ($rows as $row) {
            $result[] = [
                strtolower($row['KODE_MODUL']) => (int) $row['STATUS']
            ];
        }

        return $result;
    }
}
