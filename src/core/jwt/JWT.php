<?php
namespace TinyApi\core\jwt;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;


class JWT
{
    private static string $secret;

    public static function init()
    {
        self::$secret = getenv('JWT_SECRET') ?: 'SUA_CHAVE_SECRETA_LOCAL';
    }

    public static function encode(array $payload, int $expMinutes = 60): string
    {
        $issuedAt = time();
        $expireAt = $issuedAt + $expMinutes * 60;
        $token = array_merge($payload, ['iat' => $issuedAt, 'exp' => $expireAt]);

        return FirebaseJWT::encode($token, self::$secret, 'HS256');
    }

    public static function decode(string $jwt): array
    {
        $decoded = FirebaseJWT::decode($jwt, new Key(self::$secret, 'HS256'));
        return (array) $decoded;
    }
}
JWT::init();
