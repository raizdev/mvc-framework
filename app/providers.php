<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

/**
 * Registers our ServiceProviders
 */
return [
    // Adds our LocaleProvider to add locales
    $container->addServiceProvider(
        new \App\Provider\LocaleServiceProvider()
    ),
    // Adds our ConfigProvider
    $container->addServiceProvider(
        new \App\Provider\ConfigServiceProvider()
    ),
    // Adds our DatabaseProvider
    $container->addServiceProvider(
        new \App\Provider\DatabaseServiceProvider()
    ),
    // Adds our LoggingProvider
    $container->addServiceProvider(
        new \App\Provider\LoggingServiceProvider()
    ),
    // Adds our RouteProvider
    $container->addServiceProvider(
        new \App\Provider\RouteServiceProvider()
    ),
    // Adds our AppProvider and creates App
    $container->addServiceProvider(
        new \App\Provider\AppServiceProvider()
    ),
    // Adds our TokenProvider
    $container->addServiceProvider(
        new \App\Provider\TokenServiceProvider()
    ),
    // Adds our CacheProvider to Cache Responses
    $container->addServiceProvider(
        new \App\Provider\CacheServiceProvider()
    )
];
