<?php
use Slim\App;

// Create App instance
$app = $container->get(App::class);;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});