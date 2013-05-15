<?php

namespace DoucheWeb;

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();

$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver'    => 'pdo_sqlite',
        'path'      => __DIR__.'/../../storage/data.db',
    ],
]);
$app->register(new ServiceControllerServiceProvider());
$app->register(new ServiceProvider(), [
    'douche.user_repo.file' => __DIR__.'/../../storage/users.json',
]);

$app->get('/', 'interactor.auction_list:__invoke');

return $app;
