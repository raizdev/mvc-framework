<?php

namespace Raizdev\Framework\Loader;

use Raizdev\Framework\Interfaces\Loadable;

abstract class Loader implements Loadable
{
    /** @var string Path to a configuration file or directory */
    protected $context;

    /**
     * Create a new Loader object.
     *
     * @param string $context Path to configuration file or directory
     */
    public function __construct(string $context)
    {
        $this->context = $context;
    }

    /**
     * Retrieve the context as an array of configuration options.
     *
     * @return array Array of configuration options
     */
    abstract public function getArray(): array;
}
