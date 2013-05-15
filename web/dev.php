<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/DoucheWeb/app.php';
$app['debug'] = true;
$app->run();
