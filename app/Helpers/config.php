<?php

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        static $configs = [];

        [$file, $item] = explode('.', $key, 2);

        if (!isset($configs[$file])) {
            // ✅ SESUAI STRUKTUR app/config
            $path = dirname(__DIR__) . "/config/{$file}.php";

            if (!file_exists($path)) {
                return $default;
            }

            $configs[$file] = require $path;
        }

        return $configs[$file][$item] ?? $default;
    }
}
