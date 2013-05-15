<?php

namespace DoucheWeb;

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();

$app->register(new MonologServiceProvider());
$app->register(new DoctrineServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new ServiceProvider());

$app->get('/', 'interactor.auction_list:__invoke');

return $app;
