<?php declare(strict_types=1);

namespace Raizdev\Framework\Mapping\Driver;

use Jgut\Mapping\Driver\AbstractMappingDriver;
use Jgut\Mapping\Driver\Traits\XmlMappingTrait;

/**
 * XML mapping driver.
 */
class XmlDriver extends AbstractMappingDriver
{
    use XmlMappingTrait;
    use MappingTrait;
}