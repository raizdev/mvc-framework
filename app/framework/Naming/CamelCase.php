<?php declare(strict_types=1);

namespace Raizdev\Framework\Naming;

/**
 * Camel case route naming strategy.
 */
class CamelCase implements Strategy
{
    /**
     * {@inheritdoc}
     */
    public function combine(array $nameParts): string
    {
        return \lcfirst(\implode('', \array_map('ucfirst', $nameParts)));
    }
}