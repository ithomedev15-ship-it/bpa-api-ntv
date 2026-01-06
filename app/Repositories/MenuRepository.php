<?php

namespace App\Repositories;

class MenuRepository
{
    public function getAll(): array
    {
        return config('menu');
    }
}
