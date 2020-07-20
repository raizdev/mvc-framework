<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Enables Lazy CORS - Preflight Request
    $app->options('/{routes:.+}', function ($request, $response, $arguments) {
        return $response;
    });

    $app->get('/', 'App\Controller\Status\StatusController:getStatus');

    $app->group('/api/{locale}', function (RouteCollectorProxy $group) {
        $group->get('/users', 'App\Controller\User\UserController:all');
        $group->get('/user', 'App\Controller\User\UserController:user')->add(\App\Middleware\AuthMiddleware::class);

        $group->post('/login', 'App\Controller\Auth\AuthController:login');
        $group->post('/register', 'App\Controller\Auth\AuthController:register');
    })->add(\App\Middleware\LocaleMiddleware::class);

    // Catches every route that is not found
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    });
};
