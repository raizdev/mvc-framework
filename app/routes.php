<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use App\Middleware\AuthMiddleware;
use Slim\App;

return function (App $app) {
    $app->get('/', 'App\Controller\Status\StatusController:getStatus');

    $app->get('/users', 'App\Controller\User\UserController:all');
    $app->get('/user', 'App\Controller\User\UserController:user')->add(\App\Middleware\AuthMiddleware::class);

    $app->post('/login', 'App\Controller\Auth\AuthController:login');
    $app->post('/register', 'App\Controller\Auth\AuthController:register');
};
