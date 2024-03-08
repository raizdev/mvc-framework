<?php declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;

return (function () : Container {
    $env = $_ENV['APP_ENV'] ?? 'dev';

    $builder = new ContainerBuilder();
    $builder->useAutowiring(true);
    $builder->useAnnotations(true);

    $builder->addDefinitions(
        ...glob(__DIR__ . '/services/*.php')
    );

    if ('prod' === $env) {
        $builder->enableCompilation(__DIR__ . '/../cache');
        $builder->enableDefinitionCache('container');
        $builder->writeProxiesToFile(true, __DIR__ . '/../cache');
    }

    return $builder->build();
})();