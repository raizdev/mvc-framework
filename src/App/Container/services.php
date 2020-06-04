<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use App\Service\User\UserService;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

/**
 * Repositories needs to be parsed to Services
 *
 * @param ContainerBuilder $containerBuilder
 */
return [
    UserService::class => function (ContainerInterface $container) {
        $userRepository = $container->get(\App\Repository\User\UserRepository::class);
        return new UserService($userRepository);
    },
];