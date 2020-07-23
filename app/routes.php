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

    $app->get('/', 'Ares\Framework\Controller\Status\StatusController:getStatus');

    $app->group('/api/{locale}', function (RouteCollectorProxy $group) {
        $group->group('', function (RouteCollectorProxy $group) {
            $group->get('/users', 'Ares\User\Controller\UserController:all');
            $group->get('/user', 'Ares\User\Controller\UserController:user');
            $group->post('/logout', 'Ares\User\Controller\AuthController:logout');
        })->add(\Ares\Framework\Middleware\AuthMiddleware::class);

        $group->post('/login', 'Ares\User\Controller\AuthController:login');
        $group->post('/register', 'Ares\User\Controller\AuthController:register');
    })->add(\Ares\Framework\Middleware\LocaleMiddleware::class);

    // Catches every route that is not found
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    });
};
