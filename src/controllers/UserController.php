<?php
namespace TinyApi\controllers;

use TinyApi\core\http\Request;
use TinyApi\core\http\Response;
use TinyApi\core\auth\Auth;

/**
 * @OA\Tag(name="Users")
 */
class UserController
{
    /**
     * @OA\Get(
     *  path="/users",
     *  summary="Lista de usuários públicos",
     *  @OA\Response(response=200, description="Lista")
     * )
     */
    public function index()
    {
        $users = [
            ['id'=>1,'name'=>'Admin'],
            ['id'=>2,'name'=>'User 2']
        ];
        return Response::json(['users' => $users]);
    }

    /**
     * @OA\Get(
     *  path="/profile",
     *  summary="Perfil do usuário autenticado",
     *  security={{"bearerAuth":{}}},
     *  @OA\Response(response=200, description="Perfil"),
     *  @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function profile()
    {
        $user = Auth::user();
        return Response::json(['user' => $user]);
    }
}