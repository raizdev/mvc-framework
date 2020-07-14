<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\App;
use Slim\Factory\AppFactory;

/**
 * Class AppServiceProvider
 *
 * @package App\Provider
 */
class AppServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        App::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add(App::class, function () use ($container) {
            AppFactory::setContainer($container);

            return AppFactory::create();
        });
    }
}
