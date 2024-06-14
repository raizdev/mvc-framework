<?php
use Raizdev\Framework\Exception\AuthenticationException;
use Raizdev\Framework\Exception\DataObjectManagerException;
use Raizdev\Framework\Exception\NoSuchEntityException;
use Raizdev\Framework\Interfaces\CustomResponseInterface;
use Raizdev\Framework\Interfaces\HttpResponseCodeInterface;
use Raizdev\Framework\Model\CustomResponse;
use Raizdev\Framework\Model\Query\Collection;
use Raizdev\Framework\Proxy\App;
use Raizdev\Framework\Repository\BaseRepository;
use Raizdev\Framework\Service\LocaleService;
use Raizdev\User\Repository\UserRepository;
use League\Container\Container;
use Psr\Http\Message\ServerRequestInterface as Request;

use Raizdev\User\Model\UserModel;

if (!function_exists('__')) {
    /**
     * Takes message and placeholder to translate them to global locale.
     *
     * @param string $key
     * @param array $placeholder
     * @return string
     */
    function __(string $key, array $placeholder = []): string {
        $container = App::getContainer();
        /** @var LocaleService $localeService */
        $localeService = $container->get(LocaleService::class);
        return $localeService->translate($key, $placeholder);
    }
}

if (!function_exists('response')) {
    /**
     * Returns instance of custom response.
     *
     * @return CustomResponseInterface
     */
    function response(): CustomResponseInterface {
        $container = App::getContainer();
        return $container->get(CustomResponse::class);
    }
}

if (!function_exists('app_dir')) {
    /**
     * Returns directory path of app.
     *
     * @return string
     */
    function app_dir(): string {
        return __DIR__ . '/..';
    }
}

if (!function_exists('src_dir')) {
    /**
     * Returns directory path of src.
     *
     * @return string
     */
    function src_dir(): string {
        return __DIR__ . '/../../src';
    }
}

if (!function_exists('base_dir')) {
    /**
     * Returns directory path of root.
     *
     * @return string
     */
    function base_dir(): string {
        return __DIR__ . '/../../';
    }
}

if (!function_exists('cache_dir')) {
    /**
     * Returns directory path of cache.
     *
     * @return string
     */
    function cache_dir(): string {
        return __DIR__ . '/../../tmp/Cache';
    }
}

if (!function_exists('tmp_dir')) {
    /**
     * Returns directory path of tmp directory.
     *
     * @return string
     */
    function tmp_dir(): string {
        return __DIR__ . '/../../tmp';
    }
}

if (!function_exists('route_cache_dir')) {
    /**
     * Returns directory path of routing cache.
     *
     * @return string
     */
    function route_cache_dir(): string {
        return __DIR__ . '/../../tmp/Cache/routing';
    }
}

if (!function_exists('container')) {
    /**
     * Returns di container instance.
     *
     * @return Container
     */
    function container(): Container
    {
        return App::getContainer();
    }
}

if (!function_exists('user')) {
    /**
     * Returns current authenticated user.
     *
     */
    function user(Request $request, bool $isCached = false) {
        /** @var array $user */
        $authUser = $request->getAttribute('ares_uid');

        if (!$authUser) {
            return json_decode(json_encode($authUser), true);
        }

        /** @var UserRepository $userRepository */
        $userRepository = container()->get(UserModel::class);

        /** @var User $user */
        $user = $userRepository->find((int) $authUser);

        if (!$user) {
            throw new AuthenticationException(
                __('User doesnt exists.'),
                401, 401
            );
        }

        return $user;
    }
}
