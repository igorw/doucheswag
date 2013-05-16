<?php

namespace DoucheWeb;

use Douche\Storage\Sql\Util;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\DBAL;

require __DIR__.'/../../vendor/autoload.php';

$replacements = [
    'storage_path'  => __DIR__.'/../../storage',
    'template_path' => __DIR__.'/../../src/DoucheWeb/views',
];

$env = getenv("DOUCHE_ENV") ?: "dev";

$json = file_get_contents(__DIR__."/../../config/$env.json");
foreach ($replacements as $key => $value) {
    $json = str_replace("%".$key."%", $value, $json);
}
$config = json_decode($json, true);

$conn = DBAL\DriverManager::getConnection($config['db.options'], new DBAL\Configuration());

$console = new Application();

$console
    ->register('init')
    ->setDescription('Initialize doucheswag')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($conn) {
        Util::createAuctionSchema($conn);
    });

$console
    ->register('sql')
    ->setDefinition([
        new InputArgument('query', InputArgument::REQUIRED),
    ])
    ->setDescription('Query the database')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($conn) {
        $sql = $input->getArgument('query');
        $rows = $conn->executeQuery($sql);
        var_dump(iterator_to_array($rows));
    });

$console
    ->register('create-auction')
    ->setDefinition([
        new InputArgument('name', InputArgument::REQUIRED),
        new InputArgument('ends-at', InputArgument::REQUIRED),
    ])
    ->setDescription('Create an auction')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $name = $input->getArgument('name');
        $endsAt = new \DateTime($input->getArgument('ends-at'));
        // $app['douche.auction_repo']->createAuction($name, $endsAt);
        throw new \RuntimeException('Not implemented yet');
    });

$console->run();
