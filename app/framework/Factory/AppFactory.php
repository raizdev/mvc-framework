<?php declare(strict_types=1);
namespace Raizdev\Framework\Factory;

use Raizdev\Framework\RouteCollector;
use Raizdev\Framework\Configuration;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\CallableResolver;
use Slim\Factory\AppFactory as SlimAppFactory;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\MiddlewareDispatcherInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteResolverInterface;

/**
 * Custom routing aware AppFactory.
 */
class AppFactory extends SlimAppFactory
{
    /**
     * @var Configuration
     */
    protected static Configuration $configuration;

    /**
     * {@inheritdoc}
     */
    public static function create(
        ?ResponseFactoryInterface $responseFactory = null,
        ?ContainerInterface $container = null,
        ?CallableResolverInterface $callableResolver = null,
        ?RouteCollectorInterface $routeCollector = null,
        ?RouteResolverInterface $routeResolver = null,
        ?MiddlewareDispatcherInterface $middlewareDispatcher = null
    ): App {
        static::$responseFactory = $responseFactory ?? static::$responseFactory;

        $responseFactory = self::determineResponseFactory();
        $container = $container ?? static::$container;
        $callableResolver = $callableResolver ?? static::getCallableResolver($container);

        return new App(
            $responseFactory,
            $container,
            $callableResolver,
            $routeCollector ?? static::getRouteCollector($responseFactory, $callableResolver, $container),
            $routeResolver ?? static::$routeResolver,
            $middlewareDispatcher ?? static::$middlewareDispatcher
        );
    }

    /**
     * Get callable resolver.
     *
     * @param ContainerInterface|null $container
     *
     * @return CallableResolverInterface
     */
    protected static function getCallableResolver(?ContainerInterface $container = null): CallableResolverInterface
    {
        return static::$callableResolver ?? new CallableResolver($container);
    }

    /**
     * Get route collector.
     *
     * @param ResponseFactoryInterface  $responseFactory
     * @param CallableResolverInterface $callableResolver
     * @param ContainerInterface|null   $container
     *
     * @return RouteCollectorInterface
     */
    protected static function getRouteCollector(
        ResponseFactoryInterface $responseFactory,
        CallableResolverInterface $callableResolver,
        ?ContainerInterface $container = null
    ): RouteCollectorInterface {
        $configuration = static::$configuration ?? new Configuration();

        return static::$routeCollector
            ?? new RouteCollector($configuration, $responseFactory, $callableResolver, $container);
    }

    /**
     * Set route collector configurations.
     *
     * @param Configuration $configuration
     */
    final public static function setRouteCollectorConfiguration(Configuration $configuration): void
    {
        static::$configuration = $configuration;
    }
}