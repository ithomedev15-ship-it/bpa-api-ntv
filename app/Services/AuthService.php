<?php

namespace App\Services;

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
        $modul  = $_SERVER['APP_MODUL']  ?? '';
        $action = $_SERVER['APP_ACTION'] ?? '';

        if ($modul !== 'AUTH' || $action !== 'LOGIN') {
            throw new \Exception('Akses modul tidak diizinkan');
        }


        $user = $this->users->findByUsername($data['username']);

        if (!$user) {
            throw new \Exception('User tidak ditemukan');
        }

        if (!password_verify($data['password'], $user['PASSWORD'])) {
            throw new \Exception('Password salah');
        }

        $token = $this->tokens->create(
            $user['KODE_USER'],
            $user['USERNAME']
        );

        return [
            'token' => $token,
            'user'  => [
                'KODE_USER'  => $user['KODE_USER'],
                'USERNAME'   => $user['USERNAME'],
                'FLAG_LEVEL' => $user['FLAG_LEVEL'],
            ],
        ];
    }

    public function logout(string $plainToken): void
    {
        $this->tokens->deleteByToken($plainToken);
    }
}