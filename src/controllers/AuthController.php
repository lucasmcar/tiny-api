<?php
namespace TinyApi\controllers;

use TinyApi\core\http\Response;
use TinyApi\core\http\Request;
use TinyApi\core\jwt\JWT;

/**
 * @OA\Tag(name="Auth")
 */
class AuthController
{
    /**
     * @OA\Post(
     *   path="/login",
     *   summary="Login e geração de token",
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="password", type="string")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Token gerado"),
     *   @OA\Response(response=401, description="Credenciais inválidas")
     * )
     */
    public function login()
    {
        $data = Request::input();

        // Exemplo: validação absurda só pra demonstrar
        if (($data['email'] ?? '') === 'admin@teste' && ($data['password'] ?? '') === '123456') {
            $token = JWT::encode(['user_id' => 1, 'email' => 'admin@teste', 'name' => 'Admin']);
            return Response::json(['token' => $token]);
        }
        return Response::json(['error' => 'Credenciais inválidas'], 401);
    }
}