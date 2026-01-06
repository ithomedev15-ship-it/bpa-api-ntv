<?php

namespace App\Services;

use App\Repositories\MenuRepository;

class MenuService
{
    private MenuRepository $repo;

    public function __construct(MenuRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getMenuTree(): array
    {
        return $this->buildTree(
            $this->repo->getAll()
        );
    }

    private function buildTree(array $menus): array
    {
        $refs = [];
        $tree = [];

        foreach ($menus as $menu) {
            $menu['children'] = [];
            $refs[$menu['id']] = $menu;
        }

        foreach ($refs as $id => &$menu) {
            if ($menu['parent_id']) {
                $refs[$menu['parent_id']]['children'][] = &$menu;
            } else {
                $tree[] = &$menu;
            }
        }

        return $tree;
    }
}
