<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

use Slim\App;

/**
 * Registers our Global Middleware
 *
 * @param App $app
 */
return function (App $app) {
    $container = $app->getContainer();
    $logger = $container->get(\Psr\Log\LoggerInterface::class);

    $app->add(new Tuupola\Middleware\CorsMiddleware([
        "origin" => [$_ENV['WEB_FRONTEND_LINK']],
        "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
        "headers.allow" => ["Content-Type", "Authorization", "If-Match", "If-Unmodified-Since", "Origin"],
        "headers.expose" => ["Content-Type", "Etag", "Origin"],
        "credentials" => true,
        "cache" => $_ENV['TOKEN_DURATION']
    ]));
    $app->add(\Slim\HttpCache\Cache::class);
    $app->add(\App\Middleware\BodyParserMiddleware::class);
    $app->add(\App\Middleware\ClaimMiddleware::class);
    $app->addRoutingMiddleware();

    $app->addErrorMiddleware(true, true, true, $logger);
};
