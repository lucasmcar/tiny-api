<?php
namespace TinyApi\middleware;

use TinyApi\core\http\Response;
use TinyApi\core\jwt\JWT;


class AuthMiddleware
{
    public function handle()
    {
        $headers = function_exists('getallheaders') ? getallheaders() : apache_request_headers();
        $auth = $headers['Authorization'] ?? ($headers['authorization'] ?? '');

        if (empty($auth) || !str_starts_with($auth, 'Bearer ')) {
            Response::json(['error' => 'Token ausente'], 401);
        }

        $token = substr($auth, 7);

        try {
            $payload = JWT::decode($token);
            $GLOBALS['auth_user'] = $payload;
        } catch (\Exception $e) {
            Response::json(['error' => $e->getMessage()], 401);
        }
    }
}