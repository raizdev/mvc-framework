<?php declare(strict_types=1);

namespace Raizdev\Framework\Naming;

/**
 * Snake case route naming strategy.
 */
class SnakeCase implements Strategy
{
    /**
     * {@inheritdoc}
     */
    public function combine(array $nameParts): string
    {
        return \implode('_', $nameParts);
    }
}