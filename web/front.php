<?php

if (!isset($env)) {
    http_response_code(503);
    echo 'Front controller must have environment configured.';
    exit;
}

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/DoucheWeb/app.php';
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../config/$env.json", [
    'storage_path'  => __DIR__.'/../storage',
    'template_path' => __DIR__.'/../src/DoucheWeb/views',
]));
$app->run();
