<?php declare(strict_types=1);

use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;

use function DI\factory;

return [
    SessionInterface::class => factory(function ($container) {
        $session = new PhpSession();
        $session->setOptions([
            'name' => $_ENV['SESSION_NAME'],
            'cache_expire' => $_ENV['SESSION_CACHE_EXPIRE'],
        ]);

        $session->start();
        return $session;
    })
];