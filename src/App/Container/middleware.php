<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */
declare(strict_types=1);

use Slim\App;
use Tuupola\Middleware\JwtAuthentication;

return function (App $app) {
    $container = $app->getContainer();

    $settings = $container->get('settings');
    $logger = $container->get(\Psr\Log\LoggerInterface::class);

    $app->add(
        new JwtAuthentication(
            [
                'ignore' => ['/', '/login', '/register'],
                'secret' => $settings['jwt']['secret'],
                'algorithm' => [$settings['jwt']['algorithm']],
                'logger' => $logger,
                // HTTPS not mandatory for local development
                'relaxed' => ['localhost'],
                'error' => function ($response, $arguments) {
                    $data['status'] = 'error';
                    $data['message'] = $arguments['message'];
                    return $response->getBody()->write(
                        json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT, 512)
                    );
                }
            ]
        )
    );

    $app->addRoutingMiddleware();

    /*
     * Add Error Handling Middleware
     *
     * @param bool $displayErrorDetails -> Should be set to false in production
     * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
     * @param bool $logErrorDetails -> Display error details in error log
     * which can be replaced by a callable of your choice.
     * Note: This middleware should be added last. It will not handle any exceptions/errors
     * for middleware added after it.
    */
    $errorMiddleware = $app->addErrorMiddleware(true, true, true, $logger);
};