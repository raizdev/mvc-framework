<?php declare(strict_types=1);

namespace StarreDEV\Framework\Service;

/**
 * Import classes
 */
use Psr\Container\ContainerInterface;

/**
 * ContainerAwareTrait
 */
trait ContainerService
{

    /**
     * The application container
     *
     * @Inject
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Gets the application container
     *
     * @return ContainerInterface
     */
    public function getContainer() : ContainerInterface
    {
        return $this->container;
    }
}