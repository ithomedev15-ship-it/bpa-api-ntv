<?php

namespace App\Middleware;

use App\Helpers\UrlKey;
use App\Database\Connection;

class UrlKeyMiddleware
{
    public function handle(string $key): void
    {
        try {
            $data = UrlKey::decrypt($key);
        } catch (\Exception $e) {
            http_response_code(403);
            exit(json_encode(['message' => 'Invalid URL key']));
        }

        // cek expire
        $expire = config('security.url_key_expire');

        if (time() - $data['t'] > $expire) {
            http_response_code(403);
            exit(json_encode(['message' => 'URL key expired']));
        }

        // cocokan dengan user dari token
        $headers = getallheaders();
        $auth = $headers['Authorization'] ?? '';

        $plainToken = trim(str_replace('Bearer', '', $auth));
        $hashed = hash('sha256', $plainToken);

        $db = Connection::get('bpa');

        $stmt = $db->prepare("
            SELECT u.USERNAME
            FROM personal_access_tokens t
            JOIN SFT_MASTER_USER u ON u.KODE_USER = t.tokenable_id
            WHERE t.token = :token
        ");

        $stmt->execute(['token' => $hashed]);
        $user = $stmt->fetch();

        if (!$user || $user['USERNAME'] !== $data['u']) {
            http_response_code(403);
            exit(json_encode(['message' => 'URL key not allowed']));
        }
    }
}
