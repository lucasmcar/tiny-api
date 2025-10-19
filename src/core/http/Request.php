<?php
namespace TinyApi\core\http;

class Request
{
    public static function input(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? $_POST;
    }
}
