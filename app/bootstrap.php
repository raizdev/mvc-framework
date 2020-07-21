<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

// Loads our environment config
$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__, '../.env');
if (file_exists(__DIR__ . '/../' . '.env')) {
    $dotEnv->load();
}

// Instantiate LeagueContainer
$container = new \League\Container\Container();

// Enable Auto-wiring for our dependencies..
$container->delegate(
    (new League\Container\ReflectionContainer)->cacheResolutions()
);

// Parse our providers
require_once __DIR__ . '/providers.php';

// Create App instance
$app = $container->get(App::class);;

$middleware = require_once __DIR__ . '/middleware.php';
$middleware($app);

// Routing
$routes = require __DIR__ . '/routes.php';
$routes($app);

// Sets our App Proxy
$alias = 'App';
$proxy = \App\Proxy\App::class;
$manager = new Statical\Manager();
$manager->addProxyInstance($alias, $proxy, $app);

// Helper functions
require_once __DIR__ . '/helpers.php';

// Sets our Route-Cache
$routeCollector = $app->getRouteCollector();
$routeCollector->setCacheFile('../tmp/Cache/routing/route.cache.php');

return $app;
