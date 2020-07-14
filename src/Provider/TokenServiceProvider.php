<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Provider;

use App\Service\TokenService;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Class TokenServiceProvider
 *
 * @package App\Provider
 */
class TokenServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        TokenService::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add(TokenService::class, function () use ($container) {
            $config = $container->get('settings')['jwt'];

            $issuer     = (string)$config['issuer'];
            $lifetime   = (int)$config['lifetime'];
            $privateKey = (string)$config['private_key'];
            $publicKey  = (string)$config['public_key'];

            return new TokenService($issuer, $lifetime, $privateKey, $publicKey);
        });
    }
}
