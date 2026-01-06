<?php

namespace App\Helpers;

class ModulGuard
{
    /**
     * Validasi X-MODUL header
     *
     * @param string $expectedModul
     * @param string $expectedAction
     */
    public static function check(
        string $expectedModul,
        string $expectedAction
    ): void {
        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [];

        $headers = array_change_key_case($headers, CASE_LOWER);

        $xModul = $headers['x-modul'] ?? null;

        if (!$xModul) {
            Response::error(
                'X-MODUL wajib dikirim',
                403
            );
        }

        $xModul = strtoupper(trim($xModul));

        if (!str_contains($xModul, ':')) {
            Response::error(
                'Format X-MODUL harus MODUL:ACTION',
                403
            );
        }

        [$modul, $action] = explode(':', $xModul, 2);

        if (
            $modul !== strtoupper($expectedModul) ||
            $action !== strtoupper($expectedAction)
        ) {
            Response::error(
                'Akses modul tidak diizinkan',
                403
            );
        }

        // Optional: simpan ke context
        $_SERVER['APP_MODUL']  = $modul;
        $_SERVER['APP_ACTION'] = $action;
    }
}
