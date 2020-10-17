<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

use Illuminate\Database\Capsule\Manager as Capsule;
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

// Helper functions
require_once __DIR__ . '/helpers.php';

// Parse our providers
require_once __DIR__ . '/providers.php';

if ($_ENV['CACHE_ENABLED']) {
    $container->addServiceProvider(
        new \Ares\Framework\Provider\CacheServiceProvider()
    );
}

// Create App instance
$app = $container->get(App::class);;

$middleware = require_once __DIR__ . '/middleware.php';
$middleware($app);

// Routing
$routes = require __DIR__ . '/routes.php';
$routes($app);

// Sets our App Proxy
$alias = 'App';
$proxy = \Ares\Framework\Proxy\App::class;
$manager = new Statical\Manager();
$manager->addProxyInstance($alias, $proxy, $app);

if(!file_exists(tmp_dir())) {
    mkdir(tmp_dir(), 0755, true);
}

// Sets our Route-Cache
if ($_ENV['API_DEBUG'] == "production") {
    $routeCollector = $app->getRouteCollector();

    if(!file_exists(route_cache_dir())) {
        mkdir(route_cache_dir(), 0755, true);
    }

    $routeCollector->setCacheFile(route_cache_dir() . '/route.cache.php');
}

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['DB_HOST'].':'.$_ENV['DB_PORT'],
    'database'  => $_ENV['DB_NAME'],
    'username'  => $_ENV['DB_USER'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();

return $app;
