<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */
declare(strict_types=1);

use App\Repository\User\UserRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

/**
 * EM needs to be given to the repositories
 *
 * @param ContainerBuilder $containerBuilder
 */
return [
    UserRepository::class => function (ContainerInterface $container) {
        $em = $container->get(\Doctrine\ORM\EntityManager::class);
        return new UserRepository($em);
    },
];