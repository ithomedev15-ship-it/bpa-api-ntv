<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Helpers\Response;

class AuthController
{
    public function login(): void
    {
        // Ambil JSON
        $payload = json_decode(file_get_contents('php://input'), true);

        // Fallback ke FORM
        if (!is_array($payload)) {
            $payload = $_POST;
        }

        // Normalisasi input (support UPPER & lower)
        $username = $payload['username']
            ?? $payload['USERNAME']
            ?? null;

        $password = $payload['password']
            ?? $payload['PASSWORD']
            ?? null;

        // 🔍 VALIDASI TERPISAH
        if (empty($username)) {
            Response::error('Username wajib diisi', 422);
        }

        if (empty($password)) {
            Response::error('Password wajib diisi', 422);
        }

        try {
            $result = (new AuthService())->login([
                'username' => $username,
                'password' => $password,
            ]);

            Response::success([
                'access_token' => $result['token'],
                'token_type'   => 'Bearer',
                'user'         => $result['user'],
            ]);
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 401);
        }
    }

    public function logout(): void
    {
        // Ambil Authorization header (AMAN UNTUK APACHE)
        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [];

        $auth = $headers['Authorization']
            ?? $headers['authorization']
            ?? $_SERVER['HTTP_AUTHORIZATION']
            ?? null;

        if (!$auth) {
            Response::error('Unauthorized', 401);
        }

        // Ambil token plain
        $token = trim(str_replace('Bearer', '', $auth));

        (new AuthService())->logout($token);

        Response::success([
            'message' => 'Logout berhasil'
        ]);
    }
}

