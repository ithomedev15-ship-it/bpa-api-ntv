<?php

namespace App\Database;

use PDO;
use RuntimeException;

class Connection
{
    private static array $instances = [];

    public static function get(string $name = 'bpa'): PDO
    {
        if (!isset(self::$instances[$name])) {

            $configPath = dirname(__DIR__) . '/config/database.php';

            if (!file_exists($configPath)) {
                throw new RuntimeException('Database config file not found: ' . $configPath);
            }

            $config = require $configPath;

            if (!isset($config[$name])) {
                throw new RuntimeException("Database config [$name] not defined");
            }

            $db = $config[$name];

            self::$instances[$name] = new PDO(
                $db['dsn'],
                $db['user'],
                $db['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }

        return self::$instances[$name];
    }
}
