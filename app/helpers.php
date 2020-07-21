<?php

/**
 * Takes message and placeholder to translate them to global locale.
 */
if (!function_exists('__')) {
    function __(string $message, array $placeholder = []) {
        $container = \App\Proxy\App::getContainer();
        $localeService = $container->get(\App\Service\LocaleService::class);
        return $localeService->translate($message, $placeholder);
    }
}