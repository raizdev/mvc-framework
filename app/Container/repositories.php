<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use App\Repository\User\UserRepository;
use League\Container\Container;

/**
 * Registers our Repositories
 *
 * @param   Container  $container
 */
return [
    // Registers our UserRepository
    $container->add('userRepository', function () use ($container) {
        $entityManager = $container->get('entityManager');

        return new UserRepository($entityManager);
    })
];