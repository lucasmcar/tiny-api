<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/core/App.php';

use TinyApi\core\App;

$app = new App();
use Dotenv\Dotenv;

if (file_exists(__DIR__ . '/.env')) {
    Dotenv::createImmutable(__DIR__)->load();
}

$api = $app->router();

require __DIR__ . '/routes/api.php';
//https://card.limbersoftware.com.br/api/cross/consulta/disponibilidade/
//https://card.limbersoftware.com.br/api/auth

$app->run();