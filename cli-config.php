<?php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__ . '/src/App/bootstrap.php';

$container = $app->getContainer();
$em = $container->get(EntityManager::class);

return ConsoleRunner::createHelperSet($em);