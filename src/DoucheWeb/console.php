<?php

namespace DoucheWeb;

use Douche\Storage\Sql\Util;

use Igorw\Silex\ConfigServiceProvider;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

require __DIR__.'/../../vendor/autoload.php';

$app = require __DIR__.'/app.php';
$app->register(new ConfigServiceProvider(__DIR__."/../../config/dev.json", [
    'storage_path'  => __DIR__.'/../../storage',
    'template_path' => __DIR__.'/../../src/DoucheWeb/views',
]));

$console = new Application();

$console
    ->register('init')
    ->setDescription('Initialize doucheswag')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        Util::createAuctionSchema($app['db']);
    });

$console
    ->register('sql')
    ->setDefinition([
        new InputArgument('query', InputArgument::REQUIRED),
    ])
    ->setDescription('Query the database')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $sql = $input->getArgument('query');
        $rows = $app['db']->executeQuery($sql);
        var_dump(iterator_to_array($rows));
    });

$console
    ->register('create-auction')
    ->setDefinition([
        new InputArgument('name', InputArgument::REQUIRED),
        new InputArgument('ends-at', InputArgument::REQUIRED),
    ])
    ->setDescription('Create an auction')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $name = $input->getArgument('name');
        $endsAt = new \DateTime($input->getArgument('ends-at'));
        // $app['douche.auction_repo']->createAuction($name, $endsAt);
        throw new \RuntimeException('Not implemented yet');
    });

$console->run();
