<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use Slim\App;
use DI\ContainerBuilder;

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

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Add container definitions
$containerBuilder->addDefinitions(__DIR__ . '/Container/dependencies.php');

// Set up and add repositories
$containerBuilder->addDefinitions(__DIR__ . '/Container/repositories.php');

// Set up and add services
$containerBuilder->addDefinitions(__DIR__ . '/Container/services.php');

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Create App instance
$app = $container->get(App::class);

// Register middleware
$middleware = require __DIR__ . '/Container/middleware.php';
$middleware($app);

// Routing
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

return $app;
