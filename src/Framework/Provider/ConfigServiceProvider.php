<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Logger;
use PHLAK\Config\Config;

/**
 * Class ConfigServiceProvider
 *
 * @package Ares\Framework\Provider
 */
class ConfigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Config::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->share(Config::class, function () {
            return new Config(app_dir() . '/Configs');
        });
    }
}
