<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Provider;

use Cocur\Slugify\Slugify;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PHLAK\Config\Config;

/**
 * Class SlugServiceProvider
 *
 * @package Ares\Framework\Provider
 */
class SlugServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Slugify::class
    ];

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
