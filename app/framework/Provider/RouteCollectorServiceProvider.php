<?php
namespace Raizdev\Framework\Provider;

use Raizdev\Framework\Response\Handler\JsonResponseHandler;
use Raizdev\Framework\Response\Handler\XmlResponseHandler;
use Raizdev\Framework\Response\PayloadResponse;
use Raizdev\Framework\RouteCollector;
use Raizdev\Framework\Strategy\RequestHandler;
use Phpfastcache\Helper\Psr16Adapter;
use Phpfastcache\Helper\Psr16Adapter as FastCache;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\App;
use Slim\Interfaces\RouteCollectorInterface;

/**
 * Class RouteCollectorServiceProvider
 *
 */
class RouteCollectorServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        RouteCollectorInterface::class
    ];

    /**
     * Registers new service.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(RouteCollectorInterface::class, function () use ($container) {
            /** @var App $app */
            $app = $container->get(App::class);

            /** @var Psr16Adapter $cache */
            $cache = $container->get(FastCache::class);

            /** @var RouteCollector $routeCollector */
            $routeCollector = $app->getRouteCollector();

            // Register custom invocation strategy to handle ResponseType objects
            $invocationStrategy = new RequestHandler(
                [
                    PayloadResponse::class => JsonResponseHandler::class,
                ],
                $app->getResponseFactory(),
                $app->getContainer()
            );

            $invocationStrategy->setResponseHandler(PayloadResponse::class, XmlResponseHandler::class);

            $routeCollector->setDefaultInvocationStrategy($invocationStrategy);
            $routeCollector->registerRoutes();
            $routeCollector->setCache($cache);
            $routeCollector->setCachePrefix("ARES_ROUTE_COLLECTOR");

            return $routeCollector;
        });
    }
}