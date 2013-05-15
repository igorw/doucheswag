<?php

namespace DoucheWeb;

use Douche\Storage\Sql\Util;

require __DIR__.'/../../vendor/autoload.php';

$app = require __DIR__.'/app.php';
Util::createAuctionSchema($app['db']);
