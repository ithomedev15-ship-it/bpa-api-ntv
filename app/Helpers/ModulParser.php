<?php

namespace App\Helpers;

use InvalidArgumentException;

class ModulParser
{
    /**
     * Format: MODUL:ACTION
     * Contoh: AUTH:LOGIN
     */
    public static function parse(string $value): array
    {
        $value = strtoupper(trim($value));

        if (!str_contains($value, ':')) {
            throw new InvalidArgumentException(
                'Format X-MODUL harus MODUL:ACTION'
            );
        }

        error_log('MODUL PARSER INPUT : ' . $value);


        [$modul, $action] = explode(':', $value, 2);

        if ($modul === '' || $action === '') {
            throw new InvalidArgumentException(
                'X-MODUL tidak valid'
            );
        }

        return [
            'modul'  => $modul,
            'action' => $action,
        ];
    }
}
