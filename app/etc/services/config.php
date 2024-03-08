<?php declare(strict_types=1);

use PHLAK\Config\Interfaces\ConfigInterface;
use PHLAK\Config\Config;

use function DI\factory;

return [
    ConfigInterface::class => factory(function ($container) {
        return new Config(__DIR__ . '/../../config/settings.json');
    })
];