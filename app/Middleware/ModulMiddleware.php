<?php

namespace App\Middleware;

use App\Helpers\Response;
use App\Helpers\ModulParser;

class ModulMiddleware
{
    public function handle(): void
    {
        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [];

        $headers = array_change_key_case($headers, CASE_LOWER);

        $xModul = $headers['x-modul'] ?? null;

        error_log('MODUL RAW : ' . $xModul);

        if (!$xModul) {
            Response::error(
                'X-MODUL wajib dikirim',
                403
            );
        }

        try {
            $parsed = ModulParser::parse($xModul);

            // Simpan ke global / request context
            $_SERVER['APP_MODUL']  = $parsed['modul'];
            $_SERVER['APP_ACTION'] = $parsed['action'];
        } catch (\Exception $e) {
            Response::error(
                $e->getMessage(),
                403
            );
        }
    }
}
