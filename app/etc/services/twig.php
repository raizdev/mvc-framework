<?php declare(strict_types=1);

use StarreDEV\Framework\Service\TwigService;
use StarreDEV\Framework\Provider\TwigServiceProvider;

return [
    TwigService::class => DI\factory([TwigServiceProvider::class, 'register'])
];