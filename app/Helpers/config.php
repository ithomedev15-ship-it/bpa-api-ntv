<?php

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        static $configs = [];

        // 🔒 Aman: split max 2
        $segments = explode('.', $key, 2);

        $file = $segments[0] ?? null;
        $item = $segments[1] ?? null;

        if (!$file) {
            return $default;
        }

        // ✅ SESUAI STRUKTUR app/config
        if (!isset($configs[$file])) {
            $path = dirname(__DIR__) . "/config/{$file}.php";

            if (!file_exists($path)) {
                return $default;
            }

            $configs[$file] = require $path;
        }

        // ✅ JIKA hanya config('menu')
        if ($item === null) {
            return $configs[$file];
        }

        // ✅ JIKA config('database.mysql')
        return $configs[$file][$item] ?? $default;
    }
}
