<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use League\Container\Container;

/**
 * Registers our Controllers
 *
 * @param   Container  $container
 */
return [
    // Registers our UserController
    $container->add(\App\Controller\User\UserController::class, function () use ($container) {
        $userRepository = $container->get('userRepository');

        return new \App\Controller\User\UserController(
            $userRepository
        );
    })
];
