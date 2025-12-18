<?php
namespace App\Helpers;

class Response
{
    public static function success(array $data = [], int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'data'    => $data,
        ]);

        exit;
    }

    public static function error(string $message, int $code = 400, array $errors = []): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ]);

        exit;
    }
}
