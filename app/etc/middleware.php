<?php
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();
    $logger = $container->get(\Psr\Log\LoggerInterface::class);

    $app->add(new Tuupola\Middleware\CorsMiddleware([
        "origin" => [$_ENV['WEB_FRONTEND_LINK']],
        "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
        "headers.allow" => ["Content-Type", "Authorization", "If-Match", "If-Unmodified-Since", "Origin"],
        "headers.expose" => ["Content-Type", "Etag", "Origin", "Last-Modified"],
        "credentials" => true,
        "cache" => $_ENV['TOKEN_DURATION']
    ]));

    $app->add(\Raizdev\Framework\Middleware\BodyParserMiddleware::class);
    $app->add(\Raizdev\Framework\Middleware\ClaimMiddleware::class);
    $app->addRoutingMiddleware();

    $errorMiddleware = $app->addErrorMiddleware(true, true, true, $logger);
    $errorMiddleware->setDefaultErrorHandler(\Raizdev\Framework\Handler\ErrorHandler::class);
};
