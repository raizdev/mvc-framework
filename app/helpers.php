<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

use Ares\Framework\Repository\BaseRepository;
use League\Container\Container;

if (!function_exists('__')) {
    /**
     * Takes message and placeholder to translate them to global locale.
     *
     * @param string $key
     * @param array $placeholder
     * @return string
     */
    function __(string $key, array $placeholder = []): string {
        $container = \Ares\Framework\Proxy\App::getContainer();
        /** @var \Ares\Framework\Service\LocaleService $localeService */
        $localeService = $container->get(\Ares\Framework\Service\LocaleService::class);
        return $localeService->translate($key, $placeholder);
    }
}

if (!function_exists('response')) {
    /**
     * Returns instance of custom response.
     *
     * @return \Ares\Framework\Interfaces\CustomResponseInterface
     */
    function response(): \Ares\Framework\Interfaces\CustomResponseInterface {
        $container = \Ares\Framework\Proxy\App::getContainer();
        return $container->get(\Ares\Framework\Model\CustomResponse::class);
    }
}

if (!function_exists('app_dir')) {
    /**
     * Returns directory path of app.
     *
     * @return string
     */
    function app_dir(): string {
        return __DIR__;
    }
}

if (!function_exists('base_dir')) {
    /**
     * Returns directory path of root.
     *
     * @return string
     */
    function base_dir(): string {
        return __DIR__ . '/../';
    }
}

if (!function_exists('cache_dir')) {
    /**
     * Returns directory path of cache.
     *
     * @return string
     */
    function cache_dir(): string {
        return __DIR__ . '/../tmp/Cache';
    }
}

if (!function_exists('tmp_dir')) {
    /**
     * Returns directory path of tmp directory.
     *
     * @return string
     */
    function tmp_dir(): string {
        return __DIR__ . '/../tmp';
    }
}

if (!function_exists('route_cache_dir')) {
    /**
     * Returns directory path of routing cache.
     *
     * @return string
     */
    function route_cache_dir(): string {
        return __DIR__ . '/../tmp/Cache/routing';
    }
}

if (!function_exists('container')) {
    /**
     * Returns di container instance.
     *
     * @return Container
     */
    function container(): \League\Container\Container {
        return \Ares\Framework\Proxy\App::getContainer();
    }
}

if (!function_exists('repository')) {
    /**
     * Returns repository by given namespace.
     *
     * @param string $repository
     * @return BaseRepository
     */
    function repository(string $repository): ?BaseRepository {
        $container = container();
        $repository = $container->get($repository);

        if (!$repository instanceof BaseRepository) {
            throw new \Ares\Framework\Exception\DataObjectManagerException(
                __('Tried to instantiating not existing repository "%s"', [$repository]),
                500
            );
        }

        return $repository;
    }
}