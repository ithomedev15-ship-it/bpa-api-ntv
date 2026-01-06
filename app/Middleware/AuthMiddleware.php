<?php

namespace App\Middleware;

use App\Database\Connection;
use PDO;

class AuthMiddleware
{
    public function handle(): void
    {
        // 🔥 Ambil Authorization header
        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [];

        $auth = $headers['Authorization']
            ?? $headers['authorization']
            ?? $_SERVER['HTTP_AUTHORIZATION']
            ?? null;

        if (!$auth) {
            http_response_code(401);
            exit(json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ]));
        }

        // 🔹 Bearer token
        $plainToken  = trim(str_replace('Bearer', '', $auth));
        $hashedToken = hash('sha256', $plainToken);

        $db = Connection::get('auth');

        /**
         * 🔥 Ambil DATA TOKEN
         * - tokenable_id = KODE_USER
         * - name         = USERNAME (dipakai untuk LOG_EDIT_NAME)
         */
        $stmt = $db->prepare("
            SELECT
                tokenable_id AS KODE_USER,
                name          AS USERNAME
            FROM personal_access_tokens
            WHERE token = :token
              AND tokenable_type = 'USER'
            LIMIT 1
        ");

        $stmt->execute([
            'token' => $hashedToken
        ]);

        $token = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$token) {
            http_response_code(401);
            exit(json_encode([
                'success' => false,
                'message' => 'Token invalid'
            ]));
        }

        // ✅ SET AUTH CONTEXT (INI YANG PENTING)
        $_SERVER['AUTH_USER']      = (string) $token['USERNAME'];
        $_SERVER['AUTH_USER_CODE'] = (string) $token['KODE_USER'];
    }
}
