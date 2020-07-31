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

    $app->get('/', \Ares\Framework\Controller\Status\StatusController::class . ':getStatus');

    $app->group('/api/{locale}', function (RouteCollectorProxy $group) {
        $group->group('', function ($group) {
            // User
            $group->group('/user', function ($group) {
                $group->get('', \Ares\User\Controller\UserController::class . ':user');
                $group->post('/locale', \Ares\User\Controller\UserController::class . ':updateLocale');
            });
            // News
            $group->group('/news', function($group) {
                $group->get('', \Ares\News\Controller\NewsController::class . ':list');
                $group->get('/{id}', \Ares\News\Controller\NewsController::class . ':news');
                $group->get('/slide', \Ares\News\Controller\NewsController::class . ':slide');
            });
            // De-Authentication
            $group->post('/logout', \Ares\User\Controller\AuthController::class . ':logout');
        })->add(\Ares\Framework\Middleware\AuthMiddleware::class);

        // Authentication
        $group->post('/login', \Ares\User\Controller\AuthController::class . ':login');
        $group->group('/register', function (RouteCollectorProxy $group) {
            $group->post('', \Ares\User\Controller\AuthController::class . ':register');
            $group->post('/check', \Ares\User\Controller\AuthController::class . ':check');
        });
    })->add(\Ares\Framework\Middleware\LocaleMiddleware::class);

    // Catches every route that is not found
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    });
};
