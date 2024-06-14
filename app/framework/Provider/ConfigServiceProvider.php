<?php
namespace Raizdev\Framework\Provider;

use Raizdev\Framework\Exception\InvalidContextException;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Raizdev\Framework\Config;

/**
 * Class ConfigServiceProvider
 *
 * @package Raizdev\Framework\Provider
 */
class ConfigServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        Config::class
    ];

    /**
     * Registers new service.
     * @throws InvalidContextException
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share(Config::class, function () {
            return new Config(app_dir() . '/configs');
        });
    }
}
