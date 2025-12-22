<?php

namespace App\Services;

use App\Helpers\GlobalKey;
use App\Repositories\UserRepository;
use App\Repositories\TokenRepository;

class AuthService
{
    private $users;
    private $tokens;

    public function __construct()
    {
        $this->users  = new UserRepository();
        $this->tokens = new TokenRepository();
    }

    public function login(array $data): array
    {
        $user = $this->users->findByUsername($data['username']);

        if (!$user) {
            throw new \Exception('User tidak ditemukan');
        }

        // 🔐 VALIDASI GLOBAL KEY INTERNAL (BARU)
        if (!GlobalKey::validate($user['GLOBAL_KEY'])) {
            throw new \Exception('User tidak diizinkan (global key mismatch)');
        }

        if (!password_verify($data['password'], $user['PASSWORD'])) {
            throw new \Exception('Password salah');
        }

        // 🔑 TOKEN TIDAK DIGANGGU
        $token = $this->tokens->create($user['KODE_USER']);

        return [
            'token' => $token,
            'user'  => [
                'KODE_USER'  => $user['KODE_USER'],
                'USERNAME'   => $user['USERNAME'],
                'KODE_ROLE'  => $user['KODE_ROLE'],
                'FLAG_LEVEL' => $user['FLAG_LEVEL'],
            ],
        ];
    }

    public function logout(string $plainToken): void
    {
        $this->tokens->deleteByToken($plainToken);
    }
}