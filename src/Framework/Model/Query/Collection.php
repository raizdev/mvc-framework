<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Model\Query;

use Illuminate\Support\Collection as IlluminateCollection;

/**
 * Class Collection
 *
 * @package Ares\Framework\Model\Query
 */
class Collection extends IlluminateCollection
{
    /**
     * @param mixed $key
     * @param null  $default
     *
     * @return array|mixed
     */
    public function get($key, $default = null)
    {
        $result = array_column($this->items, $key);

        if (!$result) {
            return value($default);
        }

        return $result;
    }
}
