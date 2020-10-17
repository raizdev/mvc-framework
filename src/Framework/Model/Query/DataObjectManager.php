<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Model\Query;

use Ares\Framework\Model\DataObject;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class SearchBuilder
 *
 * @package Ares\Framework\Model\Query
 */
class DataObjectManager extends Builder
{
    /**
     * @var string
     */
    private string $entity;

    /**
     * Returns models as collection.
     *
     * @param string[] $columns
     * @return Collection
     */
    public function get($columns = ['*']): Collection
    {
        $items = [];

        foreach (parent::get($columns) as $item) {
            $items[] = new $this->entity($item);
        }

        return collect($items);
    }

    /**
     * Returns single model.
     *
     * @param string[] $columns
     * @return DataObject|null
     */
    public function first($columns = ['*']): ?DataObject
    {
        return $this->take(1)->get($columns)->first();
    }

    /**
     * Returns models as collection and ignores given fields.
     *
     * @param string[] $columns
     * @return Collection
     */
    public function getExcept($columns = []): Collection
    {
        $items = [];

        foreach (parent::get(['*']) as $item) {
            foreach ($columns as $column) {
                unset($item->$column);
            }
            $items[] = new $this->entity($item);
        }

        return collect($items);
    }

    /**
     * Sets model.
     *
     * @param string $entity
     */
    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }
}
