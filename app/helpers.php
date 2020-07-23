<?php

/**
 * Takes message and placeholder to translate them to global locale.
 */
if (!function_exists('__')) {
    function __(string $message, array $placeholder = []) {
        $container = \Ares\Framework\Proxy\App::getContainer();
        $localeService = $container->get(\Ares\Framework\Service\LocaleService::class);
        return $localeService->translate($message, $placeholder);
    }
}

/**
 * Returns directory path of app.
 */
if (!function_exists('app_dir')) {
    function app_dir() {
        return __DIR__;
    }
}

/**
 * Returns directory path of root.
 */
if (!function_exists('base_dir')) {
    function base_dir() {
        return __DIR__ . '/../';
    }
}