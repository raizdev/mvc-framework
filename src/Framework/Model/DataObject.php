<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Model;

/**
 * Class DataObject
 *
 * @package Ares\Framework\Model
 */
class DataObject
{
    /** @var array */
    public const HIDDEN = [];

    /** @var array */
    public const RELATIONS = [];

    /**
     * DataObject constructor.
     *
     * @param $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => &$value) {
            $this->setData($key, $value);
        }
    }

    /**
     * Get data by key.
     *
     * @param string|null $key
     * @return mixed|array|null
     */
    public function getData($key = null)
    {
        if (!$key) {
            return (array) $this ?? [];
        }

        if (!isset($this->{$key})) {
            return null;
        }

        return $this->{$key};
    }

    /**
     * Set data by key.
     *
     * @param string $key
     * @param $value
     * @return $this
     */
    public function setData(string $key, $value)
    {
        $this->{$key} = $value;
        return $this;
    }
}