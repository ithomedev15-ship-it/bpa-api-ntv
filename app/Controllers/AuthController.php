<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\AuthService;
use App\Helpers\ApiKeyGenerator;



class AuthController
{
    private AuthService $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function login(): void
    {
        /* ===============================
        * 0️⃣ AMBIL & NORMALISASI HEADER
        * =============================== */
        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [];

        // Normalisasi agar aman di Apache
        $headers = array_change_key_case($headers, CASE_LOWER);

        /* ===============================
        * 1️⃣ VALIDASI X-MODUL (LOGIN)
        * =============================== */
        $xModul = $headers['x-modul'] ?? null;

        if (!$xModul) {
            Response::error(
                'X-MODUL wajib dikirim',
                403
            );
        }

        try {
            $xModul = strtoupper(trim($xModul));

            if (!str_contains($xModul, ':')) {
                Response::error(
                    'Format X-MODUL harus MODUL:ACTION',
                    403
                );
            }

            [$modul, $action] = explode(':', $xModul, 2);

            // 🔒 LOGIN hanya boleh AUTH:LOGIN
            if ($modul !== 'AUTH' || $action !== 'LOGIN') {
                Response::error(
                    'X-MODUL tidak diizinkan untuk login',
                    403
                );
            }
        } catch (\Exception $e) {
            Response::error(
                $e->getMessage(),
                403
            );
        }

        /* ===============================
        * 2️⃣ AMBIL API KEY DARI HEADER
        * =============================== */
        $apiUsername  = $headers['x-username'] ?? null;
        $apiTimestamp = $headers['x-timestamp'] ?? null;
        $clientApiKey = $headers['x-api-key'] ?? null;

        if (!$apiUsername || !$apiTimestamp || !$clientApiKey) {
            Response::error(
                'Header API Key tidak lengkap',
                401
            );
        }

        /* ===============================
        * 3️⃣ VALIDASI API KEY
        * =============================== */
        try {
            $serverApiKey = ApiKeyGenerator::generate(
                $apiUsername,
                $apiTimestamp
            );

            if (!hash_equals($serverApiKey, $clientApiKey)) {
                Response::error(
                    'API Key tidak valid',
                    401
                );
            }
        } catch (\Exception $e) {
            Response::error(
                $e->getMessage(),
                401
            );
        }

        /* ===============================
        * 4️⃣ AMBIL PAYLOAD LOGIN
        * =============================== */
        $payload = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!is_array($payload)) {
            $payload = $_POST;
        }

        $username = $payload['username']
            ?? $payload['USERNAME']
            ?? null;

        $password = $payload['password']
            ?? $payload['PASSWORD']
            ?? null;

        if (empty($username)) {
            Response::error(
                'Username wajib diisi',
                422
            );
        }

        if (empty($password)) {
            Response::error(
                'Password wajib diisi',
                422
            );
        }

        /* ===============================
        * 5️⃣ PROSES LOGIN
        * =============================== */
        try {
            $result = $this->service->login([
                'username' => $username,
                'password' => $password,
            ]);

            Response::success([
                'access_token' => $result['token'],
                'token_type'   => 'Bearer',
                'user'         => $result['user'],
            ]);
        } catch (\Exception $e) {
            Response::error(
                $e->getMessage(),
                401
            );
        }
    }

}