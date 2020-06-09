<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

require __DIR__ . '/../vendor/autoload.php';

$dotEnv = new Dotenv\Dotenv(__DIR__ . '/../');
if (file_exists(__DIR__ . '/../' . '.env')) {
    $dotEnv->load();
}

// Instantiate LeagueContainer
$container = new \League\Container\Container();

// Enable Autowiring for our dependencies..
$container->delegate(
  new \League\Container\ReflectionContainer()
);

// Sets our Container
\Slim\Factory\AppFactory::setContainer($container);

// Create App instance
$app = \Slim\Factory\AppFactory::create();

// Parse our providers
require_once __DIR__ . '/providers.php';

// Routing
$routes = require __DIR__ . '/routes.php';
$routes($app);

return $app;
