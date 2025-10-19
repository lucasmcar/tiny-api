<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/core/App.php';

use TinyApi\core\App;
use TinyApi\core\connection\Database;

$app = new App();
use Dotenv\Dotenv;

if (file_exists(__DIR__ . '/.env')) {
    Dotenv::createImmutable(__DIR__)->load();
}

// Força também para getenv() e $_ENV
foreach ($_ENV as $key => $value) {
    putenv("$key=$value");
}


$api = $app->router();
$db = Database::getInstance();

require __DIR__ . '/routes/api.php';
//https://card.limbersoftware.com.br/api/cross/consulta/disponibilidade/
//https://card.limbersoftware.com.br/api/auth

$app->run();