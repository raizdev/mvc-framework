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
use ReflectionClass;

/**
 * Class DataObjectManager
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
     * @var array
     */
    private array $relations = [];

    /**
     * Returns models as collection.
     *
     * @param string[] $columns
     * @return Collection
     */
    public function get($columns = ['*']): Collection
    {
        $items = [];

        foreach (parent::get($this->getColumns($columns)) as $item) {
            foreach ($this->entity::HIDDEN as $column) {
                unset($item->$column);
            }

            if(isset($item->aggregate)) {
                $items[] = $item;
                continue;
            }

            $entity = new $this->entity($item);

            foreach ($this->relations as $relation) {
                $entity->{$relation}();
            }

            $items[] = $entity;
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
     * Add relation by key to collection call.
     *
     * @param string $relation
     *
     * @return DataObjectManager
     */
    public function addRelation(string $relation): DataObjectManager
    {
        $relations = $this->entity::RELATIONS;

        if (!isset($relations[$relation])) {
            throw new DataObjectManagerException(
                __('Tried to use undefined relation: "%s"', [$relation]),
                500
            );
        }

        $this->relations[] = $relations[$relation];

        return $this;
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

    /**
     * Returns selectable columns.
     *
     * @param array $columns
     * @return array
     */
    private function getColumns(array $columns): array
    {
        if ($columns !== ['*']) {
            return $columns;
        }

        if ($this->columns) {
            return $this->columns;
        }

        try {
            $reflectionClass = new ReflectionClass($this->entity);
            $constants = $reflectionClass->getConstants();

            unset(
                $constants['TABLE'],
                $constants['HIDDEN'],
                $constants['RELATIONS'],
                $constants['PRIMARY_KEY']
            );

            return array_values($constants);
        } catch (\ReflectionException $e) {
            return $columns;
        }
    }
}
