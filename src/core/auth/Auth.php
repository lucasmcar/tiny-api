<?php
namespace TinyApi\core\auth;

class Auth
{
    public static function user()
    {
        return $GLOBALS['auth_user'] ?? null;
    }
}
