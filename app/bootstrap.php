<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Adds dotenv environment.
require_once __DIR__ . '/etc/dotenv.php';

// Adds laravel database.
require_once __DIR__ . '/etc/database.php';

// Adds custom helper functions.
require_once __DIR__ . '/etc/helpers.php';

// Adds routing initialization.
require_once __DIR__ . '/etc/routing.php';