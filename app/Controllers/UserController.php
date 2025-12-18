<?php

namespace App\Controllers;

use App\Services\UserService;

class UserController
{
    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function index(): void
    {
        echo json_encode(['message' => 'Users OK']);
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $user = $this->service->get($id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
            return;
        }

        echo json_encode(['data' => $user]);
    }
}