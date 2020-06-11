<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use Psr\Log\LoggerInterface;
use Slim\App;

/**
 * Registers our Global Middlewares
 *
 * @param   App  $app
 */
return function (App $app) {
    $container = $app->getContainer();
    $logger    = $container->get(LoggerInterface::class);

    $app->add(\App\Middleware\BodyParserMiddleware::class);
    $app->add(\App\Middleware\CorsMiddleware::class);
    $app->add(\App\Middleware\ClaimMiddleware::class);
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(true, true, true, $logger);
};
