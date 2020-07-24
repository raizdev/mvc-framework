<?php

if (!function_exists('__')) {
    /**
     * Takes message and placeholder to translate them to global locale.
     *
     * @param string $message
     * @param array $placeholder
     * @return mixed
     */
    function __(string $message, array $placeholder = []) {
        $container = \Ares\Framework\Proxy\App::getContainer();
        $localeService = $container->get(\Ares\Framework\Service\LocaleService::class);
        return $localeService->translate($message, $placeholder);
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
    function app_dir() {
        return __DIR__;
    }
}

if (!function_exists('base_dir')) {
    /**
     * Returns directory path of root.
     *
     * @return string
     */
    function base_dir() {
        return __DIR__ . '/../';
    }
}