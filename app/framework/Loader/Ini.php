<?php
namespace Raizdev\Framework\Loader;

use Raizdev\Framework\Exception\InvalidFileException;

class Ini extends Loader
{
    /**
     * @throws \Raizdev\Framework\Exception\InvalidFileException
     *
     * @return array Array of configuration options
     */
    public function getArray(): array
    {
        $parsed = @parse_ini_file($this->context, true);

        if (! $parsed) {
            throw new InvalidFileException('Unable to parse invalid INI file at ' . $this->context);
        }

        return $parsed;
    }
}
