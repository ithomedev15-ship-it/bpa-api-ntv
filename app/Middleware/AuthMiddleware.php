<?php
namespace App\Middleware;

use App\Database\Connection;

class AuthMiddleware
{
    public function handle()
    {
        // 🔥 AMBIL HEADER DENGAN CARA AMAN
        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [];

        $auth = $headers['Authorization']
            ?? $headers['authorization']
            ?? $_SERVER['HTTP_AUTHORIZATION']
            ?? null;

        if (!$auth) {
            http_response_code(401);
            exit(json_encode(['message' => 'NO AUTH HEADER']));
        }

        // Bersihkan token
        $plainToken = trim(str_replace('Bearer', '', $auth));
        $hashedToken = hash('sha256', $plainToken);

        $db = Connection::get('bpa');

        $stmt = $db->prepare(
            "SELECT id FROM personal_access_tokens WHERE token = :token"
        );
        $stmt->execute(['token' => $hashedToken]);

        if (!$stmt->fetch()) {
            http_response_code(401);
            exit(json_encode(['message' => 'Token invalid']));
        }
    }
}
