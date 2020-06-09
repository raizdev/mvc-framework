<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Provider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

class DatabaseServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'entityManager'
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add('entityManager', function () use ($container) {
            $settings = $container->get('settings');
            $config   = Setup::createAnnotationMetadataConfiguration(
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

            return EntityManager::create($settings['doctrine']['connection'], $config);
        });
    }
}