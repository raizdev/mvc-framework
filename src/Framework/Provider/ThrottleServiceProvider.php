<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Provider;

use Ares\Framework\Middleware\ThrottleMiddleware;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Predis\Client;

/**
 * Class ThrottleServiceProvider
 *
 * @package Ares\Framework\Provider
 */
class ThrottleServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ThrottleMiddleware::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add(ThrottleMiddleware::class, function () use ($container) {
            $settings = $container->get('settings');

            $predis = new Client([
                'host' => $settings['cache']['redis_host'],
                'port' => (int)$settings['cache']['redis_port']
            ]);

            $throttleMiddleware = new ThrottleMiddleware($predis);
            $throttleMiddleware
                ->setRateLimit(10, 5)
                ->setStorageKey('ARES_API_THROTTLE:%s');

            return $throttleMiddleware;
        });
    }
}
