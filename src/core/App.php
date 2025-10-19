<?php
namespace TinyApi\core;

use TinyApi\core\Router;

class App
{
    protected Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function run()
    {
        $this->router->dispatch();
    }
}
