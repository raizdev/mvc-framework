<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use Slim\App;

return function (App $app) {
    // Enables Lazy CORS
    $app->options('/{routes:.+}', function ($request, $response, $arguments) {
        return $response;
    });

    $app->get('/', 'App\Controller\Status\StatusController:getStatus');

    $app->get('/users', 'App\Controller\User\UserController:all');
    $app->get('/user', 'App\Controller\User\UserController:user')->add(\App\Middleware\AuthMiddleware::class);

    $app->post('/login', 'App\Controller\Auth\AuthController:login');
    $app->post('/register', 'App\Controller\Auth\AuthController:register');

    // Catches every route that is not found
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    });
};
