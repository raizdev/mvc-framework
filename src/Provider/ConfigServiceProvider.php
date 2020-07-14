<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Logger;
use PHLAK\Config\Config;

/**
 * Class ConfigServiceProvider
 *
 * @package App\Provider
 */
class ConfigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'settings',
        Config::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add(Config::class, function () {
            return new Config('../app/Configs');
        });

        $container->add('settings', function () {
            return [
                'doctrine' => [
                    'dev_mode' => $_ENV['DB_DEV_MODE'],

                    'cache_dir' => '../app/Cache/doctrine',

                    'metadata_dirs' => ['src/Entity'],

                    'connection' => [
                        'driver' => 'pdo_mysql',
                        'host' => $_ENV['DB_HOST'],
                        'port' => $_ENV['DB_PORT'],
                        'dbname' => $_ENV['DB_NAME'],
                        'user' => $_ENV['DB_USER'],
                        'password' => $_ENV['DB_PASSWORD'],
                        'charset' => 'utf8mb4'
                    ]
                ],

                'jwt' => [
                    // The issuer name
                    'issuer' => $_ENV['TOKEN_ISSUER'],

                    // Max lifetime in seconds
                    'lifetime' => $_ENV['TOKEN_DURATION'],

                    // The private key
                    'private_key' => $_ENV['TOKEN_PRIVATE'],

                    'public_key' => $_ENV['TOKEN_PUBLIC']
                ],

                'logger' => [
                    'name' => $_ENV['WEB_NAME'] . '-event-log',
                    'enabled_log_levels' => [
                        // DEBUG
                        [
                            'path' => __DIR__ . '/../../app/Logs/info.log',
                            'level' => Logger::DEBUG
                        ],
                        // INFO
                        [
                            'path' => __DIR__ . '/../../app/Logs/info.log',
                            'level' => Logger::INFO
                        ],
                        // NOTICE
                        [
                            'path' => __DIR__ . '/../../app/Logs/info.log',
                            'level' => Logger::NOTICE
                        ],
                        // WARNING
                        [
                            'path' => __DIR__ . '/../../app/Logs/warning.log',
                            'level' => Logger::WARNING
                        ],
                        // ERROR
                        [
                            'path' => __DIR__ . '/../../app/Logs/error.log',
                            'level' => Logger::ERROR
                        ],
                        // CRITICAL
                        [
                            'path' => __DIR__ . '/../../app/Logs/critical.log',
                            'level' => Logger::CRITICAL
                        ],
                        // ALERT
                        [
                            'path' => __DIR__ . '/../../app/Logs/critical.log',
                            'level' => Logger::ALERT
                        ],
                        // EMERGENCY
                        [
                            'path' => __DIR__ . '/../../app/Logs/critical.log',
                            'level' => Logger::EMERGENCY
                        ],
                    ],
                ]
            ];
        });
    }
}
