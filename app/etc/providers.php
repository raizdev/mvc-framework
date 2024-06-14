<?php
return [
    // Adds our LocaleProvider to add locales
    $container->addServiceProvider(
        new \Raizdev\Framework\Provider\LocaleServiceProvider()
    ),
    // Adds our ConfigProvider
    $container->addServiceProvider(
        new \Raizdev\Framework\Provider\ConfigServiceProvider()
    ),
    // Adds our LoggingProvider
    $container->addServiceProvider(
        new \Raizdev\Framework\Provider\LoggingServiceProvider()
    ),
    // Adds our AppProvider and creates App
    $container->addServiceProvider(
        new \Raizdev\Framework\Provider\AppServiceProvider()
    ),
    // Adds our RouteCollectorProvider
    $container->addServiceProvider(
        new \Raizdev\Framework\Provider\RouteCollectorServiceProvider()
    ),
    // Adds our RouteProvider
    $container->addServiceProvider(
        new \Raizdev\Framework\Provider\RouteServiceProvider()
    ),
    // Adds our ValidationProvider
    $container->addServiceProvider(
        new \Raizdev\Framework\Provider\ValidationServiceProvider()
    ),
    // Adds our CacheServiceProvider
    $container->addServiceProvider(
        new \Raizdev\Framework\Provider\CacheServiceProvider()
    ),
    // Adds our SlugServiceProvider
    $container->addServiceProvider(
        new \Raizdev\Framework\Provider\SlugServiceProvider()
    ),
    // Adds our ThrottleServiceProvider
    $container->addServiceProvider(
        new \Raizdev\Framework\Provider\ThrottleServiceProvider()
    )
];
