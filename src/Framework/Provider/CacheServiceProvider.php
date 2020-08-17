<?php
namespace Ares\Framework\Provider;

use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Helper\Psr16Adapter as FastCache;
use League\Container\ServiceProvider\AbstractServiceProvider;

class CacheServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        FastCache::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->share(FastCache::class, function () {
            $configurationOption = new ConfigurationOption([
                'path' => cache_dir()
            ]);

            return new FastCache($_ENV['CACHE_TYPE'], $configurationOption);
        });
    }
}
