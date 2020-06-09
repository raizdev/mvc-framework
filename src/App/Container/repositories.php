<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use App\Repository\User\UserRepository;
use League\Container\Container;

/**
 * EM needs to be given to the repositories
 *
 * @param   Container  $container
 */
return [
    $container->add('userRepository', function () use ($container) {
        $em = $container->get('entityManager');

        return new UserRepository($em);
    })
];