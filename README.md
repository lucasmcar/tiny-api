#TinyApi

Um microframework RESTful minimalista em PHP, inspirado no Slim e Laravel, com:

Após desenvolvimento de um framework baseado em Laravel, mas simplista. Tive a ideia de desenvolver um outro framework voltado para apis Restful

Essa primeira versão está disponível para testes

- Rotas simples (`$api->get`, `$api->post`, etc.)
- Controllers desacoplados
- Middlewares (ex: autenticação JWT)
- Suporte nativo ao Firebase JWT
- Respostas JSON elegantes


# 🚀 Instalação

```bash
composer require lucasmcar/tiny-api-framework

# Como Usar

``` php
<?php
    require 'vendor/autoload.php';

    use TinyAPI\\Core\\App;

    $app = new App();
    $app->router()->get('/hello', fn() => ['message' => 'Olá mundo!']);
    $app->run();

# uso de middleware

use TinyApi\\middlewares\\AuthMiddleware;

$app->router()->get('/user', [UserController::class, 'profile'], [AuthMiddleware::class]);

