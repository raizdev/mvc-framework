<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;

/**
 * Class RouteServiceProvider
 *
 * @package App\Provider
 */
class RouteServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ResponseFactoryInterface::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add(ResponseFactoryInterface::class, function () use ($container) {
            return $container->get(App::class)->getResponseFactory();
        });
    }
}