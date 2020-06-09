<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use League\Container\Container;

/**
 * Registers our Services
 *
 * @param   Container  $container
 */
return [
    // Registers our TokenService
    $container->add('generateTokenService', function () use ($container) {
        return new \App\Service\GenerateTokenService($container);
    }),
    // Registers our ValidationService
    $container->add('validationService', function () use ($container) {
        return new \App\Validation\ValidationService();
    })
];