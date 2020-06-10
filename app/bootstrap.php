<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use Psr\Container\ContainerInterface;
use Slim\App;

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

// Parse our providers
require_once __DIR__ . '/providers.php';

// Create App instance
$app = $container->get(App::class);;

// Registers our JWTClaimMiddleware and CorsMiddleware Global
$app->add(\App\Middleware\ClaimMiddleware::class);
$app->add(\App\Middleware\CorsMiddleware::class);

// Routing
$routes = require __DIR__ . '/routes.php';
$routes($app);

return $app;
