<?php

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        static $configs = [];

        // contoh: security.url_key_secret
        [$file, $item] = explode('.', $key, 2);

        if (!isset($configs[$file])) {
            $path = dirname(__DIR__, 2) . "/config/{$file}.php";

            if (!file_exists($path)) {
                return $default;
            }

            $configs[$file] = require $path;
        }

        return $configs[$file][$item] ?? $default;
    }
}
