<?php
use PHLAK\Config\Config;
use StarreDEV\Framework\Interfaces\CustomResponseInterface;
use StarreDEV\Framework\Model\CustomResponse;
use StarreDEV\Framework\Model\Container;
use Odan\Session\PhpSession;
use Psr\Container\ContainerInterface;

use StarreDEV\Framework\Exception\AuthenticationException;
use StarreDEV\Framework\Interfaces\HttpResponseCodeInterface;
use StarreDEV\Models\User;

if (!function_exists('__')) {
    /**
     * Takes message and placeholder to translate them to global locale.
     *
     * @param string $key
     * @param array $placeholder
     * @return string
     */
    function __(string $key, array $placeholder = []): string {
        $locale = new \StarreDEV\Framework\Model\Locale();
        return $locale->translate($key, $placeholder);
    }
}

if (!function_exists('response')) {
    /**
     * Returns instance of custom response.
     *
     * @return CustomResponseInterface
     */
    function response(): CustomResponseInterface {
        return new CustomResponse();
    }
}


if (!function_exists('app_dir')) {
    /**
     * Returns directory path of app.
     *
     * @return string
     */
    function app_dir(): string {
        return __DIR__ . '/../../';
    }
}


if (!function_exists('config')) {
    /**
     * Returns directory path of app.
     *
     * @return string
     */
    
    function config($key = '') {
        $config = new Config(__DIR__ . '/../config/settings.json');
        return $config->get($key);
    }
}

if (!function_exists('debug')) {
    /**
     * Returns directory path of app.
     *
     * @return string
     */
    function debug($string): Response
    {
        echo '<pre>';
        print_r($string);
        exit;
    }
}

if (!function_exists('user')) {

}