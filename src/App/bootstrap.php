<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

require __DIR__ . '/../../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../../');
if (file_exists(__DIR__ . '/../../' . '.env')) {
    $dotenv->load();
}
$dotenv->required([
    'DB_HOST',
    'DB_PORT',
    'DB_NAME',
    'DB_USER',
    'DB_PASS'
]);

// Instantiate LeagueContainer
$container = new \League\Container\Container();

\Slim\Factory\AppFactory::setContainer($container);

// Create App instance
$app = \Slim\Factory\AppFactory::create();

require_once __DIR__ . '/Container/repositories.php';

require_once __DIR__ . '/Container/providers.php';

// Routing
$routes = require __DIR__ . '/../App/routes.php';
$routes($app);

return $app;
