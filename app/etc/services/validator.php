<?php declare(strict_types=1);

use StarreDEV\Framework\Service\ValidatorService;
use StarreDEV\Framework\Service\ContainerService;

use function DI\factory;

return [
    ValidatorService::class => factory(function ($container) {
        $config = $container->get(ConfigInterface::class);
        return new Validator($config->get('validation'));
    })
];