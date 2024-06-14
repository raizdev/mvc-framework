<?php
namespace Raizdev\Framework\Provider;

use Cocur\Slugify\Slugify;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Raizdev\Framework\Config;

/**
 * Class SlugServiceProvider
 *
 * @package Raizdev\Framework\Provider
 */
class SlugServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        Slugify::class
    ];

    /**
     * Registers new service.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(Slugify::class, function () use ($container) {
            $config = $container->get(Config::class);
            return new Slugify([
                'trim' => $config->get('api_settings.slug.trim')
            ]);
        });
    }
}
