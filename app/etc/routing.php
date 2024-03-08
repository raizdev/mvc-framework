<?php 
if($_ENV['APP_ENV'] == 'dev'){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

use Sunrise\Http\ServerRequest\ServerRequestFactory;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Http\Router\RequestHandler\QueueableRequestHandler;

use function Sunrise\Http\Router\emit;

$container = require __DIR__ . '/container.php';

$router = $container->get('router');
$middlewares = $container->get('middlewares');

$handler = new QueueableRequestHandler($router);
$handler->add(...$middlewares);

$request = ServerRequestFactory::fromGlobals();
$response = $handler->handle($request);

emit($response);

exit(0);