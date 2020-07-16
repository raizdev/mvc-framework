<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\HttpCache\Cache;

/**
 * Class CacheServiceProvider
 *
 * @package App\Provider
 */
class CacheServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Cache::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add(Cache::class, function () {
            return new Cache('private', $_ENV['TOKEN_DURATION']);
        });
    }
}
