<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */
declare(strict_types=1);

use Monolog\Logger;

// Settings
$settings = [];

// Path settings
$settings['root']   = dirname(__DIR__);

// Database Settings
$settings['doctrine'] = [
    'dev_mode' => getenv('DB_DEV_MODE'),

    'cache_dir' => $settings['root'] . '/App/Cache/doctrine',

    'metadata_dirs' => ['src/Entity'],

    'connection' => [
        'driver'   => getenv('DB_DRIVER'),
        'host'     => getenv('DB_HOST'),
        'port'     => getenv('DB_PORT'),
        'dbname'   => getenv('DB_NAME'),
        'user'     => getenv('DB_USER'),
        'password' => getenv('DB_PASS'),
        'charset' => 'utf8mb4'
    ]
];

// JWT Settings
$settings['jwt'] = [
    'secret'    => getenv('WEB_SECRET'),
    'algorithm' => 'HS256',
];

// Logger Settings
$settings['logger'] = [
    'name'               => 'ares-event-log',
    'enabled_log_levels' => [
        // DEBUG
        [
            'path'  => $settings['root'] . '/Logs/info.log',
            'level' => Logger::DEBUG
        ],
        // INFO
        [
            'path'  => $settings['root'] . '/Logs/info.log',
            'level' => Logger::INFO
        ],
        // NOTICE
        [
            'path'  => $settings['root'] . '/Logs/info.log',
            'level' => Logger::NOTICE
        ],
        // WARNING
        [
            'path'  => $settings['root'] . '/Logs/warning.log',
            'level' => Logger::WARNING
        ],
        // ERROR
        [
            'path'  => $settings['root'] . '/Logs/error.log',
            'level' => Logger::ERROR
        ],
        // CRITICAL
        [
            'path'  => $settings['root'] . '/Logs/critical.log',
            'level' => Logger::CRITICAL
        ],
        // ALERT
        [
            'path'  => $settings['root'] . '/Logs/critical.log',
            'level' => Logger::ALERT
        ],
        // EMERGENCY
        [
            'path'  => $settings['root'] . '/Logs/critical.log',
            'level' => Logger::EMERGENCY
        ],
    ],
];

return $settings;
