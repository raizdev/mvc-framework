<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return [
    'settings'             => function () {
        return require __DIR__ . '/../settings.php';
    },
    App::class             => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },
    LoggerInterface::class => function (ContainerInterface $container) {
        $settings       = $container->get('settings');
        $loggerSettings = $settings['logger'];
        $logger         = new Logger($loggerSettings['name']);

        $processor = new UidProcessor();

        $logger->pushProcessor($processor);

        foreach ($loggerSettings['enabled_log_levels'] as $logStreamSettings) {
            $handler = new StreamHandler($logStreamSettings['path'], $logStreamSettings['level'], false);
            $logger->pushHandler($handler);
        }

        return $logger;
    },
    EntityManager::class   => function (ContainerInterface $container) {
        $settings = $container->get('settings');
        $config   = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
            $settings['doctrine']['metadata_dirs'],
            $settings['doctrine']['dev_mode']
        );

        $config->setMetadataDriverImpl(
            new AnnotationDriver(
                new AnnotationReader,
                $settings['doctrine']['metadata_dirs']
            )
        );

        $config->setMetadataCacheImpl(
            new FilesystemCache(
                $settings['doctrine']['cache_dir']
            )
        );

        $config->addCustomStringFunction('MATCH', 'App\Service\MatchAgainstFunction');

        return \Doctrine\ORM\EntityManager::create($settings['doctrine']['connection'], $config);
    },
    \App\Service\ContainerSettingsService::class => function (ContainerInterface $container) {
        return new \App\Service\ContainerSettingsService($container->get('settings'));
    }
];