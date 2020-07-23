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