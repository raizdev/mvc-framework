<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

/**
 * Registers our ServiceProviders
 */
return [
    //Adds our ConfigProvider
    $container->addServiceProvider(
        new \App\Provider\ConfigServiceProvider()
    ),
    //Adds our DatabaseProvider
    $container->addServiceProvider(
        new \App\Provider\DatabaseServiceProvider()
    ),
    //Adds our LoggingProvider
    $container->addServiceProvider(
        new \App\Provider\LoggingServiceProvider()
    )
];
