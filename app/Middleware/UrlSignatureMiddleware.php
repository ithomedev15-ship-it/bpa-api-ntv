<?php

namespace App\Middleware;

use App\Helpers\Response;
use App\Services\UrlKeySignatureService;

class UrlSignatureMiddleware
{
    private UrlKeySignatureService $service;

    public function __construct()
    {
        $this->service = new UrlKeySignatureService();
    }

    public function handle(): void
{
    $headers = array_change_key_case(getallheaders(), CASE_LOWER);

    $xModul   = strtoupper(trim($headers['x-modul'] ?? ''));
    $username = strtoupper(trim($headers['x-username'] ?? ''));

    // 🔥 URL KEY DARI ROUTE PARAM
    $urlKey = $_SERVER['ROUTE_PARAMS']['urlkey'] ?? null;

    if (in_array($xModul, ['AUTH:LOGIN', 'AUTH:LOGOUT'], true)) {
        return;
    }

    if (!$urlKey) {
        Response::error('URL Key wajib dikirim di URL', 403);
    }

    try {
        $this->service->validateFromUrl(
            $xModul,
            $username,
            $urlKey
        );
    } catch (\Exception $e) {
        Response::error($e->getMessage(), 403);
    }
}

}
