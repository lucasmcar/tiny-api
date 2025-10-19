<?php
namespace TinyApi\controllers;

use TinyApi\core\http\Request;
use TinyApi\core\http\Response;
use TinyApi\core\auth\Auth;
use TinyApi\core\connection\Database;

/**
 * @OA\Tag(name="Users")
 */
class UserController
{

    private $db;

    public function __construct() 
    {
        $this->db = Database::getInstance();
    }
    /**
     * @OA\Get(
     *  path="/users",
     *  summary="Lista de usuários públicos",
     *  @OA\Response(response=200, description="Lista")
     * )
     */
    public function index()
    {

        $users = $this->db->query("Select * from clientes_integrados");
        
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